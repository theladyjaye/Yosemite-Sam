<?php
class YSSConfiguration
{
	private static $domain;
	private static $configuration;
	
	public function __construct($path)
	{
		$data                = parse_ini_file($path, true);
		self::$domain        = $data['application']['domain'];
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
		
		self::$configuration = $data[$data['application']['configuration']];
		
		if($currentUser)
			self::$configuration['currentUser'] = $currentUser;
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