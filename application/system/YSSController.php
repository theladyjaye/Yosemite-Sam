<?php
abstract class YSSController extends YSSPage
{
	protected $requiresAuthorization  = false;
	protected $requiresPermission     = false;
	
	protected abstract function initialize();
	protected function page_load()
	{
		if($this->requiresAuthorization)
			$this->verifyAuthorization();
		
		if($this->requiresPermission)
			$this->verifyPermission() ? null : $this->verifyPermissionFailed();

		$this->initialize();
	}

	protected function verifyAuthorization()
	{
		if($this->session->currentUser)
		{
			if($this->session->currentUser->domain != $_GET['domain'])
				$this->authorizationFailed();
		}
		else
		{
			$this->authorizationFailed();
		}
	}
	
	protected function authorizationFailed()
	{
		header('Location: /');
	}
	
	protected function verifyPermissionFailed() {}
	protected function verifyPermission()
	{
		return true;
	}
}
?>