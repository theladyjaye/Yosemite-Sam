function(head, req)
{
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		var type  = row.doc.type;
		
		if(type == "task")
		{
			if(current)
				result.push(current)
				
			current             = row.doc;
			current.view        = null;
			current.state       = null;
		}
		else
		{
			switch(type)
			{
				case "view":
					current.view = row.doc;
					break;
				
				case "state":
					current.state = row.doc;
					break;
			}
		}
	}
	
	result.push(current);
	send(JSON.stringify(result));
}