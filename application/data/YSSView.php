<?php
class YSSView extends YSSCouchObject
{
	public $title;
	public $description;
	public $project;
	public $complete;
	
	protected $type = "view";
	
	public static function taskWithJson($jsonString)
	{
		return YSSView::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSView();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>