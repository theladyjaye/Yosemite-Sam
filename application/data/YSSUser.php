<?php
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithEmail.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithUsernameInDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserInsert.php';

class YSSUser
{
	public $id;
	public $level;
	public $domain;
	public $username;
	public $email;
	public $firstname;
	public $lastname;
	public $password;
	public $timestamp;
	
	public static function passwordWithStringAndDomain($password, $domain)
	{
		return hash_hmac('sha256', $password, $domain);
	}
	
	public static function userWithEmail($email)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserWithEmail($database, $email);
		
		if(count($query) == 1)
		{
			$object = YSSUser::hydrateWithArray($query->one());
		}
		
		return $object;
	}
	
	public static function userWithUsernameInDomain($username, $domain)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserWithUsernameInDomain($database, array('username'=>$username, 'domain'=>$domain));
		
		if(count($query) == 1)
		{
			$object = YSSUser::hydrateWithArray($query->one());
		}
		
		return $object;
	}
	
	private static function hydrateWithArray($array)
	{
		$object            = new YSSUser();
		$object->id        = $array['id'];
		$object->level     = $array['level'];
		$object->domain    = $array['domain'];
		$object->username  = $array['username'];
		$object->email     = $array['email'];
		$object->firstname = $array['firstname'];
		$object->lastname  = $array['lastname'];
		$object->password  = $array['password'];
		$object->timestamp = $array['timestamp'];
		
		return $object;
	}
	
	public function save()
	{
		if($this->id)
		{
			// update
			/*
				TODO enable YSSUser update
			*/
			$object = $this;
		}
		else
		{
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryUserInsert($database, array('level'     => $this->level,
				                                                'domain'    => $this->domain, 
			                                                    'username'  => $this->username,
			                                                    'email'     => $this->email,
			                                                    'firstname' => $this->firstname,
			                                                    'lastname'  => $this->lastname,
			                                                    'password'  => $this->password));
			$query->execute();
			$object = YSSUser::userWithEmail($this->email);
		}
		
		return $object;
	}
}
?>