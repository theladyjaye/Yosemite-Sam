<?php
require '../system/YSSEnvironmentServices.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';

class YSSServiceProjects extends AMServiceContract
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints()
	{
		$this->addEndpoint("PUT",  "/projects",                                                        "addProject");
		//$this->addEndpoint("get",  "/projects/services/users/",                                        "get_Users");
		/*$this->addEndpoint("get",  "/projects/services/users/?item0=nano&item1={item1}&item2={item2}", "get_Users_QueryString");
		$this->addEndpoint("get",  "/projects/services/?name=lucy",                                    "get_Users_Lucy");
		$this->addEndpoint("get",  "/projects/services/users/?name=lucy",                              "get_Users_Lucy");
		$this->addEndpoint("get",  "/projects/services/users/?name=lucy&type={type}",                  "get_Users_Lucy_Type");
		$this->addEndpoint("get",  "/projects/services/users/{name}",                                  "get_Bookmarks");
		$this->addEndpoint("GET",  "/projects/services/users/{name}/details/{bookmark}/",              "get_BookmarkDetails");
		$this->addEndpoint("gEt",  "/projects/services/users/{name}/{bookmark}",                       "get_BookmarkDetails2");
		$this->addEndpoint("GET",  "/projects/services/{name}",                                        "get_Details");
		$this->addEndpoint("GET",  "/projects/services/foo/{name}/bar/baz/{data}",                     "get_Fancy");
		*/
		
		//$this->addEndpoint("post", "foo/{name}/bar/baz/{data}",                     "post_Fancy");
		//$this->addEndpoint("Put",  "users/{name}",                                  "put_Basic");
	}
	
	public function addProject()
	{
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, "yss/blitz");//$session->currentUser->domain);
		$project  = json_decode(file_get_contents('php://input'));
		
		$database->put($project, "project_name");
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