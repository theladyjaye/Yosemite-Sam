function(head, req)
{
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		var type  = row.value.type;
		
		if(type == "project")
		{
			if(current)
				result.push(current)
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
			current.views     = 0;
		}
		else
		{
			switch(type)
			{
				case "view":
					current.views++;
					break;
				
				case "task":
					current.tasks.total++;
					current.tasks.completed += row.value.value;
					break;
			}
		}
	}
	
	send(JSON.stringify(result));
}