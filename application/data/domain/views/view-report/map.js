function(doc) 
{
	var project = doc._id.split("/").slice(0, 3).join("/");
	
	if(doc.type == "view")
	{
		emit([project, 0], doc);
	}
	
	if(doc.type == "state")
	{
		emit([project, 1], {type:"state", label:doc.label, _id:doc._id});
	}
	
	if (doc.type == "task")
	{
		emit([project, 2], {type:"task", value:doc.complete ? 1 : 0})
	}
}
