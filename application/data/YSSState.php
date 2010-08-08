<?php
class YSSState extends YSSCouchObject
{
	const kDefault = 'default';
	public $label;
	public $description;
	
	protected $type = "state";
	
	public static function taskWithJson($jsonString)
	{
		return YSSState::hydrateWithArray(json_decode($jsonString, true));
	}
	
	public function addAttachment(YSSAttachment $attachment)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($attachment->_id, $this->_id) !== 0)
			$attachment->_id = $this->_id.'/attachment/'.YSSSecurity::generate_token();
		
		return $attachment->save();
	}
	
	public function addTask(YSSTask $task)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($task->_id, $this->_id) !== 0)
			$task->_id = $this->_id.'/'.YSSSecurity::generate_token();
		
		return $task->save();
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