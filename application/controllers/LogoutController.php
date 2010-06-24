<?php
class LogoutController extends YSSController
{
	protected function initialize() 
	{ 
		$this->session->destroy();
		header("Location: /");
	}
}
?>