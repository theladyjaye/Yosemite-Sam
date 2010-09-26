function(doc) 
{
	// project/lucy-the-dog/login
	var view = doc._id.split("/").slice(0, 3).join("/");
	
	if(doc.type == "taskGroup")
	{
		doc.tasks.forEach(function(task)
		{
			var taskView = task.split("/").slice(0, 3).join("/");
			emit(taskView, null);
		});
	}
	else
	{
		emit(view, null);
	}
}
