function(head, req)
{
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		//send(JSON.stringify(row.value)+"\n\n");
		
		if(typeof row.value == "object")
		{
			if(current)
				result.push(current)
				
			current           = row.value;
			current.tasks     = 0;
			current.completed = 0;
		}
		else
		{
			current.tasks++;
			current.completed += row.value;
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}