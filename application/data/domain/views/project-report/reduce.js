function(keys, values,rereduce) 
{
	out = {"count": 0, "complete":0}
	
	if(values[0].description)
		return values[0];
		
	for(var obj in values)
	{
		out.count    += values[obj].count;
		out.complete += values[obj].complete;
	}
	return out
}