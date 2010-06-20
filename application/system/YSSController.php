<?php
abstract class YSSController extends YSSPage
{
	protected $requiresAuthorization  = false;
	
	protected function page_load()
	{
		/*$this->hasAuthorizedUser = NakedTruth::hasAuthorizedUser();
		
		if($this->requiresAuthorization && !$this->hasAuthorizedUser)
		{
			header('Location: /management/login');
		}
		
		*/
		
		$this->initialize();
	}
	
	protected abstract function initialize();
	
}
?>