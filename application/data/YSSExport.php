<?php
class YSSExport
{
	public static function exportProjectInDomain(YSSProject $project, $domain)
	{
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $domain);

		$options  = array('key'          => $project->_id,
		                  'include_docs' => true);

		$result  = $database->view("project/project-forward", $options, false);
		
		$payload              = new stdClass();
		$payload->project     = null;
		$payload->views       = array();
		$payload->states      = array();
		$payload->tasks       = array();
		$payload->notes       = array();
		$payload->attachments = array();
		$payload->taskGroups  = array();
		
		foreach($result as $document)
		{
			$type = $document['type'];
			unset($document['_rev']);
			switch($type)
			{
				case 'project':
					$payload->project = $document;
					break;
				
				case 'view':
					$payload->views[] = $document;
					break;
				
				case 'state':
					$payload->states[] = $document;
					break;
					
				case 'task':
					$payload->tasks[] = $document;
					break;
				
				case 'note':
					$payload->notes[] = $document;
					break;
				
				case 'attachment':
					//
					$payload->attachments[] = $document;
					break;
				
				case 'taskGroup':
					$payload->taskGroups[] = $document;
					break;
			}
		}
	}
}
?>