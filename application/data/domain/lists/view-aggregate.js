function(head, req)
{
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		var type  = row.value.type;
		
		if(type == "view")
		{
			if(current)
				result.push(current)
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
			current.notes     = 0;
			current.states    = [];
			
		}
		else
		{
			switch(type)
			{
				case "state":
					current.states.push(row.value);
					break;
				
				case "task":
					current.tasks.total++;
					current.tasks.completed += row.value.value;
					break;
				
				case "note":
					current.notes.total++;
					break;
			}
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}