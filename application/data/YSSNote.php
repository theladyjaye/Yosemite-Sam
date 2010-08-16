<?php
class YSSNote extends YSSAnnotation
{
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