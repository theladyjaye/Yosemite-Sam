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


class YSSServiceProjects extends AMServiceContract
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/projects",                                                       "generateReport");
				$this->addEndpoint("GET",    "/projects/{id}",                                                  "getProject");
				break;
			
			case "PUT":
				$this->addEndpoint("PUT",    "/projects/{id}",                                                  "updateProject");
				break;
			
			case "POST":
				$this->addEndpoint("POST",   "/projects/{id}",                                                  "updateProject");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE", "/projects/{id}",                                                  "deleteProject");
				break;
		}
	}
	
	public function getProject($id)
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, "yss/blitz");//$session->currentUser->domain);
		echo $database->document($id, true);
	}
	
	public function updateProject($id)
	{
		$response = new stdClass();
		$response->ok = false;
		
		$data       = json_decode(file_get_contents('php://input'), true);
		$data['id'] = strtolower($id);
		
		$context    = array(AMForm::kDataKey=>$data);
		$input      = AMForm::formWithContext($context);
	
		$input->addValidator(new AMInputValidator('name', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		$input->addValidator(new AMPatternValidator('id', AMValidator::kRequired, '/^[a-z][a-z0-9-_]+$/', "Invalid project id. Expecting minimum 2 characters."));
		$input->addValidator(new AMInputValidator('description', AMValidator::kRequired, 2, null, "Invalid description.  Expecting minimum 2 characters."));
		
		if($data['_rev'])
		{
			$input->addValidator(new AMPatternValidator('_rev', AMValidator::kRequired, '/^[\d]+-[a-z0-9]{32}+$/', "Invalid _rev."));
		}
		
		if($input->isValid)
		{
			$project = new YSSProject();
			$project->name = $input->name;
			$project->description = $input->description;
			$project->_id = $id;
			
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
		
	}
	
	public function generateReport()
	{
		echo "Howdy!";
	}
	
	public function verifyAuthorization()
	{
		$result  = false;
		$session = YSSSession::sharedSession();
		
		if($session->currentUser)
			$result = true;
		
		return true;//$result;
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceProjects());
$manager->start();
?>