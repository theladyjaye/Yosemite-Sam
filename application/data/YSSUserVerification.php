<?php
class YSSUserVerification
{
	// for the creation of a new domain
	public static function welcome(YSSUser $user)
	{
		require YSSApplication::basePath().'/application/data/queries/YSSQueryUserVerificationInsert.php';
		require YSSApplication::basePath().'/application/mail/YSSMessageWelcome.php';
		
		$token    = YSSSecurity::generate_token();
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserVerificationInsert($database, array('token'   => $token,
		                                                                'domain'  => $user->domain,
		                                                                'user_id' => $user->id));
		$query->execute();
		
		$message = new YSSMessageWelcome($user->email, $user->domain, $token);
		$message->send();
		
		return $token;
	}
	
	// when users are added to domains
	public static function register(YSSUser $user)
	{
		require YSSApplication::basePath().'/application/data/queries/YSSQueryUserVerificationInsert.php';
		require YSSApplication::basePath().'/application/mail/YSSMessageVerifyAccount.php';
		
		$token    = YSSSecurity::generate_token();
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserVerificationInsert($database, array('token'   => $token,
		                                                                'domain'  => $user->domain,
		                                                                'user_id' => $user->id));
		$query->execute();
		
		$message = new YSSMessageVerifyAccount($user->email, $user->domain, $token);
		$message->send();
		
		return $token;
	}
	
	public static function verify($token, $domain)
	{
		
		require YSSApplication::basePath().'/application/data/queries/YSSQueryUserVerificationForTokenInDomain.php';
		require YSSApplication::basePath().'/application/mail/YSSMessageVerifyAccountComplete.php';
		require YSSApplication::basePath().'/application/data/queries/YSSQueryUserVerificationRemoveForTokenInDomain.php';
		
		$result   = false;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserVerificationForTokenInDomain($database, array('token'=>$token, 'domain'=>$domain));
		
		if(count($query) == 1)
		{
			$result  = $query->one();
			$user_id = $result['user_id'];
			
			if($user_id)
			{
				$user = YSSUser::userWithId($user_id);
				
				if($user)
				{
					$password       = YSSSecurity::generate_password();
					
					$user->active   = YSSUserActiveState::kActive;
					$user->password = YSSUser::passwordWithStringAndDomain($password, $user->domain);
					$user->save();
					
					$message           = new YSSMessageVerifyAccountComplete($user->email);
					$message->password = $password;
					$message->domain   = $user->domain; 
					$message->send();
					
					$query    = new YSSQueryUserVerificationRemoveForTokenInDomain($database, array('token'=>$token, 'domain'=>$domain, 'user_id'=>$user->id));
					$query->execute();
					
					$result = true;
				}
			}
		}
		
		return $result;
	}
}
?>