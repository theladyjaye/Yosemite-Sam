<?php
class YSSApplication
{
	private static $application;
	
	public static function sharedApplication()
	{
		return YSSApplication::$application;
	}
	
	public static function basePath()
	{
		static $path;
		
		if($path == null)
		{
			$path = realpath('./');
		}
		
		return $path;
	}
	
	public static function current_language()
	{
		return 'en-US';
	}
	
	public function __construct()
	{
		new YSSConfiguration('config.ini');
		YSSApplication::$application = $this;
	}
	
	
}
?>