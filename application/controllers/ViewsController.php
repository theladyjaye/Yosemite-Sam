<?php
class ViewsController extends YSSController
{
	protected $requiresAuthorization  = false;	
	public    $data					  = "";
	
	protected function initialize()
	{ 
		$this->data = $this->get_data();
	/*	
		if(empty($this->data))
		{
			
		}
	*/
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
		if(isset($_POST['project']))
		{
			$project = $_POST['project'];
		}
		else
		{
			$this->verifyPermissionFailed();
		}
		
		return file_get_contents("http://yss.com/api/project/$project/views");
	}	
}
?>