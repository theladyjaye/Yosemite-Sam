<?php
class YSSCouchObject
{
	public $_id;
	public $_rev;
	protected $type;
	
	public function save()
	{
		$ok       = false;
		$session  = YSSSession::sharedSession();
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, "blitz");//$session->currentUser->domain);
		
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
			if($key != '_id' && $value != null)
			{
				$obj->{$key} = $value;
			}
		}
		
		$obj->type = $this->type;
		return json_encode($obj);
	}
}
?>