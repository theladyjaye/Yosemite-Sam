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
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFileValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMFileSizeValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';


require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';

require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';
require YSSApplication::basePath().'/application/data/YSSUserVerification.php';
require YSSApplication::basePath().'/application/data/YSSAttachment.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUsersForDomain.php';



require YSSApplication::basePath().'/application/system/YSSService.php';

class YSSServiceAccounts extends YSSService
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
				$this->addEndpoint("POST",    "/api/account/{domain}/users/reset/{email}", "resetPassword");
				$this->addEndpoint("POST",    "/api/account/{domain}/users/{username}",    "updateUserInDomain");
				$this->addEndpoint("POST",    "/api/account/{domain}/users",               "addUserInDomain");
				$this->addEndpoint("POST",    "/api/account/{domain}",                     "updateDomain");
				break;
			
			case "DELETE":
				$this->addEndpoint("DELETE",    "/api/account/{domain}/users/{username}",  "deleteUserInDomain");
				break;
		}
	}
	
	public function updateDomain($domain)
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$session  = YSSSession::sharedSession();
		if($session->currentUser && $session->currentUser->domain == $domain)
		{
			if($session->currentUser->level == YSSUserLevel::kAdministrator)
			{
				$company = YSSCompany::companyWithDomain($session->currentUser->domain);
				$context = array(AMForm::kFilesKey=>$_FILES);
				$input   = AMForm::formWithContext($context);
			
				$input->addValidator(new AMFileValidator('logo', AMValidator::kRequired, "Invalid attachment. None provided."));
				$input->addValidator(new AMFilesizeValidator('logo', AMValidator::kRequired, MAX_UPLOAD_SIZE, "Invalid attachment size. Expecting maximum ".(MAX_UPLOAD_SIZE / 1024)." megabytes."));
			
				if($input->isValid)
				{
					$id        = "domain-logo";
					$logo      = YSSAttachment::attachmentWithIdInDomain($id, $session->currentUser->domain);
					
					if($logo)
					{
						$logo->setFile($input->logo->tmp_name);
						YSSAttachment::saveAttachmentInDomain($logo, $session->currentUser->domain);
					}
					else
					{
						$logo      = YSSAttachment::attachmentWithLocalFileInDomain($input->logo->tmp_name, $session->currentUser->domain);
						$logo->_id = "domain-logo";
					}
					
					$logo->save();
					
					$company->logo  = YSSAttachment::attachmentEndpointWithId($logo->_id);
					$company->save();
					
					$response->ok   = true;
					$response->path = $company->logo;
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
		
		
		
		echo json_encode($response);
	}
	
	public function resetPassword($domain, $email)
	{
		// always returing {ok:true} here no matter what $email or $domain is given
		// no need to let people know what the real domains / accounts are.
		
		$response     = new stdClass();
		$response->ok = true;
		
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
					
					
					// if password is passed in then the user is trying to change their password
					// otherwise the user is editing their information		
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
					else
					{
						$input->addValidator(new AMPatternValidator('firstname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid first name. Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
						$input->addValidator(new AMPatternValidator('lastname', AMValidator::kRequired, '/^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/', "Invalid last name.  Expecting minimum 2 characters. Must start with at least 2 letters, followed by letters or spaces"));
						$input->addValidator(new AMPatternValidator('username', AMValidator::kRequired, '/^[\w\d]{4,}$/', "Invalid username.  Expecting minimum 4 characters. Must be composed of letters, numbers or _"));
						$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, 'Invalid email address'));
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
							
							// remove password, active, and timestamp from user
							unset($user->password, $user->active, $user->timestamp);
														
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
					{	
						// identify current logged in user by username				
						$user['is_current_user'] = ($user['username'] == $session->currentUser->username);											
						$response->users[] = $user;
					}					
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
				// need the number of users
				$company = YSSCompany::companyDetailsWithDomain($session->currentUser->domain);
				if($company)
				{
					$response->ok = true;
					$response->company = array("name"               => $company->name,
					                           "domain"             => $company->domain,
					                           "timestamp"          => $company->timestamp,
					                           "users"              => $company->users,
					                           "logo"               => $company->logo,
											   "current_username"   => $session->currentUser->username,
											   "user_level"         => $session->currentUser->level);
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
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceAccounts());
$manager->start();
?>