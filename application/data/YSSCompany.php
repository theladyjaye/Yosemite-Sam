<?php
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyWithDomain.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyInsertUser.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyDeleteUser.php';
require YSSApplication::basePath().'/application/data/queries/YSSQueryCompanyInsert.php';

class YSSCompany
{
	public $id;
	public $name;
	public $domain;
	
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
	
	public function save()
	{
		$object = null;
		
		if($this->id)
		{
			// update
			/*
				TODO create update comapny query
			*/
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