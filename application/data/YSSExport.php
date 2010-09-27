<?php
class YSSExport
{
	public static function exportProjectInDomain(YSSProject $project, $domain)
	{
		/*
			TODO 
			1) make a temp folder for working
			2) write $payload to a file (need a name) within this folder
			3) make an attachments folder
			4) write physical attachments to this location
			5) zip the whole thing up and deliver it to the user
			
			Do we want any checksum storage, etc?  EG: can the user only import files that have been exported
			or can users create their own system as long as it matches our format and import that.
			I think I'm leaning towards allowing users to import their own documents, the only issue I have here is "attachments"
			if we let this go, then you could concievibaly upload anything you want, eg: some executable and start using the service
			for file hosting. 
			
			Maybe we just limit the filesize to 1 meg?  Of course enforcing a checksum validation would prevent this, but then
			we need to store all of the checksums to verify. Maybe that's not a huge deal, I dunno.
		*/
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
					// need to wrate attachments to a temp folder here.
					$payload->attachments[] = $document;
					break;
				
				case 'taskGroup':
					$payload->taskGroups[] = $document;
					break;
			}
		}
		
		$data = json_encode($payload);
		
		// write to a file, 
	}
}
?>