<?php
class YSSConfiguration
{
	private static $domain;
	private static $configuration;
	
	public function __construct($path)
	{
		$data                = parse_ini_file($path, true);
		self::$domain        = $data['application']['domain'];
		self::$configuration = $data[$data['application']['configuration']];
		
	}
	
	public static function applicationDomain()
	{
		return YSSConfiguration::$domain;
	}
	
	public static function standardConfiguration()
	{
		return YSSConfiguration::$configuration;
	}
}
?>