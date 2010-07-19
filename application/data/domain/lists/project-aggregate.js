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
			current.tasks     = 0;
			current.views     = 0;
			current.completed = 0;
		}
		else
		{
			switch(type)
			{
				case "view":
					current.views++;
					break;
				
				case "task":
					current.tasks++;
					current.completed += row.value.value;
					break;
			}
			
			
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}