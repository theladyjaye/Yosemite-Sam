function(doc)
{
	if(doc.type == "taskGroup")
	{
		var id = doc._id;
		
		if(doc.tasks)
		{
			doc.tasks.forEach(function(task)
			{
				var view  = task.split('/').slice(0, 3).join('/');
				var state = task.split('/').slice(0, 4).join('/');
				emit([id, 0], {"_id":task});
				emit([id, 1], {"_id":view});
				emit([id, 2], {"_id":state});
			});
		}
	}
}