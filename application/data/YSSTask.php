<?php
class YSSTask extends YSSAnnotation
{
	const kStatusIncomplete = 0;
	const kStatusComplete   = 1;
	
	public $assigned_to;
	public $status = YSSTask::kStatusIncomplete;
	public $group;
	public $priority;
	public $estimate;
	
	protected $type = "task";
	
	// Task Id's should be generated from the following:
	// YSSSecurity::generate_token($salt) = this->_id;
	// see YSSState::addTask;
	
	public static function taskWithArray($array)
	{
		return YSSTask::hydrateWithArray($array);
	}
	
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