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
		$this->processForm();
	}
	
	private function processForm()
	{
		static $input;
		
		if(!$input && $this->isPostBack)
		{
			$context = array(AMForm::kDataKey=>$_POST);
			$input   = AMForm::formWithContext($context);
		
			$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
			$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
			$input->addValidator(new AMInputValidator('company', AMValidator::kRequired, 1, null, "Invalid company name.  Expecting minimum 1 character minimum."));
			$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
			$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
			$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', null, "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
			$input->addValidator(new AMMatchValidator('password', 'password_verify', AMValidator::kRequired, "Passwords do not match"));
			
			if($input->isValid)
			{
				// the input is good, now we need to check some other important things
			}
		}
		
		return $input;
	}
	
	public function displayForm()
	{
		$input = $this->processForm();
		$errors = null;
		
		if($input)
		{
			$errors = new stdClass();
			foreach($input->validators as $validator)
			{
				if(!$validator->isValid)
				{
					if(is_a($validator, "AMMatchValidator"))
					{
						$errors->password = 'error';
					}
					else
					{
						$errors->{$validator->key} = 'error';
					}
				}
			}
		}
		
		$data = array("value_firstname"  => $input  ? $input->firstname  : null,
		              "value_lastname"   => $input  ? $input->lastname   : null,
		              "value_email"      => $input  ? $input->email      : null,
		              "value_company"    => $input  ? $input->company    : null,
		              "value_username"   => $input  ? $input->username   : null,
		              "status_firstname" => $errors ? $errors->firstname : null,
		              "status_lastname"  => $errors ? $errors->lastname  : null,
		              "status_email"     => $errors ? $errors->email     : null,
		              "status_company"   => $errors ? $errors->company   : null,
		              "status_username"  => $errors ? $errors->username  : null,
		              "status_password"  => $errors ? $errors->password  : null
		              );
		               
		$form = new FormSignup($data);
		echo $form;
	}
}
?>