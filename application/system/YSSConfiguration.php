<?php
class YSSConfiguration
{
	private static $configuration;
	
	public function __construct($path)
	{
		$data       = parse_ini_file($path, true);
		YSSConfiguration::$configuration = $data[$data['application']['configuration']];
	}
	
	public static function standardConfiguration()
	{
		return YSSConfiguration::$configuration;
	}
}
?>