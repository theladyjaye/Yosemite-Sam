<?php
class YSSTaskGroup extends YSSCouchObject
{
	public $label;
	public $children;
	protected $type = "taskGroup";
	
	private $project;
	
	public static function groupWithProject(YSSProject $project)
	{
		if(!$project->_rev)
			$project->save();
		
		$object          = new YSSTaskGroup();
		$object->project = $project->_id;
		$object->_id     = $project->_id.'/group/task/'.YSSSecurity::generate_token();
		
		return $object;
	}
	
	public static function groupWithId($id)
	{
		$object    = null;
		$session   = YSSSession::sharedSession();
		$database  = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
		
		if(strpos($id, 'project/') !== 0)
			throw new Exception("invalid group id, requires full uri");
			
		$response = $database->document($id);
		
		if(!isset($response['error']))
		{
			$object = YSSTaskGroup::hydrateWithArray($response);
		}
		
		return $object;
	}
	
	public static function groupWithIdInProject($id, $project)
	{
		if(strpos($id, 'project/'.$project.'/group/task/') !== 0)
			$id = 'project/'.$project.'/group/task/'.$id;
			
		return YSSTaskGroup::groupWithId($id);
	}
	
	public function addTask(YSSTask $task)
	{
		if(!$this->project)
		{
			throw new Exception("Task Group does not belong to a project.");
			return;
		}
		
		if(strpos($task->_id, $this->project) !== 0)
		{
			throw new Exception("Attempting to add a task from a foreign project");
			return;
		}
		
		if(!$task->_rev)
			$task->save();
		
		$task->group = $this->_id;
		$task->save();
			
		if($this->children == null)
			$this->children = array();
		
		array_push($this->children, $task->_id);
	}
	
	public function removeTask(YSSTask $task);
	{	
		$index = array_search($task->_id, $this->children);
		
		if($index !== false)
			array_splice($this->children, $index, 1);
	}
	
	private static function hydrateWithArray($array)
	{
		$object  = new YSSTaskGroup();
		foreach($array as $key=>$value)
		{
			$object->{$key} = $value;
		}
		
		$object->project = substr($object->_id, 0, strpos($object->_id, '/group/task'));
		
		return $object;
	}
}
?>