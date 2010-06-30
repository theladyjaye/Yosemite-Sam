<?php
class YSSProject extends YSSCouchObject
{
	public $name;
	public $description;
	protected $type = "project";
	
	public static function projectWithJson($jsonString)
	{
		return YSSProject::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSProject();
		foreach($array as $key=>$value)
		{
			echo $key, ': ', $value;
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>