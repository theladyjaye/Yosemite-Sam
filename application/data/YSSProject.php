<?php
class YSSProject extends YSSCouchObject
{
	public $label;
	public $description;
	public $attachments;
	
	protected $type = "project";
	
	public static function projectWithId($id)
	{
		$object    = null;
		$session   = YSSSession::sharedSession();
		$database  = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		if(strpos($id, 'project/') !== 0)
			$id = 'project/'.$id;
			
		$response = $database->document($id);
		
		if(!isset($response['error']))
		{
			$object = YSSProject::hydrateWithArray($response);
		}
		
		return $object;
	}
	
	public function addView(YSSView $view)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($view->_id, $this->_id) !== 0)
			$view->_id = $this->_id.'/'.$view->_id;
		
		$view->save();
	}
	
	public function addAttachment(YSSAttachment $attachment)
	{
		if(!$this->_rev)
			$this->save();
		
		if(strpos($attachment->_id, $this->_id) !== 0)
			$attachment->_id = $this->_id.'/attachment/'.YSSSecurity::generate_token();
		
		$attachment->save();
	}
	
	
	public static function projectWithJson($jsonString)
	{
		return YSSProject::hydrateWithArray(json_decode($jsonString, true));
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSProject();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		return $object;
	}
}
?>