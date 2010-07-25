function(head, req)
{
	var ddoc     = this;
	var mustache = require("mustache");
	
	while(row = getRow())
	{
		// the view requires include_docs=true, so value will be null in this case
		var type  = row.doc.type;
		
		switch(type)
		{
			case "note":
				send(mustache.to_html(ddoc.templates.note, row.doc)+"\n");
				break;
			
			case "task":
				send(mustache.to_html(ddoc.templates.task, row.doc)+"\n");
				break;
		}
	}
}