<?php
class YSSState extends YSSCouchObject
{
	const kDefault = 'default';
	public $label;
	public $description;
	public $project;
	public $view;
	public $complete;
	
	protected $type = "state";
	
	public static function taskWithJson($jsonString)
	{
		return YSSState::hydrateWithArray(json_decode($jsonString, true));
	}
	
	public function addAttachment($attachment)
	{
		
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