<?php
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/data/YSSUserVerification.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';

class AccountVerifyController extends YSSController
{
	protected function initialize() 
	{ 
		if(empty($_GET))
		{
			$this->redirect();
		}
		else
		{
			$this->processForm();
		}
	}
	
	private function redirect()
	{
		header("Location: /");
	}
	
	private function processForm()
	{
		$context = array(AMForm::kDataKey=>$_GET);
		$input   = AMForm::formWithContext($context);
	
		$input->addValidator(new AMPatternValidator('domain', AMValidator::kRequired, '/^[a-zA-Z0-9-]+$/', "Invalid domain."));
		$input->addValidator(new AMPatternValidator('token', AMValidator::kRequired, '/^[a-zA-Z0-9]{32}$/', "Invalid token."));
		
		if($input->isValid)
		{
			YSSUserVerification::verify($input->token, $input->domain);
		}
		else
		{
			$this->redirect();
		}
	}
}
?>