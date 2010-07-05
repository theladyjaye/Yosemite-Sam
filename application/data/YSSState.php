<?php
class YSSState extends YSSCouchObject
{
	public $title;
	public $description;
	public $project;
	public $complete;
	
	protected $type = "state";
	
	public static function taskWithJson($jsonString)
	{
		return YSSState::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSState();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>