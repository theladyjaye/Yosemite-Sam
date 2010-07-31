function(doc) 
{
	var view = doc._id.split("/").slice(0, 3).join("/");
	emit(view, null);
}
