<?php
require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';

require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/templates/FormSignup.php';

class SignupController extends YSSController
{
	private $input;
	
	protected function initialize() 
	{ 
		if($this->isPostBack)
			$this->processForm();
	}
	
	private function processForm()
	{
		/*
			The general idea here is that we can have duplicate company names
			The subdomains MUST be unique.  The subdomain + username will be hashed 
			so we can check for duplicate usernames within the same company.  
			In this way the same username can exist
			across the system, but it will be bound to unique company id, aka the subdomain.
		*/
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
	
		$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMInputValidator('company', AMValidator::kRequired, 2, null, "Invalid company name.  Expecting minimum 2 characters."));
		$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
		$input->addValidator(new AMPatternValidator('subdomain', AMValidator::kRequired, '/^[a-zA-Z0-9-]+$/', null, "Invalid subdomain.  Expecting minimum 1 character. Cannot contain spaces"));
		$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
		$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', null, "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
		$input->addValidator(new AMMatchValidator('password', 'password_verify', AMValidator::kRequired, "Passwords do not match"));
		
		if($input->isValid)
		{
			// everything looks good so far
			// but we need to do some additional checking/cleanup
			// before we can create the account
			
			$data =& $input->formData;
			$data['firstname'] = ucwords(strtolower($data['firstname']));
			$data['lastname']  = ucwords(strtolower($data['lastname']));
			$data['email']     = strtolower($data['email']);
			$data['subdomain'] = strtolower($data['subdomain']);
			$data['username']  = strtolower($data['username']);
			
			// do the subdomain and email values already exist?
			$company = YSSCompany::companyWithDomain($input->subdomain);
			$user    = YSSUser::userWithEmail($input->email);
			
			$dirty   = false;
			
			if($company)
			{
				$input->addValidator(new AMErrorValidator('subdomain', "Invalid subdomain.  This subdomain is currently in use."));
				$dirty = true;
			}
			
			if($user)
			{
				$input->addValidator(new AMErrorValidator('email', "Invalid email address.  This email address is currently in use."));
				$dirty = true;
			}
			
			if($dirty) 
			{
				$input->clearInvalidValues();
			}
			else
			{
				$company            = new YSSCompany();
				$company->name      = $input->company;
				$company->domain    = $input->subdomain;
				
				$user               = new YSSUser();
				$user->domain       = $input->subdomain;
				$user->username     = $input->username;
				$user->email        = $input->email;
				$user->firstname    = $input->firstname;
				$user->lastname     = $input->lastname;
				$user->password     = YSSUser::passwordWithStringAndDomain($user->password, $user->domain);
				
				$company            = $company->save();
				$user               = $user->save();
				
				$company->addUser($user);
			}
		}
		
		$this->input = $input;
	}
	
	public function displayForm()
	{
		$input =& $this->input;
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
		              "value_subdomain"  => $input  ? $input->subdomain  : null,
		              "status_firstname" => $errors ? $errors->firstname : null,
		              "status_lastname"  => $errors ? $errors->lastname  : null,
		              "status_email"     => $errors ? $errors->email     : null,
		              "status_company"   => $errors ? $errors->company   : null,
		              "status_username"  => $errors ? $errors->username  : null,
		              "status_password"  => $errors ? $errors->password  : null,
		              "status_subdomain" => $errors ? $errors->subdomain : null
		              );
		               
		$form = new FormSignup($data);
		echo $form;
	}
}
?>