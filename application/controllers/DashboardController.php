<?php
class DashboardController extends YSSController
{
	protected $requiresAuthorization  = true;
	
	protected function initialize() 
	{ 
	
	}
	
	public function showAddUser()
	{
		if($this->session->currentUser->level & YSSUserLevel::kCreateUsers)
		{
			echo "20";
		}
	}
	
}
?>