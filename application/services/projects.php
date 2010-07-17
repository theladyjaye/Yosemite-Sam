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
	
	public function updateProject($id)
	{
		$response = new stdClass();
		$response->ok = false;
		
		$data                    = json_decode(file_get_contents('php://input'), true);
		$data['id']              = strtolower($id);
		$data['transform_label'] = YSSUtils::transform_to_id($data['label']);
		
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
	
		$input->addValidator(new AMPatternValidator('id', AMValidator::kRequired, '/^[a-z\d-]{2,}$/', "Invalid project id."));
		$input->addValidator(new AMPatternValidator('label', AMValidator::kRequired, '/^[\w\d- ]{2,}$/', "Invalid label. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kOptional, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMMatchValidator('id', 'transform_label', AMValidator::kRequired, "Invalid project id."));
		
		if($data['_rev'])
		{
			$input->addValidator(new AMPatternValidator('_rev', AMValidator::kRequired, '/^[\d]+-[a-z0-9]{32}+$/', "Invalid _rev."));
		}
		
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
$manager->bindContract(new YSSServiceProjects());
$manager->start();
?>