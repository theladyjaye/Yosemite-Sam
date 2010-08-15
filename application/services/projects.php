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
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFileValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFilesizeValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';


require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';
require YSSApplication::basePath().'/application/system/YSSService.php';
require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSTask.php';


require YSSApplication::basePath().'/application/data/YSSAttachment.php';
if(AWS_S3_ENABLED) require 'Zend/Service/Amazon/S3.php';



class YSSServiceProjects extends YSSService
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
			
			case "POST":
				$this->addEndpoint("POST",   "/api/project/{id}",                                                  "updateProject");
				$this->addEndpoint("POST",   "/api/project/{id}/attachment/{attachment_id}",                       "updateAttachment");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/api/project/{id}",                                                  "deleteProject");
				$this->addEndpoint("DELETE", "/api/project/{id}/attachment/{attachment_id}",                       "deleteAttachment");
				break;
		}
	}
	
	public function getProject($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	private function applyBaseProjectValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
	}
	
	private function applyPostValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('label', AMValidator::kOptional, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		//$input->addValidator(new AMMatchValidator('id', 'transform_label', AMValidator::kRequired, "Invalid project id."));
		//$input->addValidator(new AMPatternValidator('_rev', AMValidator::kRequired, '/^[\d]+-[a-z0-9]{32}+$/', "Invalid _rev."));
	}
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('label', AMValidator::kRequired, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMMatchValidator('id', 'transform_label', AMValidator::kRequired, "Invalid project id."));
	}
	
	private function applyBaseProjectAttachmentValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('attachment_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
	}
	
	private function applyAttachmentPutValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('label', AMValidator::kRequired, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMFileValidator('attachment', AMValidator::kRequired, "Invalid attachment. None provided."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
		$input->addValidator(new AMMatchValidator('attachment_id', 'transform_label', AMValidator::kRequired, "Invalid attachment id."));
	}
	
	private function applyAttachmentPostValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('label', AMValidator::kOptional, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMFileValidator('attachment', AMValidator::kOptional, "Invalid attachment. None provided."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
	}
	
	private function createNewProject(&$input, &$response)
	{
		$project = new YSSProject();
		$project->label = $input->label;
		$project->description = $input->description;
		$project->_id = strtolower('project/'.$input->id);
		
		if($input->_rev)
			$project->_rev = $input->_rev;
		
		
		if($project->save())
		{
			$response->ok = true;
		}
		else
		{
			$input->addValidator(new AMErrorValidator('error', 'Unable to create new project') );
			$this->hydrateErrors($input, $response);
		}
	}
	
	private function updateExistingProject(&$project, &$input, &$response)
	{
		// so we have some rules here...
		// 1) updates are optional to parts, eg: everything is not required for an update
		// 2) if the label changes the _id has to change
		// 3) before you go changing the _id, first commit all other changes, then do the COPY / DELETE
		// 4) If performing a copy/delete first try to get a project with the same id, eg: project/{project}.  Do NOT start the full copy 
		//    unless this response is null
		
		//$project = YSSProject::projectWithId('project/'.$input->id);
		if($project)
		{
			// update all applicable fields up to the label. Label gets special treatment
			if($input->description)
				$project->description = $input->description;
			
			if($input->label)
			{
				
				$id = 'project/'.YSSUtils::transform_to_id($input->label);
				
				if($id != $project->_id)
				{
					// does aproject already exist with the new id?
					$targetProject = YSSProject::projectWithId($id);
					
					if($targetProject == null)
					{
						$project->label = $input->label;
						
						if($project->save())
						{
							// we are good to go to copy/delete it all
							$success  = true;
							$result   = null;
							$session  = YSSSession::sharedSession();
							$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
					
							$options  = array('key'          => $project->_id,
							                  'include_docs' => true);

							$result        = $database->view("project/project-forward", $options, false);
					
							$payload       = new stdClass();
							$payload->docs = array();
					
							foreach($result as $document)
							{
								$copy_id = $id.substr($document['_id'], strlen($project->_id));
								
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
									
									// include the new attachment ( we changed the path property above so we need to update)
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
								
									// project_id
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
								$response->ok = true;
								$response->id = $id;
							}
							else
							{
								$this->hydrateErrors($input, $response);
							}
							
						}
						else
						{
							$input->addValidator(new AMErrorValidator('error', 'Unable to update project') );
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
					if($project->save())
					{
						$response->ok = true;
						$response->id = $project->_id;
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Unable to update project') );
						$this->hydrateErrors($input, $response);
					}
				}
			}
			else
			{
				// save with no label transform success:
				if($project->save())
				{
					$response->ok = true;
					$response->id = $project->_id;
				}
				else
				{
					$input->addValidator(new AMErrorValidator('error', 'Unable to update project') );
					$this->hydrateErrors($input, $response);
				}
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('id', 'Invalid project key') );
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
	
	public function updateAttachment($project_id, $attachment_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session               = YSSSession::sharedSession();
		
		$data                  = $_POST;
		$data['project_id']    = YSSUtils::transform_to_id($project_id);
		$data['attachment_id'] = YSSUtils::transform_to_id($attachment_id);
		if(isset($data['label'])) $data['transform_label'] = YSSUtils::transform_to_id($data['label']);
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$this->applyBaseProjectAttachmentValidators($input);
		
		if($input->isValid)
		{
			$attachment = YSSAttachment::attachmentWithIdInDomain('project/'.$input->project_id.'/attachment/'.$input->attachment_id, $session->currentUser->domain);
			$isNew      = $attachment == null ? true : false;
			
			$isNew ? $this->applyAttachmentPutValidators($input) : $this->applyAttachmentPostValidators($input);
			
			if($input->isValid)
			{
				$isNew ? $this->createNewProjectAttachment($input, $response) : $this->updateExistingProjectAttachment($attachment, $input, $response);
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
	
	private function createNewProjectAttachment(&$input, &$response)
	{
		$session = YSSSession::sharedSession();
		$project = YSSProject::projectWithId($input->project_id);
		
		if($project)
		{
			$attachment        = YSSAttachment::attachmentWithLocalFileInDomain($input->attachment->tmp_name, $session->currentUser->domain);
			$attachment->label = $input->label;
			$attachment->_id   = $project->_id.'/attachment/'.$input->attachment_id;
			
			if($project->addAttachment($attachment))
			{
				$response->ok = true;
			}
			else
			{
				$input->addValidator(new AMErrorValidator('attachment', 'could not save attachment'));
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$input->addValidator(new AMErrorValidator('project_id', 'not found'));
			$this->hydrateErrors($input, $response);
		}
	}
	
	private function updateExistingProjectAttachment(&$attachment, &$input, &$response)
	{
		if($attachment)
		{
			$session           = YSSSession::sharedSession();
			$needsSave         = false;
			
			// user is giving us new data for a current attachment.  eg: overwrite old
			if($input->attachment->tmp_name)
			{
				$attachment->setFile($input->attachment->tmp_name);
				YSSAttachment::saveAttachmentInDomain($attachment, $session->currentUser->domain);
				
				$needsSave = true;
				
				$response->ok = true;
				$response->id = $attachment->_id;
			}
			
			if($input->label && $input->label != $attachment->_id)
			{
				//reset status
				$response->ok      = false;
				$response->id      = null;
				
				$database          = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
				$original_id       = $attachment->_id;
				
				$copy_id           = substr($attachment->_id, 0, strrpos($attachment->_id, '/'));
				$copy_id           = $copy_id.'/'.YSSUtils::transform_to_id($input->label);
				
				$attachment->label = $input->label;
				$attachment->path  = YSSAttachment::attachmentEndpointWithId($copy_id);
				
				if($attachment->save())
				{
					$needsSave = false;
					if($database->copy($attachment->_id, $copy_id))
					{
						$database->delete($original_id, $attachment->_rev);
						
						YSSAttachment::copyAttachmentWithIdToIdInDomain($original_id, $copy_id, $session->currentUser->domain);
						YSSAttachment::deleteAttachmentWithIdInDomain($original_id, $session->currentUser->domain);
						
						$response->ok = true;
						$response->id = $copy_id;
					}
					else
					{
						$input->addValidator(new AMErrorValidator('error', 'Could not copy attachment') );
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('error', 'Could not save attachment') );
					$this->hydrateErrors($input, $response);
				}
			}
			
			if($needsSave) $attachment->save();
		}
	}
	
	
	public function updateProject($id)
	{
		
		$response = new stdClass();
		$response->ok = false;
		
		$data                    = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['id']              = strtolower($id);
		if(isset($data['label'])) $data['transform_label'] = YSSUtils::transform_to_id($data['label']);
		
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
		
		$this->applyBaseProjectValidators($input);
		
		if($input->isValid)
		{
			$project = YSSProject::projectWithId('project/'.$input->id);
			$isNew   = $project == null ? true : false;
			
			// set our validators based on the type of command:
			// adding validators causes the forms needs validation 
			// flag to be reset, so we can check for validation again
			$isNew ? $this->applyPutValidators($input)          : $this->applyPostValidators($input);
			
			if($input->isValid)
			{
				$isNew ? $this->createNewProject($input, $response) : $this->updateExistingProject($project, $input, $response);
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
	
	public function deleteAttachment($project_id, $attachment_id)
	{
		$session      = YSSSession::sharedSession();
		$response     = new stdClass();
		$response->ok = false;
		
		$attachment = YSSAttachment::attachmentWithIdInDomain('project/'.$project_id.'/attachment/'.$attachment_id, $session->currentUser->domain);
		if($attachment)
		{
			$attachment->_deleted = true;
			if($attachment->save())
			{
				YSSAttachment::deleteAttachmentWithIdInDomain($attachment->_id, $session->currentUser->domain);
			}
		}
		
		$response->ok = true;
		echo json_encode($response);
	}
	
	public function deleteProject($id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		// deleting a project is a big deal, everything associated with it needs to go.
		
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		$options = array('key'          => 'project/'.$id,
		                 'include_docs' => true);
		
		$result        = $database->view("project/project-forward", $options, false);
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
		
		// may want to compact here.. depends on the load though, so compatcing is probably not the best idea ALL the time.
		$database->compact();
		
		$response->ok = true;
		echo json_encode($response);
	}
	
	public function generateReport()
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->formatList("project/project-aggregate-render", "project-report", null, true);
		//echo $database->formatList("project/project-aggregate", "project-report", null, true);
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceProjects());
$manager->start();
?>