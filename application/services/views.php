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
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';

require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSState.php';


class YSSServiceViews extends AMServiceContract
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/project/{project_id}/views",          "generateReport");
				break;
			
			case "PUT":
				$this->addEndpoint("PUT",    "/api/project/{project_id}/{view_id}",      "updateView");
				break;
			
			case "POST":
				$this->addEndpoint("POST",    "/api/project/{project_id}/{view_id}",     "updateView");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/api/project/{project_id}/{view_id}",      "deleteView");
				break;
		}
	}
	
	public function getView($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	public function generateReport($project_id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options = array('startkey' => array('project/'.$project_id), 
		                 'endkey'   => array('project/'.$project_id, new stdClass()));
		
		echo $database->formatList("project/view-aggregate-render", "view-report", $options, true);
	}
	
	private function applyViewValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('view_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id. Expecting minimum 2 lowercase characters."));
	}
	
	private function applyPostValidators(&$input)
	{
		$input->addValidator(new AMInputValidator('label', AMValidator::kOptional, 2, null, "Invalid label.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
	}
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMInputValidator('label', AMValidator::kRequired, 2, null, "Invalid label.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
	}
	
	private function createNewView(&$input, &$response)
	{
		$project = YSSProject::projectWithId($input->project_id);
		
		if($project)
		{
			$view              = new YSSView();
			$view->label       = $input->label;
			$view->description = $input->description;
			$view->_id         = $project->_id.'/'.$input->view_id;
			
			if($input->_rev)
				$view->_rev = $input->_rev;
		
			if($view->save())
			{
				$state              = new YSSState();
				$state->label       = YSSState::kDefault;
				$state->description = YSSState::kDefault;
				$state->_id         = $view->_id.'/'.YSSState::kDefault;
				
				$view->addState($state);
				
				$attachment = array('name'         => $input->attachment->name,
				                    'path'         => $input->attachment->tmp_name);
				
				if($state->addAttachment($attachment))
				{
					$response->ok = true;
				}
				else
				{
					$input->addValidator(new AMErrorValidator('state', 'error'));
					$this->hydrateErrors($input, $response);
					
					/*$response->errors = array();
					$error = new stdClass();
					$error->key = 'state';
					$error->message = 'error';
					$response->errors[] = $error;
					*/
				}
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('project_id', 'not found'));
			$this->hydrateErrors($input, $response);
			
			/*$response->errors = array();
			$error = new stdClass();
			$error->key = 'project_id';
			$error->message = "not_found";
			$response->errors[] = $error;
			*/
		}
	}
	
	private function updateExistingView(&$input, &$response)
	{
		// like projects, we have some rules here... 
		// 1) updates are optional to parts, eg: everything is not required for an update
		// 2) if the label changes the _id has to change
		// 3) before you go changing the _id, first commit all other changes, then do the COPY / DELETE
		// 4) If performing a copy/delete first try wih the view, eg: project/{project}/{view}.  Do NOT start the full copy 
		//    unless this commit succeeds
		
		$view = YSSView::viewWithId('project/'.$input->project_id.'/'.$input->view_id);
		if($view)
		{
			// update all applicable fields up to the label. Label gets special treatment
			if($input->description)
				$view->description = $input->description;
			
			if($input->label)
			{
				$new_id = 'project/'.$input->project_id.'/'.YSSUtils::transform_to_id($input->label);
				
				if($new_id != $view->_id)
				{
					$view->label = $input->label;
					if($view->save())
					{
						// does a view already exist with the new id?
						$targetView = YSSView::viewWithId($new_id);
					
						if($targetView != null)
						{
							$input->addValidator(new AMErrorValidator('label', 'Invalid label. View label already exists'));
							$this->hydrateErrors($input, $response);
						}
						else
						{
							// we are good to go to copy/delete it all
							$success  = true;
							$session  = YSSSession::sharedSession();
							$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
						
							$options  = array('key'          => $view->_id,
							                  'include_docs' => true);

							$result        = $database->view("project/view-forward", $options, false);
						
							$payload       = new stdClass();
							$payload->docs = array();
							
							foreach($result as $document)
							{
								$copy_id = $new_id.substr($document['_id'], strlen($view->_id));
								$result  = $database->copy($document['_id'], $copy_id);
								
								if(isset($result['error']))
								{
									$success = false;
									$input->addValidator(new AMErrorValidator('error', 'Copy operation failed, your original data is unchanged.'));
									$parts = explode('/', $copy_id);
									
									// project_id, view_id
									$this->deleteView($parts[1], $parts[2]);
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
								$response->ok = true;
							}
							else
							{
								$this->hydrateErrors($input, $response);
							}
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Unable to update view') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					if($view->save())
					{
						$response->ok = true;
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Unable to update view') );
						$this->hydrateErrors($input, $response);
					}
				}
				
			}
			else
			{
				if($view->save())
				{
					$response->ok = true;
				}
				else
				{
					$input->addValidator(new AMErrorValidator('error', 'Unable to update view') );
					$this->hydrateErrors($input, $response);
				}
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('id', 'Invalid view key') );
			$this->hydrateErrors($input, $response);
		}
	}
	
	public function updateView($project_id, $view_id)
	{
		$isNew = $_SERVER['REQUEST_METHOD'] == 'PUT' ? true : false;
		
		$response     = new stdClass();
		$response->ok = false;
		
		$data               = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['view_id']    = YSSUtils::transform_to_id($view_id);
		$data['project_id'] = YSSUtils::transform_to_id($project_id);
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$this->applyViewValidators($input);
		
		$isNew ? $this->applyPutValidators($input)       : $this->applyPostValidators($input);
		if($input->isValid)
		{
			$isNew ? $this->createNewView($input, $response) : $this->updateExistingView($input, $response);
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
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
	
	public function deleteView($project_id, $view_id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options  = array('key'          => 'project/'.$project_id.'/'.$view_id,
		                  'include_docs' => true);
		
		$result        = $database->view("project/view-forward", $options, false);
		$payload       = new stdClass();
		$payload->docs = array();
		
		foreach($result as $document)
		{
			$document['_deleted'] = true;
			$payload->docs[] = $document;
		}
		
		$database->bulk_update($payload);
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
$manager->bindContract(new YSSServiceViews());
$manager->start();
?>