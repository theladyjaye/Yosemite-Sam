function(head, req)
{
	var ddoc     = this;
	var mustache = require("mustache");
	
	var row;
	var result  = [];
	var current;
	
	while(row = getRow())
	{
		var type  = row.value.type;
		
		if(type == "view")
		{
			if(current)
			{
				send(mustache.to_html(ddoc.templates.view, current)+"\n");
			}			
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
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
			}
		}
	}
	
	//result.push(current);
	//send(JSON.stringify(result));
}