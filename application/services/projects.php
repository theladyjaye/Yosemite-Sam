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
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';

require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';
require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSTask.php';


class YSSServiceProjects extends AMServiceContract
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/projects",                                                      "generateReport");
				$this->addEndpoint("GET",    "/api/project/{id}",                                                  "getProject");
				break;
			
			case "PUT":
				$this->addEndpoint("PUT",    "/api/project/{id}",                                                  "updateProject");
				break;
			
			case "POST":
				$this->addEndpoint("POST",   "/api/project/{id}",                                                  "updateProject");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/api/project/{id}",                                                  "deleteProject");
				break;
		}
	}
	
	public function getProject($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	private function applyPostValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		$input->addValidator(new AMPatternValidator('label', AMValidator::kOptional, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		//$input->addValidator(new AMMatchValidator('id', 'transform_label', AMValidator::kRequired, "Invalid project id."));
		//$input->addValidator(new AMPatternValidator('_rev', AMValidator::kRequired, '/^[\d]+-[a-z0-9]{32}+$/', "Invalid _rev."));
	}
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		$input->addValidator(new AMPatternValidator('label', AMValidator::kRequired, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMMatchValidator('id', 'transform_label', AMValidator::kRequired, "Invalid project id."));
	}
	
	private function createNewProject(&$input, &$response)
	{
		if($input->isValid)
		{
			$project = new YSSProject();
			$project->label = $input->label;
			$project->description = $input->description;
			$project->_id = strtolower('project/'.$id);
			
			if($input->_rev)
				$project->_rev = $input->_rev;
			
			
			if($project->save())
			{
				$response->ok = true;
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
	}
	
	private function updateExistingProject(&$input, &$response)
	{
		// so we have some rules here... 
		// 1) updates are optional to parts, eg: everything is not required for an update
		// 2) if the label changes the _id has to change
		// 3) before you go changing the _id, first commit all other changes, then do the COPY / DELETE
		// 4) If performing a copy/delete first try wih the project, eg: project/{name}.  Do NOT start the full copy 
		//    unless this commit succeeds
		
		if($input->isValid)
		{
			$project = YSSProject::projectWithId('project/'.$input->id);
			
			if($project)
			{
				if($input->description)
					$project->description = $input->description;
				
				if($input->label)
				{
					$id = 'project/'.YSSUtils::transform_to_id($input->label);
					
					if($id != $project->_id)
					{
						$project->label = $input->label;
						$project->save();
						
						$targetProject = YSSProject::projectWithId($id);
						
						if($targetProject != null)
						{
							$input->addValidator(new AMErrorValidator('label', 'Invalid label. Project label already exists'));
							$this->hydrateErrors($input, $response);
						}
						else
						{
							// we are good to go to copy/delete it all
							$session  = YSSSession::sharedSession();
							$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
							
							$options = array('key'          => $project->_id,
							                 'include_docs' => true);

							$result        = $database->view("project/project-all", $options, false);
							
							$payload       = new stdClass();
							$payload->docs = array();
							
							$success = true;
							foreach($result as $document)
							{
								$copy_id = $id.substr($document['_id'], strlen($project->_id));
								
								$response = $database->copy($document['_id'], $copy_id);
								
								if(isset($response['error']))
								{
									$success = false;
									$input->addValidator(new AMErrorValidator('error', 'Copy operation failed, your original data is unchanged.'));
									$parts = explode('/', $copy_id);
									$this->deleteProject($parts[1]);
									break;
								}
								else
								{
									$document['_deleted'] = true;
									$payload->docs[] = $document;
								}
							}
							if($success)
							{
								$database->bulk_update($payload);
							
								// we may not want to compact here.
								// Depending on how we charge people, disk space may be important
								// since we just did a copy , with attachments, followed by a delete
								// if we don't compact, their DB size will be increased by the copy operation
								$database->compact();
							}
							else
							{
								$this->hydrateErrors($input, $response);
							}
						}
					}
				}
				else
				{
					if($project->save())
						$response->ok = true;
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('id', 'Invalid project key') );
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
	}
	
	private function hydrateErrors(&$input, &$response)
	{
		$response->errors = array();
		
		foreach($input->validators as $validator)
		{
			if(!$validator->isValid)
			{
				$error = new stdClass();
				$error->key = $validator->key;
				$error->message = $validator->message;
				$response->errors[] = $error;
			}
		}
	}
	
	public function updateProject($id)
	{
		$isNew = $_SERVER['REQUEST_METHOD'] == 'PUT' ? true : false;
		
		$response = new stdClass();
		$response->ok = false;
		
		$data                    = json_decode(file_get_contents('php://input'), true);
		$data['id']              = strtolower($id);
		$data['transform_label'] = YSSUtils::transform_to_id($data['label']);
		
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		// set our validators based on the type of command:
		$isNew ? $this->applyPutValidators($input)          : $this->applyPostValidators($input);
		$isNew ? $this->createNewProject($input, $response) : $this->updateExistingProject($input, $response);
		
		echo json_encode($response);
	}
	
	public function deleteProject($id)
	{
		// deleting a project is a big deal, everything associated with it needs to go.
		
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options = array('key'          => 'project/'.$id,
		                 'include_docs' => true);
		
		$result        = $database->view("project/project-all", $options, false);
		$payload       = new stdClass();
		$payload->docs = array();
		
		foreach($result as $document)
		{
			$document['_deleted'] = true;
			$payload->docs[] = $document;
		}
		
		$database->bulk_update($payload);
		
		// may want to compact here.. depends on the load though, so compatcing is probably not the best idea ALL the time.
		$database->compact();
	}
	
	public function generateReport()
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->formatList("project/project-aggregate-render", "project-report", null, true);
	}
	
	public function verifyAuthorization()
	{
		$result  = false;
		$session = YSSSession::sharedSession();
		
		if($session->currentUser)
			$result = true;
		
		return $result;
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceProjects());
$manager->start();
?>