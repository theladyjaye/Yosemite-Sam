function(doc)
{
	if(doc.type == "taskGroup")
	{
		if(doc.tasks)
		{
			doc.tasks.forEach(function(task)
			{
				emit(task, {"_id": task});
			});
		}
	}
}