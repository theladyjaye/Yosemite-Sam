<?php
class ProjectAddController extends YSSController
{
	protected $requiresAuthorization  = true;
	protected $requiresPermission     = true;
	
	protected function initialize()
	{ 
	
	}
	
	protected function verifyPermission()
	{
		return ($this->session->currentUser->level & YSSuserLevel::kCreateProjects) > 0;
	}
	
	protected function verifyPermissionFailed() 
	{
		header("Location:/dashboard");
	}
	
}
?>