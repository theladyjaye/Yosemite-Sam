function(doc)
{
	if(doc.type == "task" && doc.assigned_to != null)
	{
		emit(doc.assigned_to, null);
	}
}