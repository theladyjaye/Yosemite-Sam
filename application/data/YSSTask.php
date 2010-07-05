<?php
class YSSTask extends YSSCouchObject
{
	public $label;
	public $description;
	public $project;
	public $view;
	public $state;
	public $complete;
	
	protected $type = "task";
	
	public static function taskWithJson($jsonString)
	{
		return YSSTask::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSTask();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>