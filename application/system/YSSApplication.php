<?php
class YSSApplication
{
	private static $application;
	
	public static function sharedApplication()
	{
		return YSSApplication::$application;
	}
	
	public function __construct()
	{
		new YSSConfiguration('config.ini');
		YSSApplication::$application = $this;
	}
	
	public function current_language()
	{
		return 'en-US';
	}
}
?>