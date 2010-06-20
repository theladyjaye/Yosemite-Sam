<?php
abstract class YSSController extends YSSPage
{
	public $session;
	protected $requiresAuthorization  = false;
	
	protected function page_load()
	{
		/*$this->hasAuthorizedUser = NakedTruth::hasAuthorizedUser();
		
		if($this->requiresAuthorization && !$this->hasAuthorizedUser)
		{
			header('Location: /management/login');
		}
		
		$this->session = NakedTruth::session();
		*/
		$this->initialize();
	}
	
	protected abstract function initialize();
	
}
?>