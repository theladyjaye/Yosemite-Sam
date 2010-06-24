<?php
class DashboardController extends YSSController
{
	protected $requiresAuthorization  = true;
	
	protected function initialize() 
	{ 
		print_r($this->session->currentUser);
	}
}
?>