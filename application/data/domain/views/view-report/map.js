function(doc) 
{
	if(doc.type == "view" ||
	   doc.type == "state" ||
	   doc.type == "task")
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
			emit([project, view, 2], {type:"task", value:doc.complete ? 1 : 0})
		}
	}
}
