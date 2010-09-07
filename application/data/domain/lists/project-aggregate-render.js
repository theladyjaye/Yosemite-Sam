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
			    // no attachments
			    if(!current.attachments.length)
			    {
			        current.attachments.push({content_type: "image/png", "path": "/resources/img/no-attachment.png"});
			    }
			    
				send(mustache.to_html(ddoc.templates.project, current)+"\n");
			}
			
			current             = row.value;
			current.tasks       = {completed:0, total:0};
			current.views       = 0;
			current.attachments = [];
			current.path	    = current._id.split("/").slice(1).join("/");
			
			var created_date    = new Date(current.created_at.replace(/\+\d*/, ""));
			current.created_at  = (created_date.getMonth() + 1) + "/" + created_date.getDate() + "/" + created_date.getFullYear();
		}
		else
		{
			switch(type)
			{
				case "attachment":
					current.attachments.push({"content_type":row.value.content_type, "path":row.value.path, "label":row.value.label});
					break;
						
				case "view":
					current.views = current.views + 1;
					break;
				
				case "task":
					current.tasks.total = current.tasks.total + 1;
					current.tasks.completed =  current.tasks.completed + row.value.value;
					break;
			}
		}
	}
	
	send(mustache.to_html(ddoc.templates.project, current)+"\n");
}