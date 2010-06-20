<?php
$page = null;

abstract class YSSPage
{
	public $isPostBack = false;
	
	public static function CodeBehind($class)
	{
		global $page;
		$configuration = YSSConfiguration::standardConfiguration();
		
		//require realpath('./').'/'.$configuration['controllers'].'/'.$class;
		require $configuration['controllers'].'/'.$class;
		
		$class = substr($class, 0, strrpos($class, '.'));
		$page = new $class();
	}
	
	public function __construct()
	{
		if(count($_POST))
		{
			$this->isPostBack = true;
		}
		
		$this->page_load();
	}
	
	protected abstract function page_load();
}
?>