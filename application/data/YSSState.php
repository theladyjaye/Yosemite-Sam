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
		$result = false;
		
		if(!$this->_rev)
		{
			throw new Exception("YSSState must be saved prior to adding an attachment");
			exit;
		}
			
		$database = $this->database();
		$response = $database->put_attachment($attachment, $this->_id, $this->_rev);
		
		if(isset($response['ok']) && $response['ok'] == true)
			$result = true;
			
		return $result;
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