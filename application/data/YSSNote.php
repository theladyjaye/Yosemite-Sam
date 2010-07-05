<?php
class YSSNote extends YSSCouchObject
{
	public $label;
	public $description;
	protected $type = "note";
	
	public static function projectWithJson($jsonString)
	{
		return YSSNote::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSNote();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>