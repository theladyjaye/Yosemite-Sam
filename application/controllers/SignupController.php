<?php
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';

require YSSApplication::basePath().'/application/templates/FormSignup.php';

class SignupController extends YSSController
{
	protected function initialize() 
	{ 
		if ($this->isPostBack)
		{
			$this->processForm();
		}
	}
	
	private function processForm()
	{
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
		
		if($input->isValid)
		{
			
		}
		else
		{
			
		}
	}
	
	public function displayForm()
	{
		$data = array("value_firstname"  => '',
		              "value_lastname"   => '',
		              "value_email"      => '',
		              "value_company"    => '',
		              "value_username"   => '',
		              "value_password"   => '',
		              "status_firstname" => '',
		              "status_lastname"  => '',
		              "status_email"     => '',
		              "status_company"   => '',
		              "status_username"  => '',
		              "status_password"  => ''
		              );
		               
		$form = new FormSignup($data);
		echo $form;
	}
}
?>