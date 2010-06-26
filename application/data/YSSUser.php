<?php

require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithId.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithEmail.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserWithUsernameInDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserInsert.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryUserUpdate.php';

class YSSUserActiveState
{
	const kActive   = 1;
	const kInactive = 0;
}

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
	public $active;
	public $timestamp;
	
	public static function passwordWithStringAndDomain($password, $domain)
	{
		return hash_hmac('sha256', $password, $domain);
	}
	
	public static function userWithId($id)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryUserWithId($database, $id);
		
		if(count($query) == 1)
		{
			$object = YSSUser::hydrateWithArray($query->one());
		}
		
		return $object;
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
		$object->level     = (int) $array['level'];
		$object->domain    = $array['domain'];
		$object->username  = $array['username'];
		$object->email     = $array['email'];
		$object->firstname = $array['firstname'];
		$object->lastname  = $array['lastname'];
		$object->password  = $array['password'];
		$object->active    = (int) $array['active'];
		$object->timestamp = $array['timestamp'];
		
		return $object;
	}
	
	public function save()
	{
		if($this->id)
		{
			// update
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryUserUpdate($database, array('id'        => $this->id,
				                                                'level'     => $this->level,
				                                                'domain'    => $this->domain, 
			                                                    'username'  => $this->username,
			                                                    'email'     => $this->email,
			                                                    'firstname' => $this->firstname,
			                                                    'lastname'  => $this->lastname,
			                                                    'active'    => $this->active,
			                                                    'password'  => $this->password));
			$query->execute();
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
			                                                    'active'    => $this->active,
			                                                    'password'  => $this->password));
			$query->execute();
			$object = YSSUser::userWithEmail($this->email);
		}
		
		return $object;
	}
}
?>