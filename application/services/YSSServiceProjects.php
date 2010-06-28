<?php
class YSSServiceProjects extends AMServiceContract
{
	public function registerServiceEndpoints()
	{
		$this->addEndpoint("PUT",  "/projects",                                                        "addProject");
		$this->addEndpoint("get",  "/projects/services/users/",                                        "get_Users");
		$this->addEndpoint("get",  "/projects/services/users/?item0=nano&item1={item1}&item2={item2}", "get_Users_QueryString");
		$this->addEndpoint("get",  "/projects/services/?name=lucy",                                    "get_Users_Lucy");
		$this->addEndpoint("get",  "/projects/services/users/?name=lucy",                              "get_Users_Lucy");
		$this->addEndpoint("get",  "/projects/services/users/?name=lucy&type={type}",                  "get_Users_Lucy_Type");
		$this->addEndpoint("get",  "/projects/services/users/{name}",                                  "get_Bookmarks");
		$this->addEndpoint("GET",  "/projects/services/users/{name}/details/{bookmark}/",              "get_BookmarkDetails");
		$this->addEndpoint("gEt",  "/projects/services/users/{name}/{bookmark}",                       "get_BookmarkDetails2");
		$this->addEndpoint("GET",  "/projects/services/{name}",                                        "get_Details");
		$this->addEndpoint("GET",  "/projects/services/foo/{name}/bar/baz/{data}",                     "get_Fancy");
		
		//$this->addEndpoint("post", "foo/{name}/bar/baz/{data}",                     "post_Fancy");
		//$this->addEndpoint("Put",  "users/{name}",                                  "put_Basic");
	}
	
	public function addProject()
	{
		
	}
	public function get_Bookmarks($name)
	{
		echo "getBookmarks: ".$name;
	}
	
	public function get_BookmarkDetails($name, $bookmark)
	{
		echo "getBookmarkDetails: ".$name." | ".$bookmark;
	}
	
	public function get_BookmarkDetails2($name, $bookmark)
	{
		echo "getBookmarkDetails2: ".$name." | ".$bookmark;
	}
	
	public function get_Details($name)
	{
		if(empty($name))
		{
			AMServiceManager::not_found();
		}
		
		echo "get_Details: ".$name;
	}
	
	public function get_Fancy($name, $data)
	{
		echo "get_Fancy: ".$name." | ".$data;
	}
	
	public function get_Users()
	{
		echo "get_Users";
	}
	
	public function get_Users_QueryString($item1, $item2)
	{
		echo "get_Users_QueryString:", "item1: ", $item1, "<br>", "item2: ", $item2;
	}
	
	public function get_Users_Lucy()
	{
		echo "get_Users_Lucy";
	}
	
	public function get_Users_Lucy_Type($type)
	{
		echo "get_Users_Lucy_Type: ",$type;
	}
	
	public function post_Fancy($name, $data)
	{
		echo "post_Fancy: ".$name." | ".$data;
		echo "<pre>".print_r($_POST, true)."</pre>";
	}
	
	public function put_Basic($username)
	{
		echo "put_Basic: ".$username;
	}
}
?>