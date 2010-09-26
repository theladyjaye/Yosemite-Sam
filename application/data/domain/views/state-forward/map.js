function(doc) 
{
	if(doc.type == "taskGroup")
	{
		doc.tasks.forEach(function(task)
		{
			var taskState = task.split("/").slice(0, 4).join("/");
			emit(taskState, null);
		});
	}
	else
	{
		var state = doc._id.split("/").slice(0, 4).join("/");
		emit(state, null);
	}
}