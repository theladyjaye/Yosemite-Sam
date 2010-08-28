<?php
class YSSState extends YSSCouchObject
{
	const kDefault = 'default';
	public $label;
	public $description;
	public $attachments;
	
	protected $type = "state";
	
	public static function stateWithId($id)
	{
		$object    = null;
		$session   = YSSSession::sharedSession();
		$database  = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
			
		$response = $database->document($id);
		
		if(!isset($response['error']))
		{
			$object = YSSState::hydrateWithArray($response);
		}
		
		return $object;
	}
	
	public function addAttachment(YSSAttachment $attachment)
	{			
		if(!$this->_rev)
			$this->save();
		
		if(strpos($attachment->_id, $this->_id) !== 0)
			$attachment->_id = $this->_id.'/attachment/'.YSSSecurity::generate_token();
				
		return $attachment->save();
	}
	
	public function addAnnotation(YSSAnnotation $annotation)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($annotation->_id, $this->_id) !== 0)
			$annotation->_id = $this->_id.'/'.YSSSecurity::generate_token();
		
		return $annotation->save();
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