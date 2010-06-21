<?php
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithEmail.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithUsername.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserInsert.php';

class YSSUser
{
	public $id;
	public $domain;
	public $username;
	public $email;
	public $firstname;
	public $lastname;
	public $password;
	
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
	
	public static function userWithUsername($username)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserWithUsername($database, $username);
		
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
		$object->domain    = $array['domain'];
		$object->username  = $array['username'];
		$object->email     = $array['email'];
		$object->firstname = $array['firstname'];
		$object->lastname  = $array['lastname'];
		$object->password  = $array['password'];
		
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
			$query    = new YSSQueryUserInsert($database, array('domain'    => $this->domain, 
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