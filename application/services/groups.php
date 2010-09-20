<?php
require '../system/YSSEnvironmentServices.php';

require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFilesizeValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFileValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';

require YSSApplication::basePath().'/application/system/YSSService.php';
require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/data/YSSAnnotation.php';
require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSTask.php';
require YSSApplication::basePath().'/application/data/YSSTaskGroup.php';

require YSSApplication::basePath().'/application/data/YSSAttachment.php';

if(AWS_S3_ENABLED) require 'Zend/Service/Amazon/S3.php';


class YSSServiceGroups extends YSSService
{
	
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/project/{project_id}/group/task/{group_id}",                "getTasksInGroup");
				$this->addEndpoint("GET",    "/api/project/{project_id}/group/task",                           "getTaskGroups");
				break;
			
			case "POST":
				$this->addEndpoint("POST",    "/api/project/{project_id}/group/task",                          "createGroup");
				$this->addEndpoint("POST",    "/api/project/{project_id}/group/task/{group_id}/{task_id}",     "addTaskToGroup");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE",    "/api/project/{project_id}/group/task/{group_id}",             "deleteGroup");
				$this->addEndpoint("DELETE",    "/api/project/{project_id}/group/task/{group_id}/{task_id}",   "deleteTask");
				break;
		}
	}
	
	public function getTaskGroups($project_id)
	{
		$response = new stdClass();
		$response->ok = false;
		
		$data                    = array();
		$data['project_id']      = strtolower($project_id);
	
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		
		if($input->isValid)
		{
		
			$session  = YSSSession::sharedSession();
			$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
			$options = array('startkey'     => array("project/".$project_id, null),
			                 'endkey'       => array("project/".$project_id, new stdClass()),
			                 'include_docs' => true);
		
			$result   = $database->view("project/taskGroup-report", $options, false);
			$groups = array();
			
			foreach($result as $row)
				$groups[] = $row;
				
			$response->ok     = true;
			$response->groups = $groups;
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function getTasksInGroup($project_id, $group_id)
	{
		
		$response     = new stdClass();
		$response->ok = false;
	
		$data                    = array();
		$data['project_id']      = strtolower($project_id);
		$data['group_id']        = strtolower($group_id);
	
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyBaseGroupValidators($input);
		
		if($input->isValid)
		{
			$session  = YSSSession::sharedSession();
			$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
			
			
			$options = array('include_docs' => true,
			                 'startkey'     => array("project/".$project_id."/group/task/".$group_id, null),
			                 'endkey'       => array("project/".$project_id."/group/task/".$group_id, 3));
		
		
			$result = $database->formatList("project/taskGroup-tasks-aggregate", "taskGroup-tasks", $options, false);
			
			
			$response->ok    = true;
			$response->group = "project/$project_id/group/task/$group_id";
			$response->tasks = $result;
			
			print_r($response);exit;
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function addTaskToGroup($project_id, $group_id, $task_id)
	{
		$response = new stdClass();
		$response->ok = false;
	
		$data                    = array();//json_decode(file_get_contents('php://input'), true);
		$data['project_id']      = strtolower($project_id);
		$data['group_id']        = strtolower($group_id);
	    $data['task_id']         = strtolower($task_id);
	
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyBaseGroupValidators($input);
		$input->addValidator(new AMPatternValidator('task_id', AMValidator::kRequired, '/^project\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-f0-9]{32}$/', "Invalid task id."));
		
		if($input->isValid)
		{
			// does the project exist?
			$project = YSSProject::projectWithId('project/'.$project_id);
		
			if($project)
			{
				// does the task group exist?
				$group = YSSTaskGroup::groupWithId($project->_id.'/group/task/'.$group_id);
			
				if($group)
				{
					$task = YSSTask::taskWithId($task_id);
				
					if($task)
					{
						if(!in_array($task_id, $group->tasks))
						{
							$group->addTask($task);
						
							if($group->save())
							{
								//$task->group  = $group->_id;
							
								if($task->save())
								{
									$response->ok = true;
									$response->id = $group->_id;
								}
								else
								{
									$input->addValidator(new AMErrorValidator('error', 'Unable to save task updates to task') );
									$this->hydrateErrors($input, $response);
								}
							}
							else
							{
								$input->addValidator(new AMErrorValidator('error', 'Unable to save task group') );
								$this->hydrateErrors($input, $response);
							}
						}
						else
						{
							$input->addValidator(new AMErrorValidator('task', 'Task already exists in group') );
							$this->hydrateErrors($input, $response);
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('task', 'Task does not exist') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('taskGroup', 'Task Group does not exist') );
					$this->hydrateErrors($input, $response);
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('project', 'Project does not exist') );
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function createGroup($project_id)
	{
		$response = new stdClass();
		$response->ok = false;
		
		$data                    = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['project_id']      = strtolower($project_id);
		
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyPutValidators($input);
		if($input->isValid)
		{
			// does the project exist?
			$project = YSSProject::projectWithId('project/'.$input->project_id);

			if($project)
			{
				$task = YSSTask::taskWithId($input->task_id);
				
				if($task)
				{
					
					$group        = YSSTaskGroup::groupWithProject($project);
					$group->label = $input->label;
					$group->addTask($task);
					
					if($group->save())
					{
						//$task->group  = $group->_id;
						
						if($task->save())
						{
							$response->ok = true;
							$response->id = $group->_id;
						}
						else
						{
							$input->addValidator(new AMErrorValidator('error', 'Unable to save task updates to task') );
							$this->hydrateErrors($input, $response);
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Unable to save task group') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('task', 'Task does not exist') );
					$this->hydrateErrors($input, $response);
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('error', 'Project does not exist') );
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('project', 'Invalid project id') );
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function deleteGroup($project_id, $group_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$data                    = array();//json_decode(file_get_contents('php://input'), true);
		$data['project_id']      = strtolower($project_id);
		$data['group_id']        = strtolower($group_id);
	
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyBaseGroupValidators($input);
		
		if($input->isValid)
		{
			// does the project exist?
			$project = YSSProject::projectWithId('project/'.$input->project_id);
			
			if($project)
			{
				// does the task group exist?
				$group = YSSTaskGroup::groupWithId($project->_id.'/group/task/'.$group_id);
				
				if($group)
				{
					$payload = null;
					
					if(count($group->tasks) > 0)
					{
						$session   = YSSSession::sharedSession();
						$database  = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
						
						$keys       = new stdClass();
						$keys->keys = $group->tasks;
						
						$keys = $database->request("POST", 
						                               $session->currentUser->domain.'/_all_docs', 
						                               array("include_docs" => "true"), 
						                               $keys);
						
						$payload       = new stdClass();
						$payload->docs = array();
						
						foreach($keys['rows'] as $row)
						{
							$document = $row['doc'];
							if(!isset($document['error']))
							{
								$document['group'] = null;
								$payload->docs[]   = $document;
							}
						}
					}
					
					$status = $database->delete($group->_id, $group->_rev);
					
					if($status['ok'])
					{
						if($payload)
						{
							// may want to add some checking here to ensure 
							// it's all good after the command is issued
							$bulk_status = $database->bulk_update($payload);
						}
						
						$response->ok = true;
					}
					else
					{
						$input->addValidator(new AMErrorValidator('taskGroup', 'Unable to delete Task Group') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('taskGroup', 'Task Group does not exist') );
					$this->hydrateErrors($input, $response);
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('project', 'Invalid project id') );
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function deleteTask($project_id, $group_id, $task_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$data                    = array();
		$data['project_id']      = strtolower($project_id);
		$data['group_id']        = strtolower($group_id);
	    $data['task_id']         = strtolower($task_id);
	
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyBaseGroupValidators($input);
		$input->addValidator(new AMPatternValidator('task_id', AMValidator::kRequired, '/^project\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-f0-9]{32}$/', "Invalid task id."));
		
		if($input->isValid)
		{
			// does the project exist?
			$project = YSSProject::projectWithId('project/'.$input->project_id);
			
			if($project)
			{
				// does the task group exist?
				$group = YSSTaskGroup::groupWithId($project->_id.'/group/task/'.$group_id);
				
				if($group)
				{
					$tasks = array();
					$task  = null;
					
					foreach($group->tasks as $id)
					{
						if($id == $task_id)
							$task = YSSTask::taskWithId($id);
						else
							$tasks[] = $id;
					}
					
					if($task != null)
					{
						$group->tasks = $tasks;
						
						if($group->save())
						{
							$task->group = null;
							
							if($task->save())
							{
								$response->ok = true;
							}
							else
							{
								$input->addValidator(new AMErrorValidator('task', 'Unable to save task') );
								$this->hydrateErrors($input, $response);
							}
						}
						else
						{
							$input->addValidator(new AMErrorValidator('taskGroup', 'Unable to save Task Group') );
							$this->hydrateErrors($input, $response);
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('task', 'Task does not exist in group') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('taskGroup', 'Task Group does not exist') );
					$this->hydrateErrors($input, $response);
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('project', 'Invalid project id') );
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	private function applyBaseGroupValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		$input->addValidator(new AMPatternValidator('group_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid group id."));
		
		if(isset($input->transform_label))
			$input->addValidator(new AMMatchValidator('group_id', 'transform_label', AMValidator::kRequired, "group label and group id do not match."));
	}
	
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		$input->addValidator(new AMPatternValidator('task_id', AMValidator::kRequired, '/^project\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-z\d-]{2,}\/[a-fA-F0-9]{32}$/', "Invalid task id, expecting full id."));
		$input->addValidator(new AMPatternValidator('label', AMValidator::kRequired, '/^[\w\d- \']{2,}$/', "Invalid label. Expecting minimum 2 characters letters, numbers, - or '."));
	}
	
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceGroups());
$manager->start();
?>