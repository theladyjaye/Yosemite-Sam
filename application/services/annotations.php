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

require YSSApplication::basePath().'/application/system/YSSService.php';
require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSState.php';


class YSSServiceAnnotations extends YSSService
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/project/{project_id}/{view_id}/{state_id}/tasks",               "getTasks");
				$this->addEndpoint("GET",    "/api/project/{project_id}/{view_id}/{state_id}/notes",               "getNotes");
				$this->addEndpoint("GET",    "/api/project/{project_id}/{view_id}/{state_id}/annotations",         "getAnnotations");
				break;
				
			case "POST":
				$this->addEndpoint("POST",    "/api/project/{project_id}/{view_id}/{state_id}/annotations",     "createAnnotation");
				$this->addEndpoint("POST",    "/api/project/{project_id}/{view_id}/{state_id}/{annotation_id}", "updateAnnotation");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/api/project/{project_id}/{view_id}/{state_id}/{annotation_id}",  "deleteAnnotation");
				break;
		}
	}
	
	public function getTasks($project_id, $view_id, $state_id)
	{
		$project_id = YSSUtils::transform_to_id($project_id);
		$view_id    = YSSUtils::transform_to_id($view_id);
		$state_id   = YSSUtils::transform_to_id($state_id);
		
		$session  = YSSSession::sharedSession();
		$options = array('key'            => 'project/'.$project_id.'/'.$view_id.'/'.$state_id, 
		                 'include_docs'   => true);
		
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		// use the view not the list of you want JSON instead of HTML
		echo $database->formatList("project/annotation-renderer", "task-report", $options, true);
	}
	
	public function getNotes($project_id, $view_id, $state_id)
	{
		$project_id = YSSUtils::transform_to_id($project_id);
		$view_id    = YSSUtils::transform_to_id($view_id);
		$state_id   = YSSUtils::transform_to_id($state_id);
		
		$session  = YSSSession::sharedSession();
		$options = array('key'            => 'project/'.$project_id.'/'.$view_id.'/'.$state_id, 
		                 'include_docs'   => true);
		
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		// use the view not the list of you want JSON instead of HTML
		echo $database->formatList("project/annotation-renderer", "note-report", $options, true);
	}
	
	public function getAnnotations($project_id, $view_id, $state_id)
	{
		$project_id = YSSUtils::transform_to_id($project_id);
		$view_id    = YSSUtils::transform_to_id($view_id);
		$state_id   = YSSUtils::transform_to_id($state_id);
		
		$session  = YSSSession::sharedSession();
		$options  = array('key'            => 'project/'.$project_id.'/'.$view_id.'/'.$state_id, 
		                  'include_docs'   => true);
		
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		// use the view not the list of you want JSON instead of HTML
		echo $database->formatList("project/annotation-renderer", "annotations-report", $options, true);
	}
	
	private function applyBaseAnnotationValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('view_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('state_id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid state id. Expecting minimum 2 lowercase characters."));
	}
	
	private function applyPostValidators(&$input)
	{
		$input->addValidator(new AMPatternValidator('annotation_id', AMValidator::kRequired, '/^[a-z0-9]{32}$/', "Invalid annotation id."));
		$input->addValidator(new AMInputValidator('label', AMValidator::kOptional, 2, null, "Invalid label.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
	}
	
	private function applyPutValidators(&$input)
	{
		$input->addValidator(new AMInputValidator('label', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMPatternValidator('x', AMValidator::kRequired, '/^[\d]+$/', "Invalid x coordinate"));
		$input->addValidator(new AMPatternValidator('y', AMValidator::kRequired, '/^[\d]+$/', "Invalid x coordinate"));
	}
	
	public function createAnnotation($project_id, $view_id, $state_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$project_id = YSSUtils::transform_to_id($project_id);
		$view_id    = YSSUtils::transform_to_id($view_id);
		$state_id   = YSSUtils::transform_to_id($state_id);
		
		$data                  = $_POST;
		$data['view_id']       = $view_id;
		$data['project_id']    = $project_id;
		$data['state_id']      = $state_id;
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$this->applyBaseAnnotationValidators($input);
		
		if($input->isValid)
		{
			$this->applyPutValidators($input);
		}
	}
	
	
	public function updateAnnotation($project_id, $view_id, $state_id, $annotation_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$project_id = YSSUtils::transform_to_id($project_id);
		$view_id    = YSSUtils::transform_to_id($view_id);
		$state_id   = YSSUtils::transform_to_id($state_id);
		
		$isNew      = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) && $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] == 'PUT' ? true : false;
		
		$data                  = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['view_id']       = $view_id;
		$data['project_id']    = $project_id;
		$data['state_id']      = $state_id;
		$data['annotation_id'] = $annotation_id;
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$this->applyBaseAnnotationValidators($input);
		
		
		if($input->isValid)
		{
			$this->applyPostValidators();
			
			if($input->isValid)
			{
				
			}
		}
		
		echo json_encode($response);
	}
	
	public function deleteAnnotation()
	{
		/*
			TODO Finish delete project logic.  Do we just mark as unused? Do we cascade down all of the associated tasks/comments/attachments/views?
			just need to decide the best course of action.  Probably will be to delete everything, since it takes up resources to keep it around.
		*/
		
		$response     = new stdClass();
		$response->ok = false;
		
		$response->ok = true;
		echo json_encode($response);
	}
	
	public function generateReport()
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->formatList("project/view-aggregate", "view-report", null, true);
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceAnnotations());
$manager->start();
?>