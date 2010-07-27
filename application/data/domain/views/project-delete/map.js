function(doc) 
{
	var project = doc._id.split("/").slice(0, 2).join("/");
	emit(project, null);
}
