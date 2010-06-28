function(doc) 
{
	if(doc.type == "project")  
	{    
		emit([doc._id, {}],doc)
	}  
	if (doc.type == "task")  
	{   
		emit([doc.project, "report"] , {"count":1, "complete": doc.complete? 1 : 0})
	}
}
