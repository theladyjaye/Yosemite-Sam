<?php
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyWithDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyDetailsWithDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyExistsWithDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyInsertUser.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyDeleteUser.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyInsert.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyUpdate.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyUsers.php';

class YSSCompany
{
	public $id;
	public $name;
	public $domain;
	public $timestamp;
	public $users;
	public $logo = YSSAttachment::attachmentEndpointWithId("domain-logo");
	
	public static $default_logo = "/resources/imgs/peeq-domain-logo.jpg";
	
	public static function companyExistsWithDomain($domain)
	{
		$response = false;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		
		$query    = new YSSQueryCompanyExistsWithDomain($database, $domain);
		
		if(count($query))
			$response = true;
		
		return $response;
	}
	
	// this will give you back everything YSSCompany::companyWithDomain does but you will also get back
	// the quantity of users as well.  Had to seperate the 2 calls because when you register
	// the SQL to COUNT(*) that would give you the user quantity was returning a null
	// row, which was breaking the initial user save upon register.
	public static function companyDetailsWithDomain($domain)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryCompanyDetailsWithDomain($database, $domain);
		
		if(count($query) == 1)
		{
			$object = YSSCompany::hydrateWithArray($query->one());
		}
		
		return $object;
	}
	
	public static function companyWithDomain($domain)
	{
		$object   = null;
		$database = YSSDatabase::connection(YSSDatabase::kSql);
		$query    = new YSSQueryCompanyWithDomain($database, $domain);
		if(count($query) == 1)
		{
			$object = YSSCompany::hydrateWithArray($query->one());
		}
		
		return $object;
	}
	
	private static function hydrateWithArray($array)
	{
		$object             = new YSSCompany();
		$object->id         = $array['id'];
		$object->name       = $array['name'];
		$object->domain     = $array['domain'];
		$object->timestamp  = $array['timestamp'];
		$object->users      = $array['users'];
		$object->logo       = $array['logo'];
		
		return $object;
	}
	
	public function addUser(YSSUser $user)
	{
		$result = false;
		
		if($this->id && $user->id)
		{
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryCompanyInsertUser($database, array('company_id'=>$this->id, 'user_id'=>$user->id));
			$query->execute();
			
			/*
				TODO AMQuery should return a true/false status upon success or failure
			*/
			$result = true;
			
		}
		
		return $result;
	}
	
	public function deleteUser(YSSUser $user)
	{
		$result = false;
		if($this->id && $user->id)
		{
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryCompanyDeleteUser($database, array('company_id'=>$this->id, 'user_id'=>$user->id));
			$query->execute();
		}
		
		return $result;
	}
	
	public function getUsers()
	{
		if($this->id)
		{
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query = new YSSQueryCompanyUsers($database, array('company_id'=>$this->id));
			return $query;
		}
	}
	
	public function save()
	{
		$object = null;
		
		if($this->id)
		{
			// update
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryCompanyUpdate($database, array('id'=>$this->id, 'logo'=>$this->logo));
			$query->execute();
			$object = $this;
		}
		else
		{
			// insert
			$database = YSSDatabase::connection(YSSDatabase::kSql);
			$query    = new YSSQueryCompanyInsert($database, array('name'=>$this->name, 'domain'=>$this->domain));
			$query->execute();
			
			$object = YSSCompany::companyWithDomain($this->domain);
		}
		
		return $object;
	}
}
?>