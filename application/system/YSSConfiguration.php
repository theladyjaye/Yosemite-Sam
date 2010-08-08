<?php
class YSSConfiguration
{
	private static $domain;
	private static $data;
	private static $configuration;
	
	public function __construct($path)
	{
		$data                = parse_ini_file($path, true);
		self::$domain        = $data['application']['domain'];
		self::$configuration = $data['application']['configuration'];
		$currentUser         = null;
		
		if($data['autologin'] && $data['application']['configuration'] == 'debug')
		{
			$currentUser            = new YSSCurrentUser();
			$currentUser->id        = $data['autologin']['userid'];
			$currentUser->domain    = $data['autologin']['domain'];
			$currentUser->firstname = $data['autologin']['firstname'];
			$currentUser->lastname  = $data['autologin']['lastname'];
			$currentUser->username  = $data['autologin']['username'];
			$currentUser->email     = $data['autologin']['email'];
			$currentUser->level     = $data['autologin']['level'];
		}
		
		self::$data       = $data[$data['application']['configuration']];
		self::$data['s3'] = $data['s3'];
		
		if($currentUser)
			self::$data['currentUser'] = $currentUser;
	}
	
	public static function applicationConfiguration()
	{
		return YSSConfiguration::$configuration;
	}
	
	public static function applicationDomain()
	{
		return YSSConfiguration::$domain;
	}
	
	public static function standardConfiguration()
	{
		return YSSConfiguration::$data;
	}
}
?>