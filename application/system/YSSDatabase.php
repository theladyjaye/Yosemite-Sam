<?php
class YSSDatabase
{
	const kSql     = 1;
	const kCouchDB = 2;
	
	public static function connection($type)
	{
		switch($type)
		{
			case YSSDatabase::kSql:
				return YSSDatabase::SqlDatabase();
				break;
			
			case YSSDatabase::kCouchDB:
				return YSSDatabase::CouchDbDatabase();
				break;
		}
	}
	
	private static function SqlDatabase()
	{
		static $connection = null;
		
		if(empty($connection))
		{
			$configuration = YSSConfiguration::standardConfiguration();
			$connection =  new mysqli($configuration['database.sql.host'], 
				                      $configuration['database.sql.username'],
				                      $configuration['database.sql.password'],
				                      $configuration['database.sql.catalog']);
		
			if ($connection->connect_errno) 
			{
				// handle this error accordingly
				//echo 'Error', $connection->connect_errno;
			}
		}
		
		return $connection;
	}
	
	private static function CouchDbDatabase()
	{
		
	}
}
?>