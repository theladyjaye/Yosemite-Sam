<?php
class ViewsController extends YSSController
{
	protected $requiresAuthorization  = false;
	protected $requiresPermission     = false;
	
	public    $data					  = array();
	
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
		header("Location:/dashboard");
	}
	
	private function get_data()
	{
		if(isset($_REQUEST['project']))
		{
			$project = $_REQUEST['project'];
		}
		else
		{
			$this->verifyPermissionFailed();
		}
		
		return json_decode(file_get_contents("http://yss.com/api/project/$project/views"));
	}
	
}
?>