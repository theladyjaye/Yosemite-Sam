function(head, req)
{
	var row;
	var rowIndex = 1;
	var result;
	
	while(row = getRow())
	{
		if(rowIndex % 2 == 0)
		{
			var doc = {};
			doc.percentComplete = result;
			
			for(var key in row.value)
			{
				doc[key] = row.value[key];
			}
			
			send(JSON.stringify(doc))
		}
		else
		{
			result = Math.round((row.value.complete/row.value.count) * 100) / 100;
		}
		
		rowIndex++;
	}
}