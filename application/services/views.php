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
				$this->addEndpoint("GET",    "/project/{project_id}/views",               "generateReport");
				break;
			
			case "PUT":
				$this->addEndpoint("PUT",    "/project/{project_id}/view/{view_id}",      "updateView");
				break;
			
			case "POST":
				$this->addEndpoint("POST",    "/project/{project_id}/view/{view_id}",     "updateView");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/project/{project_id}/view/{view_id}",      "deleteView");
				break;
		}
	}
	
	public function getProject($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	public function updateView($project_id, $view_id)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$data               = $_POST;//json_decode(file_get_contents('php://input'), true);
		$data['view_id']    = strtolower($view_id);
		$data['project_id'] = strtolower($project_id);
		
		$context = array(AMForm::kDataKey=>$data, AMForm::kFilesKey=>$_FILES);
		$input   = AMForm::formWithContext($context);
		
		$input->addValidator(new AMInputValidator('label', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMPatternValidator('view_id', AMValidator::kRequired, '/^[a-z][a-z0-9-_]+$/', "Invalid view id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMPatternValidator('project_id', AMValidator::kRequired, '/^[a-z][a-z0-9-_]+$/', "Invalid project id. Expecting minimum 2 lowercase characters."));
		$input->addValidator(new AMFilesizeValidator('attachment', AMValidator::kRequired, 1024000, "Invalid attachment size. Expecting maximum 1 megabyte."));
		
		if($data['_rev'])
		{
			$input->addValidator(new AMPatternValidator('_rev', AMValidator::kRequired, '/^[\d]+-[a-z0-9]{32}+$/', "Invalid _rev."));
		}
		echo $input->attachment->tmp_name,"\n";
		echo $input->attachment->name, "\n";
		echo $input->attachment->size, "\n";
		
		if($input->isValid)
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
					
					//$state->addAttachment();
					//$view->addState($state);
					
					$response->ok = true;
				}
			}
			else
			{
				$response->errors = array();
				$error = new stdClass();
				$error->key = 'project_id';
				$error->message = "not_found";
				$response->errors[] = $error;
			}
		}
		else
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
		
		echo json_encode($response);
	}
	
	public function deleteProject($id)
	{
		/*
			TODO Finish delete project logic.  Do we just mark as unused? Do we cascade down all of the associated tasks/comments/attachments/views?
			just need to decide the best course of action.  Probably will be to delete everything, since it takes up resources to keep it around.
		*/
	}
	
	public function generateReport()
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		echo $database->formatList("project/project-aggregate", "project-report", null, true);
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