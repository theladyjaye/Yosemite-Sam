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
		
		if(type == "state")
		{
			if(current)
			{
				send(mustache.to_html(ddoc.templates.states, current)+"\n");
			}
				
			current           = row.value;
			current.tasks     = {completed:0, total:0};
			current.path	  = current._id.split("/").slice(1).join("/");
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
	
	send(mustache.to_html(ddoc.templates.states, current)+"\n");
}
