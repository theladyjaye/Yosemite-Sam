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
	
	public function addAttachment($attachment)
	{
		$result = false;
		
		if(!isset($attachment['name']) ||
		   !isset($attachment['path']))
		{
			throw new Exception("Invalid attachment name or path");
			//return $result;
		}
		
		$fileinfo     = finfo_open(FILEINFO_MIME_TYPE);
		$content_type = finfo_file($fileinfo, $attachment['path']);
		finfo_close($fileinfo);
			
		$attachment['content_type'] = $content_type;
		
		
		if(!$this->_rev)
			$this->save();
			
		$database = $this->database();
		$response = $database->put_attachment($attachment, $this->_id, $this->_rev);
		
		if(isset($response['ok']) && $response['ok'] == true)
			$result = true;
			
		return $result;
	}
	
	public function addTask(YSSTask $task)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($task->_id, $this->_id) !== 0)
			$task->_id = $this->_id.'/'.YSSSecurity::generate_token();
		
		$task->save();
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