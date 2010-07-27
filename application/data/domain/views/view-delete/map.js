function(doc) 
{
	switch(doc.type)
	{
		case 'view':
		case 'state':
		case 'task':
		case 'note':
			var key = doc._id.split("/").slice(0, 3).join("/");
			emit(key, null);
			break;
	}
}
