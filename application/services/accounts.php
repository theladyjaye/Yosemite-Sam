<?php
require '../system/YSSEnvironmentServices.php';

require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';


require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';

require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';
require YSSApplication::basePath().'/application/data/YSSUserVerification.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUsersForDomain.php';



require YSSApplication::basePath().'/application/system/YSSService.php';

class YSSServiceDefault extends YSSService
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",     "/api/account/{domain}",        "getDomainInfo");
				$this->addEndpoint("GET",     "/api/account/{domain}/users",  "getUsersInDomain");
				break;
				
			case "POST":
				$this->addEndpoint("POST",    "/api/account/logout",                       "logout");
				$this->addEndpoint("POST",    "/api/account/login",                        "login");
				$this->addEndpoint("POST",    "/api/account/register",                     "registerAccount");
				$this->addEndpoint("POST",    "/api/account/{domain}/users/{username}",    "updateUserInDomain");
				$this->addEndpoint("POST",    "/api/account/{domain}/users",               "addUserInDomain");
				$this->addEndpoint("POST",    "/api/account/{domain}/users/reset/{email}", "resetPassword");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE",    "/api/account/{domain}/users/{username}",  "deleteUserInDomain");
				break;
		}
	}
	
	public function resetPassword($domain, $email)
	{
		$response     = new stdClass();
		$response->ok = ok;
		
		$data    = array('domain' => $domain, 'email' => $mail);
		$context = array(AMForm::kDataKey=>$data);
		$input   = AMForm::formWithContext($context);
		
		$input->addValidator(new AMEmailValidator('email', AMValidator::kOptional, 'Invalid email address'));
		$input->addValidator(new AMPatternValidator('domain', AMValidator::kRequired, '/^[a-zA-Z0-9-]+$/', "Invalid domain.  Expecting minimum 1 character. Cannot contain spaces"));
		
		if($input->isValid)
		{
			$user = YSSUser::userWithEmailInDomain($email, $domain);
			
			if($user)
			{
				require YSSApplication::basePath().'/application/mail/YSSMessagePasswordReset.php';
				
				$newPassword    = YSSSecurity::generate_password();
				$user->password = YSSUser::passwordWithStringAndDomain($newPassword, $domain);
				$user->save();
				
				$message           = new YSSMessagePasswordReset($user->email);
				$message->password = $newPassword;
				$message->domain   = $domain;
				$message->send();
			}
		}
		
		echo json_encode($response);
	}
	
	public function deleteUserInDomain($domain, $username)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		
		if($session->currentUser)
		{
			if($session->currentUser->domain == $domain)
			{
				if($session->currentUser->level == YSSUserLevel::kAdministrator)
				{
					$company = YSSCompany::companyWithDomain($session->currentUser->domain);
					if($company)
					{
						$user = YSSUser::userWithUsernameInDomain($username, $session->currentUser->domain);
						
						if($user)
						{
							if ($user->id != $session->currentUser->id)
							{
								$company->deleteUser($user);
								
								$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
								$options  = array('key'          => $user->username,
								                  'include_docs' => true);

								$result        = $database->view("project/task-user", $options, false);

								$payload       = new stdClass();
								$payload->docs = array();
								
								foreach($result as $document)
								{
									$document['assigned_to'] = null;
									$payload->docs[] = $document;
								}
								
								$database->bulk_update($payload);
								$response->ok = true;
							}
							else
							{
								$response->message = "invalid user";
							}
						}
						else
						{
							$response->message = "unknown user";
						}
					}
					else
					{
						$response->message = "unauthorized";
					}
				}
				else
				{
					$response->message = "unauthorized";
				}
			}
			else
			{
				$response->message = "unauthorized";
			}
		}
		else
		{
			$response->message = "unauthorized";
		}
		
		echo json_encode($response);
	}
	
	public function updateUserInDomain($domain, $username)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		
		if($session->currentUser)
		{
			if($session->currentUser->domain == $domain)
			{
				$user = YSSUser::userWithUsernameInDomain($username, $session->currentUser->domain);
				if($user)
				{
					$data    =& $_POST;
					$dirty   = false;
					
					$context = array(AMForm::kDataKey=>$data);
					$input   = AMForm::formWithContext($context);
					
					$input->addValidator(new AMPatternValidator('firstname', AMValidator::kOptional, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
					$input->addValidator(new AMPatternValidator('lastname', AMValidator::kOptional, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
					$input->addValidator(new AMPatternValidator('username', AMValidator::kOptional, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
					$input->addValidator(new AMEmailValidator('email', AMValidator::kOptional, 'Invalid email address'));
					
					if($input->password)
					{
						// only the owner can change the password
						if($session->currentUser->id == $user->id)
						{
							$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
							$input->addValidator(new AMMatchValidator('password', 'password_verify', AMValidator::kRequired, "Passwords do not match"));
						}
						else
						{
							$dirty = true;
							$this->message = "unable to change password";
						}
					}
					
					
					if($dirty == false)
					{
						if($input->isValid)
						{
							// everything looks good so far
							// but we need to do some additional checking/cleanup
							// before we can create the account
							
							// there is no reason for using the array access here vs $input-> access
							// just sarted typing it that way.
						
							if(isset($data['firstname']))
								$user->firstname = ucwords(strtolower($data['firstname']));
						
							if(isset($data['lastname']))
								$user->lastname  = ucwords(strtolower($data['lastname']));
						
							if(isset($data['email']))
								$user->email     = strtolower($data['email']);
						
							if(isset($data['username']))
								$user->username  = strtolower($data['username']);
								
							if(isset($data['password']))
								$user->password = YSSUser::passwordWithStringAndDomain($data['password'], $session->currentUser->domain);
							
							$user = $user->save();
						
							$response->ok   = true;
							$response->user = $user;
						}
						else
						{
							$this->hydrateErrors($response, $input);
						}
					}
				}
				else
				{
					$response->message = "unknown user";
				}
			}
			else
			{
				$response->message = "unauthorized";
			}
		}
		else
		{
			$response->message = "unauthorized";
		}
		
		echo json_encode($response);
	}
	
	public function addUserInDomain($domain)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		
		if($session->currentUser)
		{
			if($session->currentUser->level == YSSUserLevel::kAdministrator && 
			   $session->currentUser->domain == $domain)
			{
				$company = YSSCompany::companyWithDomain($session->currentUser->domain);
				
				if($company)
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

						// do the username and email values already exist?
						$user           = YSSUser::userWithEmail($input->email);
						$usernameExists = YSSUser::userWithUsernameInDomain($input->username, $session->currentUser->domain);
						
						$dirty          = false;
						
						if($usernameExists == null)
						{
							if($user)
							{
								$dirty = true;
								$input->addValidator(new AMErrorValidator('email', "Invalid email address.  This email address is currently in use."));
								$this->hydrateErrors($input, $response);
							}
							
							if(!$dirty)
							{
								$user               = new YSSUser();
								$user->domain       = $session->currentUser->domain;
								$user->username     = $input->username;
								$user->email        = $input->email;
								$user->firstname    = $input->firstname;
								$user->lastname     = $input->lastname;
								$user->level        = $input->administrator ? YSSUserLevel::kAdministrator : YSSUserLevel::kUser;
								$user->active       = YSSUserActiveState::kInactive;
								$user->password     = YSSUser::passwordWithStringAndDomain(YSSSecurity::generate_token(), $session->currentUser->domain);
								
								$user               = $user->save();

								$company->addUser($user);
								$token = YSSUserVerification::register($user);
								
								$response->ok    = true;
								//$response->token = $token;
							}
						}
						else
						{
							if($user)
								$input->addValidator(new AMErrorValidator('email', "Invalid email address.  This email address is currently in use."));
								
							$input->addValidator(new AMErrorValidator('username', "Invalid username.  This username is already taken"));
							$this->hydrateErrors($input, $response);
						}
					}
					else
					{
						$this->hydrateErrors($input, $response);
					}
				}
				else
				{
					$response->message = "unauthorized";
				}
			}
			else
			{
				$response->message = "unauthorized";
			}
		}
		else
		{
			$response->message = "unauthorized";
		}
		
		echo json_encode($response);
	}
	
	public function getUsersInDomain($domain)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		
		if($session->currentUser)
		{
			if($session->currentUser->domain == $domain)
			{
				$company = YSSCompany::companyWithDomain($session->currentUser->domain);
			
				if($company)
				{
					$response->ok    = true;
					$response->users = array();
				
					$users = $company->getUsers();
				
					foreach($users as $user)
						$response->users[] = $user;
				}
				else
				{
					$response->message = "invalid company";
				}
			}
			else
			{
				$response->message = "unauthorized";
			}
		}
		else
		{
			$response->message = "unauthorized";
		}
		
		echo json_encode($response);
	}
	
	public function getDomainInfo($domain)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		/*
			TODO Company needs the logo image url
		*/
		if($session->currentUser)
		{
			if($session->currentUser->domain == $domain)
			{
				$company = YSSCompany::companyWithDomain($session->currentUser->domain);
				if($company)
				{
					$response->ok = true;
					$response->company = array("name"      => $company->name, 
					                           "domain"    => $company->domain,
					                           "timestamp" => $company->timestamp,
					                           "users"     => $company->users);
				}
				else
				{
					$response->message = "invalid company";
				}
			}
			else
			{
				$response->message = "unauthorized";
			}
		}
		else
		{
			$response->message = "unauthorized";
		}
		
		echo json_encode($response);
	}
	
	public function logout()
	{
		$response     = new stdClass();
		$response->ok = true;
		
		$session  = YSSSession::sharedSession();
		$session->destroy();
		
		echo json_encode($response);
	}
	
	public function login()
	{
		$response     = new stdClass();
		$response->ok = false;
		
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
				$this->hydrateErrors($input, $response);
			}
			else
			{
				$currentUser            = new YSSCurrentUser();
				$currentUser->id        = $user->id;
				$currentUser->domain    = $input->domain;
				$currentUser->firstname = $user->firstname;
				$currentUser->lastname  = $user->lastname;
				$currentUser->username  = $user->username;
				$currentUser->email     = $user->email;
				$currentUser->level     = $user->level;
				
				$session  = YSSSession::sharedSession();
				$session->currentUser = $currentUser;
				
				$response->ok     = true;
				$response->user   = $currentUser;
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
	
	public function registerAccount()
	{
		$response     = new stdClass();
		$response->ok = false;
		
		/*
			The general idea here is that we can have duplicate company names
			The domains MUST be unique.  The domain + username might be hashed 
			so we can check for duplicate usernames within the same company.  
			In this way the same username can exist
			across the system, but it will be bound to unique company id, aka the domain.
		*/
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
	
		$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
		$input->addValidator(new AMInputValidator('company', AMValidator::kRequired, 2, null, "Invalid company name.  Expecting minimum 2 characters."));
		$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
		$input->addValidator(new AMPatternValidator('domain', AMValidator::kRequired, '/^[a-zA-Z0-9-]+$/', "Invalid domain.  Expecting minimum 1 character. Cannot contain spaces"));
		$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
		$input->addValidator(new AMPatternValidator('password', AMValidator::kRequired, '/^[\w\d\W]{5,}$/', "Invalid password.  Expecting minimum 5 characters. Cannot contain spaces"));
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
			$data['domain']    = strtolower($data['domain']);
			$data['username']  = strtolower($data['username']);
			
			// do the domain and email values already exist?
			$company = YSSCompany::companyWithDomain($input->domain);
			$user    = YSSUser::userWithEmail($input->email);
			
			$dirty   = false;
			
			if($company)
			{
				$input->addValidator(new AMErrorValidator('domain', "Invalid domain.  This domain is currently in use."));
				$dirty = true;
			}
			
			if($user)
			{
				$input->addValidator(new AMErrorValidator('email', "Invalid email address.  This email address is currently in use."));
				$dirty = true;
			}
			
			if($dirty) 
			{
				$this->hydrateErrors($input, $response);
			}
			else
			{
				$company            = new YSSCompany();
				$company->name      = $input->company;
				$company->domain    = $input->domain;
				
				$user               = new YSSUser();
				$user->domain       = $input->domain;
				$user->username     = $input->username;
				$user->email        = $input->email;
				$user->firstname    = $input->firstname;
				$user->lastname     = $input->lastname;
				$user->level        = YSSUserLevel::kAdministrator;
				$user->password     = YSSUser::passwordWithStringAndDomain($input->password, $user->domain);
				
				$company            = $company->save();
				$user               = $user->save();
				
				$company->addUser($user);
				
				// create the store for the domain
				YSSDomain::create($company->domain);
				
				$response->ok = true;
			}
		}
		
		echo json_encode($response);
	}
	
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceDefault());
$manager->start();
?>