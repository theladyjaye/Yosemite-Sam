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
		
		if(type == "project")
		{
			if(current)
			{
				send(mustache.to_html(ddoc.templates.project, current)+"\n");
			}
			
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
}