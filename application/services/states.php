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
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSState.php';

require YSSApplication::basePath().'/application/data/YSSAttachment.php';

if(AWS_S3_ENABLED) require 'Zend/Service/Amazon/S3.php';


class YSSServiceStates extends YSSService
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/project/{project_id}/{view_id}/states",          "generateReport");
				break;
			
			case "POST":
				$this->addEndpoint("POST",    "/api/project/{project_id}/{view_id}/{state_id}",     "updateState");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/api/project/{project_id}/{view_id}/{state_id}",      "deleteState");
				break;
		}
	}
	
	public function getState($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	public function generateReport($project_id, $view_id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options = array('startkey' => array('project/'.$project_id.'/'.$view_id), 
		                 'endkey'   => array('project/'.$project_id.'/'.$view_id, new stdClass()));
		
//		echo $database->formatList("project/state-aggregate-render", "state-report", $options, true);
		echo $database->formatList("project/state-aggregate", "state-report", $options, true);
	}
	
	private function applyBaseStateValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('view_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('state_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
	}
	
	private function applyPostValidators(&$input)
	{
		$input->addValidator(new AMInputValidator('label', AMValidator::kOptional, 2, null, "Invalid label.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMFileValidator('attachment', AMValidator::kOptional, "Invalid attachment. None provided."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
	}
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMInputValidator('label', AMValidator::kRequired, 2, null, "Invalid label.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMFileValidator('attachment', AMValidator::kRequired, "Invalid attachment. None provided."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
		$input->addValidator(new AMMatchValidator('state_id', 'transform_label', AMValidator::kRequired, "Invalid state id."));
	}
	
	private function createNewState(&$input, &$response)
	{
		$view = YSSView::viewWithId('project/'.$input->project_id.'/'.$input->view_id);
		
		if($view)
		{
			$state              = new YSSState();
			$state->label       = $input->label;
			$state->description = $input->description;
			$state->_id         = $view->_id.'/'.YSSUtils::transform_to_id($input->label);

			$view->addState($state);
			
			$session = YSSSession::sharedSession();
			
			$attachment = YSSAttachment::attachmentWithLocalFileInDomain($input->attachment->tmp_name, $session->currentUser->domain);
			$attachment->_id = $state->_id.'/attachment/representation';
			$attachment->label = 'representation';
			
			if($state->addAttachment($attachment))
			{
				$response->ok = true;
			}
			else
			{
				$input->addValidator(new AMErrorValidator('state', 'error'));
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('view_id', 'not found'));
			$this->hydrateErrors($input, $response);
		}
	}
	
	private function updateExistingState(&$state, &$input, &$response)
	{
		// like projects, we have some rules here... 
		// 1) updates are optional to parts, eg: everything is not required for an update
		// 2) if the label changes the _id has to change
		// 3) before you go changing the _id, first commit all other changes, then do the COPY / DELETE
		// 4) If performing a copy/delete first try to get a view with the same id, eg: project/{project}/{view}.  Do NOT start the full copy 
		//    unless this response is null
		
		if($state)
		{
			// update all applicable fields up to the label. Label gets special treatment
			if($input->description)
				$state->description = $input->description;
				
			
			if($input->attachment->tmp_name)
			{
				$this->updateStateRepresenation($state, $input, $response);
			}
			
			if($input->label)
			{
				$new_id = 'project/'.$input->project_id.'/'.$input->view_id.'/'.YSSUtils::transform_to_id($input->label);
				
				if($new_id != $state->_id)
				{
					// does a view already exist with the new id?
					$targetState = YSSState::stateWithId($new_id);
					
					if($targetState == null)
					{
						$state->label = $input->label;
						
						if($state->save())
						{
							// we are good to go to copy/delete it all
							$success  = true;
							$session  = YSSSession::sharedSession();
							$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
					
							$options  = array('key'          => $state->_id,
							                  'include_docs' => true);

							$result        = $database->view("project/state-forward", $options, false);
					
							$payload       = new stdClass();
							$payload->docs = array();
						
							foreach($result as $document)
							{
								$copy_id = $new_id.substr($document['_id'], strlen($state->_id));
								
								if($document['type'] == 'attachment')
								{
									$attachment       = YSSAttachment::attachmentWithArray($document);
									$attachment->path = YSSAttachment::attachmentEndpointWithId($copy_id);
									$attachment->_id  = $copy_id;
									
									YSSAttachment::copyAttachmentWithIdToIdInDomain($document['_id'], $copy_id, $session->currentUser->domain);
									YSSAttachment::deleteAttachmentWithIdInDomain($document['_id'], $session->currentUser->domain);
									
									// TODO probbaly want to add some better error handling/rollback logic around here.
									
									$result = $database->copy($document['_id'], $copy_id);
									$attachment->_rev = $result['rev'];
									
									// include the new attachment in the batch update ( we changed the path property above so we need to update)
									$payload->docs[] = $attachment;
								}
								else
								{
									$result = $database->copy($document['_id'], $copy_id);
								}
							
								if(isset($result['error']))
								{
									$success = false;
									$input->addValidator(new AMErrorValidator('error', 'Copy operation failed, your original data is unchanged.'));
									$parts = explode('/', $copy_id);
								
									// project_id, view_id, state_id
									$this->deleteState($parts[1], $parts[2], $parts[3]);
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
								$response->id  = $new_id;
							}
							else
							{
								$this->hydrateErrors($input, $response);
							}
						}
						else
						{
							$input->addValidator(new AMErrorValidator('error', 'Unable to update state') );
							$this->hydrateErrors($input, $response);
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('label', 'Invalid label transform. Cannot transform label to id, id already exists'));
						$this->hydrateErrors($input, $response);
					}
				}
				// the label was submitted but it's the same as it was, we may still have other filed updates though, so we need to save.
				else
				{
					if($state->save())
					{
						$response->ok = true;
						$response->id = $state->_id;
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Unable to update state') );
						$this->hydrateErrors($input, $response);
					}
				}
				
			}
			else
			{
				// save with no label transform success:
				if($state->save())
				{
					$response->ok = true;
					$response->id = $state->_id;
				}
				else
				{
					$input->addValidator(new AMErrorValidator('error', 'Unable to update state') );
					$this->hydrateErrors($input, $response);
				}
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('id', 'Invalid state key') );
			$this->hydrateErrors($input, $response);
		}
	}
	
	private function updateStateRepresenation(&$state, &$input, &$response)
	{
		$session    = YSSSession::sharedSession();
		$attachment = YSSAttachment::attachmentWithIdInDomain($state->_id.'/attachment/representation', $session->currentUser->domain);
		
		if($attachment)
		{
			// user is giving us new data for a current attachment.  eg: overwrite old
			if($input->attachment->tmp_name)
			{
				$attachment->setFile($input->attachment->tmp_name);
				if($attachment->save())
				{
					YSSAttachment::saveAttachmentInDomain($attachment, $session->currentUser->domain);
				}
				else
				{
					$response->ok = false;
					$input->addValidator(new AMErrorValidator('attachment', 'Error saving attachment') );
					$this->hydrateErrors($input, $response);
					echo json_encode($response);
					exit;
				}
			}
		}
		else
		{
			// Something has gone very wrong.
			// There should never be a state without an attachment representation
			// if we have an orphan this is a problem, terminate any operation at this point
			// thought: if there is no attachment should we just make a new one?
			// my hesitation there is we end up with a situation were we have a ton of orphaned
			// attachments if the error goes un checked.
			
			$response->ok = false;
			$input->addValidator(new AMErrorValidator('attachment', 'No state representation found, please contact support.') );
			$this->hydrateErrors($input, $response);
			echo json_encode($response);
			exit;
		}
	}
	
	public function updateState($project_id, $view_id, $state_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$data                    = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['project_id']      = YSSUtils::transform_to_id($project_id);
		$data['view_id']         = YSSUtils::transform_to_id($view_id);
		$data['state_id']        = YSSUtils::transform_to_id($state_id);
		$data['transform_label'] = YSSUtils::transform_to_id($data['label']);
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$this->applyBaseStateValidators($input);
		
		if($input->isValid)
		{
			$state = YSSState::stateWithId('project/'.$input->project_id.'/'.$input->view_id.'/'.$input->state_id);
			$isNew = $state == null ? true : false;
			
			// set our validators based on the type of command:
			// adding validators causes the forms needs validation 
			// flag to be reset, so we can check for validation again
			$isNew ? $this->applyPutValidators($input) : $this->applyPostValidators($input);
			
			if($input->isValid)
			{
				$isNew ? $this->createNewState($input, $response) : $this->updateExistingState($state, $input, $response);
			}
			else
			{
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function deleteState($project_id, $view_id, $state_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options  = array('key'          => 'project/'.$project_id.'/'.$view_id.'/'.$state_id,
		                  'include_docs' => true);
		
		$result        = $database->view("project/state-forward", $options, false);
		$payload       = new stdClass();
		$payload->docs = array();
		
		foreach($result as $document)
		{
			if($document['type'] == 'attachment')
				YSSAttachment::deleteAttachmentWithIdInDomain($document['_id'], $session->currentUser->domain);
				
			$document['_deleted'] = true;
			$payload->docs[] = $document;
		}
		
		$database->bulk_update($payload);
		
		$response->ok = true;
		echo json_encode($response);
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceStates());
$manager->start();
?>