<?php
class YSSProject extends YSSCouchObject
{
	public $label;
	public $description;
	
	protected $type = "project";
	
	public static function projectWithId($id)
	{
		$object   = null;
		$session  = YSSSession::sharedSession();
		$couchdb  = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		$response = $couchdb->document('project/'.$id);
		
		if(!isset($response['error']))
		{
			$object = YSSProject::hydrateWithArray($response);
		}
		
		return $object;
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