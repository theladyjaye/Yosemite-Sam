function(doc) 
{
	var project = doc._id.split("/").slice(0, 2).join("/");
	
	if(doc.type == "project")
	{    
		emit([project, 0], doc)
	}
	
	if (doc.type == "view")
	{
		emit([project, 1], {type:"view", value:1});
	}
	
	if (doc.type == "task")
	{
		emit([project, 2], {type:"task", value:doc.complete ? 1 : 0})
	}
	
	
	
}
