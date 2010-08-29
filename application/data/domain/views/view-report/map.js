function(doc) 
{
	// !json enums.TaskStatus
	
	if(doc.type == "view"  ||
	   doc.type == "state" ||
	   doc.type == "task"  ||
	   doc.type == "note"  ||
	   doc.type == "attachment")
	{
		var view    = doc._id.split("/").slice(0, 3).join("/");
		var project = view.split("/").slice(0, 2).join("/");

		if(doc.type == "view")
		{
			emit([project, view, 0], doc);
		}

		if(doc.type == "state")
		{
			emit([project, view, 1], {type:"state", label:doc.label, _id:doc._id});
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
			
			emit([project, view, 2], {"type":"task", "value":taskValue})
		}
		
		if (doc.type == "note")
		{
			emit([project, view, 3], {"type":"note"})
		}
		
		if (doc.type == "attachment" && doc._id.split('/').pop() == "representation")
		{
			emit([project, view, 4], {"type":"attachment", "_id": doc._id, "content_type":doc.content_type, "path":doc.path})
		}
	}
}
