function(doc)
{
	var project = doc._id.split("/").slice(0, 2).join("/");
	
	if(doc.type == "taskGroup")
	{
		emit([project, doc.label], null);
	}
}