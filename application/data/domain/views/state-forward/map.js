function(doc) 
{
	var view = doc._id.split("/").slice(0, 4).join("/");
	emit(view, null);
}
