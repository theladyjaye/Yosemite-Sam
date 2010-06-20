<?php
class YSSDatabase
{
	const kSql     = 1;
	const kCouchDB = 2;
	
	public static function database($type)
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
		
	}
	
	private static function CouchDbDatabase()
	{
		
	}
}
?>