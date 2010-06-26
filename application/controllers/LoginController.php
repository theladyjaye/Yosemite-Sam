<?php
require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';

require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';

class LoginController extends YSSController
{
	private $input;
	
	protected function initialize() 
	{ 
		if($this->isPostBack)
			$this->processForm();
	}
	
	private function processForm()
	{
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
	
		$isEmail = false;
		$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
		$input->addValidator(new AMPatternValidator('domain', AMValidator::kRequired, '/^[a-zA-Z0-9-]+$/', "Invalid domain.  Expecting minimum 1 character. Cannot contain spaces"));
		
		
		if(strpos($input->username, '@') !== false)
		{
			$input->addValidator(new AMEmailValidator('username', AMValidator::kRequired, 'Invalid email address'));
			$isEmail = true;
		}
		else
		{
			$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
		}
		
		if($input->isValid)
		{
			$dirty = false;
			// everything looks good so far
			// test for a valid account
			
			$data =& $input->formData;
			$data['username']  = strtolower($data['username']);
			$data['domain']    = strtolower($data['domain']);
			
			// do we have a valid domain?
			$company = YSSCompany::companyWithDomain($input->domain);
			
			if($company)
			{
				$user = null;
				if($isEmail)
				{
					$user  = YSSUser::userWithEmail($input->username);
				}
				else
				{
					$user = YSSUser::userWithUsernameInDomain($input->username, $input->domain);
				}
				
				if($user)
				{
					if($user->active == YSSUserActiveState::kActive)
					{
						$password = YSSUser::passwordWithStringAndDomain($input->password, $input->domain);
						//echo YSSUser::passwordWithStringAndDomain($input->password, $input->domain),'<br>';
						//echo $password,'<br>',$user->password,'<br>';
						if($password != $user->password)
						{
							$dirty = true;
						}
					}
					else
					{
						$input->addValidator(new AMErrorValidator('username', "Account not active."));
						$dirty = true;
					}
				}
				else
				{
					$dirty = true;
				}
			}
			else
			{
				$input->addValidator(new AMErrorValidator('domain', "Invalid domain."));
				$dirty = true;
			}
			
			
			if($dirty) 
			{
				$input->addValidator(new AMErrorValidator('username', "Invalid account."));
				$input->addValidator(new AMErrorValidator('password', "Invalid account."));
				$input->clearInvalidValues();
			}
			else
			{
				$currentUser            = new YSSCurrentUser();
				$currentUser->domain    = $input->domain;
				$currentUser->firstname = $user->firstname;
				$currentUser->lastname  = $user->lastname;
				$currentUser->username  = $user->username;
				$currentUser->email     = $user->email;
				$currentUser->level     = $user->level;
				
				$this->session->currentUser = $currentUser;
				
				$url = "http://".$currentUser->domain.'.'.YSSConfiguration::applicationDomain()."/dashboard";
				header("Location: $url");
			}
		}
		else
		{
			foreach($input->validators as $validator)
			{
				if(!$validator->isValid)
				{
					echo '<div>',$validator->key, ': ',$validator->message,'</div>';
				}
			}
		}
		
		$this->input = $input;
	}
}
?>