peeq.prototype.editable =
{
	main: function() 
	{
		$(".editable").editable(function(value, settings) {
			peeq.editable.save({"label": value}, function(response) {
				console.log(response);
			//	var ary_hash = document.location.hash.split("/");
			//	document.location.hash = ary_hash.slice(0, ary_hash.length - 1).push(value)
			});
			return value;
		}, { 
			indicator: "Saving...",
			tooltip: "Click to edit...",
			onblur: "ignore",
			submit: "Save",
			cancel: "Cancel",
			cssclass: "frm-editable",
			width: "none",
			height: "none"
		});
		
		$(".editable-textarea").editable(function(value, settings) {
			value = value.replace(new RegExp("\\n", "g"), "<br />");
			peeq.editable.save({"description": value}, function(response) {
				console.log(response);
			});
			return value;
		}, {
			type: "textarea",
			indicator: "Saving...",
			tooltip: "Click to edit...",
			onblur: "ignore",
			submit: "Save",
			cancel: "Cancel",
			cssclass: "frm-editable",
			width: "none",
			height: "none",
			data: function(value, settings) {
	        	return $.trim(value.replace(/<br[\s\/]?>/gi, "\n"));
	        }			
		});
	},
	save: function(data, callback) 
	{
		var ary_hash = document.location.hash.split("/"),
			slice_end = ary_hash.length > 2 : ary_hash.length - 1 : 2,	/* > 2 in state page and editing view, else in project page editing project */
			resource = "/project/" + ary_hash.slice(1, slice_end).join("/");
			
		peeq.api.request(resource, data, "POST", function(response) {
			if($.isFunction(callback)) 
			{
				callback(response);
			}
		});
	}
};