function(doc) 
{
	if(doc.type == "task")
	{
		var state   = doc._id.split("/").slice(0, 4).join("/");
		
		emit(state, null);
	}
}
