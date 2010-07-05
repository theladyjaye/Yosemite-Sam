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
				$this->addEndpoint("GET",    "/projects",                                                       "generateReport");
				//$this->addEndpoint("GET",    "/projects?delete=1",                                              "generateReport");
				//$this->addEndpoint("GET",    "/projects?create=1",                                              "generateReport");
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
			$project->_id = strtolower($id);
			
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
			/*$database = YSSDatabase::connection(YSSDatabase::kCouchDB, "blitz");//$session->currentUser->domain);
			$database->delete_database();
		
			YSSDomain::create("blitz");
		
			$p1 = new YSSProject();
			$p1->name = "Lucy";
			$p1->description = "Project Lucy Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
			$p1->_id = "project.lucy";                                                                                                                                                                                                                                                 
			                                                                                                                                                                                                                                                                           
			$p2 = new YSSProject();                                                                                                                                                                                                                                                    
			$p2->name = "Ollie";                                                                                                                                                                                                                                                       
			$p2->description = "Project Ollie Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
			$p2->_id = "project.ollie";
			
			$p3 = new YSSProject();                                                                                                                                                                                                                                                    
			$p3->name = "YSS";                                                                                                                                                                                                                                                       
			$p3->description = "Project YSS Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
			$p3->_id = "project.yss";
		
			$t1 = new YSSTask();
			$t1->title = "Go for a walk";
			$t1->description = "lorem ipsum dolor sit amet";
			$t1->project = $p1->_id;
			$t1->complete = false;
		
			$t2 = new YSSTask();
			$t2->title = "Go for a ride";
			$t2->description = "lorem ipsum dolor sit amet";
			$t2->project = $p1->_id;
			$t2->complete = false;
		
			$t3 = new YSSTask();
			$t3->title = "Bark";
			$t3->description = "lorem ipsum dolor sit amet";
			$t3->project = $p1->_id;
			$t3->complete = true;
			
			$t4 = new YSSTask();
			$t4->title = "Stare at birds";
			$t4->description = "lorem ipsum dolor sit amet";
			$t4->project = $p2->_id;
			$t4->complete = false;
		
			$p1->save();
			$t1->save();
			$t2->save();
			$t3->save();
			
			$p2->save();
			$t4->save();
			
			$p3->save();
			*/
			
		
			$session  = YSSSession::sharedSession();
			$database = YSSDatabase::connection(YSSDatabase::kCouchDB, "blitz");//$session->currentUser->domain);
			echo $database->formatList("project/project-aggregate", "project-report", null, true);
		
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