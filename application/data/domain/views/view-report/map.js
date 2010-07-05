function(doc) 
{
	if(doc.type == "project")
	{    
		emit([doc._id, 0], doc)
	}
	
	if (doc.type == "view")
	{
		emit([doc.project, 1], {type:"view", value:1});
	}
	
	if (doc.type == "task")
	{
		emit([doc.project, 2], {type:"task", value:doc.complete ? 1 : 0})
	}
}
