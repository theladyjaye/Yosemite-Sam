<?php
class YSSView extends YSSCouchObject
{
	public $name;
	public $description;
	public $project;
	public $states;
	
	protected $type = "view";
	
	public static function taskWithJson($jsonString)
	{
		return YSSView::hydrateWithArray(json_decode($jsonString, true));
	}
	
	public function addState(YSSState $state)
	{
		return $state->save();
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