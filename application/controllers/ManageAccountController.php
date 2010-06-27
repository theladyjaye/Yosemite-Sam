<?php
require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';

require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/templates/FormManageAccount.php';

class ManageAccountController extends YSSController
{
	protected $requiresAuthorization  = true;
	private $input;
	private $user;
	
	protected function initialize()
	{
		$this->user = YSSUser::userWithId($this->session->currentUser->id);
		
		if($this->isPostBack)
			$this->processForm();
	}
	
	private function processForm()
	{
		$changed = false;
		
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
		
		// now, did the user even change anything?
		if($input->username != $this->user->username    ||
			$input->firstname != $this->user->firstname ||
			$input->lastname != $this->user->lastname   ||
			$input->email != $this->user->email         ||
			$input->password)
			{
				$changed = true;
			}
		
		if($changed)
		{
			$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
			$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		
			if($input->username != $this->user->username)
				$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
		
			if($input->email != $this->user->email)
				$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
		
			if($input->password)
			{
				$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
				$input->addValidator(new AMMatchValidator('password', 'password_verify', AMValidator::kRequired, "Passwords do not match"));
			}
		
			if($input->isValid)
			{
				// everything looks good so far
				// but we need to do some additional checking/cleanup
				// before we can create the account
			
				$data =& $input->formData;
				$data['firstname'] = ucwords(strtolower($data['firstname']));
				$data['lastname']  = ucwords(strtolower($data['lastname']));
				$data['email']     = strtolower($data['email']);
				$data['username']  = strtolower($data['username']);
			
				// user specific info check are disabled at the beginning
				// we add them incrementally as needed based on what was submitted.
				$usernameExists = null;
				$user           = false;
				$dirty          = false;
			
				if($input->username != $this->user->username)
					$usernameExists = YSSUser::userWithUsernameInDomain($input->username, $this->session->currentUser->domain);
			
				if($input->email != $this->user->email)
					$user  = YSSUser::userWithEmail($input->email);
			
				if($usernameExists == null)
				{
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
						$this->user->username     = $input->username;
						$this->user->email        = $input->email;
						$this->user->firstname    = $input->firstname;
						$this->user->lastname     = $input->lastname;
					
						if($input->password)
						{
							$this->user->password     = YSSUser::passwordWithStringAndDomain($input->password, $this->session->currentUser->domain);
						}
					
						$this->user->save();
					}
				}
				else
				{
					$input->addValidator(new AMErrorValidator('username', "Invalid username.  This username is already taken"));
					$input->clearInvalidValues();
				}
			}
			
			$this->input = $input;
		}
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
		
		$data = array("value_firstname"  => $this->user->firstname,
		              "value_lastname"   => $this->user->lastname,
		              "value_email"      => $this->user->email,
		              "value_username"   => $this->user->username,
		              "status_firstname" => $errors ? $errors->firstname : null,
		              "status_lastname"  => $errors ? $errors->lastname  : null,
		              "status_email"     => $errors ? $errors->email     : null,
		              "status_username"  => $errors ? $errors->username  : null,
		              "status_password"  => $errors ? $errors->password  : null,
		              );
		
		$form = new FormManageAccount($data);
		echo $form;
	}
}
?>