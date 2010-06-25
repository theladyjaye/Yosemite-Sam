<?php
require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';

require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';
require YSSApplication::basePath().'/application/mail/YSSAuthorizeAccountMessage.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUsersForDomain.php';




class ManageUsersController extends YSSController
{
	protected $requiresAuthorization  = true;
	protected $requiresPermission     = true;
	
	protected function initialize() 
	{ 
		if($this->isPostBack)
			$this->processForm();
	}
	
	private function processForm()
	{
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
	
		$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
		$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
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
			
			// do the domain and email values already exist?
			$company = YSSCompany::companyWithDomain($this->session->currentUser->domain);
			$user    = YSSUser::userWithEmail($input->email);
			
			$dirty   = false;
			
			if(!$company)
			{
				// someone tried something funny...
				$input->addValidator(new AMErrorValidator('domain', "Invalid domain.  Invalid domain"));
				header("/dashboard");
				exit;
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
				$user               = new YSSUser();
				$user->domain       = $this->session->currentUser->domain;
				$user->username     = $input->username;
				$user->email        = $input->email;
				$user->firstname    = $input->firstname;
				$user->lastname     = $input->lastname;
				$user->level        = $input->administrator ? YSSUserLevel::kAdministrator : YSSUserLevel::kUser;
				$user->active       = YSSUserActiveState::kInactive;
				$user->password     = YSSSecurity::generate_token();
				
				$user               = $user->save();
				
				$company->addUser($user);
				
				$message = new YSSAuthorizeAccountMessage($user->email, $this->session->currentUser->domain, $user->password);
				$message->send();
			}
		}
	}
	
	public function showUsers()
	{
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$users    = new YSSQueryUsersForDomain($database, $this->session->currentUser->domain);
		
		foreach($users as $user)
		{
			echo $user['firstname'];
		}
	}
	
	protected function verifyPermission()
	{
		return ($this->session->currentUser->level & YSSuserLevel::kCreateUsers) > 0;
	}
	
	protected function verifyPermissionFailed() 
	{
		header("Location:/dashboard");
	}
	
}
?>