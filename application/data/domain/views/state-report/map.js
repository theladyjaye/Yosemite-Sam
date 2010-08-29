function(doc) 
{
	// !json enums.TaskStatus
	
	if(doc.type == "state" ||
	   doc.type == "task" ||
	   doc.type == "attachment")
	{
		var state  = doc._id.split("/").slice(0, 4).join("/");
		var view   = state.split("/").slice(0, 3).join("/");

		if(doc.type == "state")
		{
			emit([view, state, 0], doc);
		}
		
		if (doc.type == "task")
		{
			var taskValue;
			switch(doc.status)
			{
				case enums.TaskStatus.kStatusIncomplete:
					taskValue = 0;
					break;

				case enums.TaskStatus.kStatusComplete:
					taskValue = 1;
					break;
			}
			
			emit([view, state, 1], {"type":"task", "value":taskValue})
		}
		
		if (doc.type == "attachment" && doc._id.split('/').pop() == "representation")
		{
			emit([view, state, 2], {"type":"attachment", "_id": doc._id, "content_type":doc.content_type, "path":doc.path})
		}
	}
}
