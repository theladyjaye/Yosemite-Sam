function(head, req)
{
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		var type  = row.value.type;
		
		if(type == "state")
		{
			if(current)
				result.push(current)
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
			
		}
		else
		{
			switch(type)
			{
				case "task":
					current.tasks.total++;
					current.tasks.completed += row.value.value;
					break;
			}
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}