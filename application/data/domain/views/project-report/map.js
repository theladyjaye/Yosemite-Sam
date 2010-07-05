function(doc) 
{
	if(doc.type == "project")
	{    
		emit([doc._id, 0], doc)
	}
	
	if (doc.type == "task")
	{
		emit([doc.project, 1], doc.complete ? 1 : 0)
	}
}
