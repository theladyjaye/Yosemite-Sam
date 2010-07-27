<?php
class ProjectsController extends YSSController
{
	protected $requiresAuthorization  = false;	
	public    $data					  = "";
	
	protected function initialize()
	{ 
		$this->data = $this->get_data();
	}
	
	protected function verifyPermission()
	{
		return ($this->session->currentUser->level & YSSuserLevel::kCreateProjects) > 0;
	}
	
	protected function verifyPermissionFailed() 
	{
		header("Location:/");
	}

	private function get_data()
	{
		return file_get_contents("http://yss.com/api/projects");
	}
}
?>