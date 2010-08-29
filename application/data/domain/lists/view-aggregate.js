function(head, req)
{
	var row;
	var result  = [];
	var current;
	var stateLookup = {};
	
	while(row = getRow())
	{
		var type  = row.value.type;
		
		if(type == "view")
		{
			if(current)
				result.push(current)
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
			current.notes     = 0;
			current.states    = [];
			
		}
		else
		{
			var doc = row.value;
			switch(type)
			{
				case "state":
					stateLookup[doc._id] = current.states.length;
					current.states.push(doc);
					break;
				
				case "task":
					current.tasks.total++;
					current.tasks.completed += doc.value;
					break;
				
				case "note":
					current.notes++;
					break;
				
				case "attachment":
					
					// magic number 11 = "/attachment".length
					var key   = stateLookup[doc._id.substring(0, (doc._id.lastIndexOf("/") - 11))];
					var state = current.states[key];
					
					//if(typeof(state.attachments) == "undefined")
					//	state.attachments = [];
					//state.attachments.push(doc);
					
					state.attachment = doc;
					break;
			}
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}