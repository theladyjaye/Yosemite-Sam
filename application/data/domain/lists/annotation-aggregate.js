function(head, req)
{
	var ddoc     = this;
	var result  = [];
	
	while(row = getRow())
	{
		// the view requires include_docs=true, so value will be null in this case
		//var type  = row.doc.type;
		
		result.push(row.doc);
	}
	
	send(JSON.stringify(result));
}