<?php
class YSSCouchObject
{
	public $_id;
	public $_rev;
	public $created_by;
	public $created_at;
	protected $type;
	
	protected function database()
	{
		static $database;
		
		if(!$database)
		{
			$session  = YSSSession::sharedSession();
			$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		}
		
		return $database;
	}
	public function save()
	{
		$ok       = false;
		$session  = YSSSession::sharedSession();
		//$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		if(!$this->_rev) // it's a new item
		{
			$this->cerated_by = $session->currentUser->username;
			$this->created_at = YSSApplication::timestamp_now();
		}
		
		$database = $this->database();
		$response = $database->put($this, $this->_id);
		
		if(isset($response['ok']))
		{
			$ok = true;
			$this->_id = $response['id'];
			$this->_rev = $response['rev'];
		}
		
		return $ok;
	}
	
	public function __toString()
	{
		$obj = new stdClass();
		
		foreach($this as $key=>$value)
		{
			if($key != '_id' && $value !== null)
			{
				$obj->{$key} = $value;
			}
		}
		
		$obj->type = $this->type;
		return json_encode($obj);
	}
}
?>