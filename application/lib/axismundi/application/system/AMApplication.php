<?php
class AMApplication
{
	public $defaults;
	
	private $delegate;
	
	private static $application;
	
	public static function current_language()
	{
		return 'en_US';
	}
	
	public static function sharedApplication()
	{
		return AMApplication::$application;
	}
	
	public function __construct()
	{
		$this->defaults      = parse_ini_file('application.ini', true);
		PHPApplication::$application = $this;
		
		if(isset($this->defaults['application']['delegate']))
		{
			$this->delegate = new $this->defaults['application']['delegate']();
			$this->delegate->applicationDidFinishLaunching();
		}
		
		$this->handleRequest();
	}
	
	private function handleRequest()
	{
		global $page;
		$view = null;
		if(isset($_GET['view']))
		{
			$prefix = isset($this->defaults['resources']['views']) ? $this->defaults['resources']['views'].'/' : null;
			$view   =  $prefix.$_GET['view'];
		}
		else
		{
			$end  = strpos($_SERVER['REQUEST_URI'], '?') === false ? strlen($_SERVER['REQUEST_URI']) -1  : strpos($_SERVER['REQUEST_URI'], '?') -1;
			$view = substr($_SERVER['REQUEST_URI'], 1, $end);
			
			if(!$view){
				$view = $this->defaults['application']['default_view'];
			}
		}
		
		if($this->delegate){
			$this->delegate->applicationWillLoadView($view);
		}
		
		require $view;
	}
	
	public function __destruct()
	{
		if($this->delegate)
		{
			$this->delegate->applicationWillTerminate();
		}
	}
}
?>