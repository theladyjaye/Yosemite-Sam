peeq.prototype.editable =
{
	main: function() 
	{
		$(".editable").editable(function(value, settings) {
			peeq.editable.save({"label": value}, function(response) {
				if(response.ok)
				{
					var path = response.id.split("/").slice(1),
						ary_current_hash = document.location.hash.split("/");
						
					if(ary_current_hash.length > 3) // in state
					{
						path.push(ary_current_hash[3]);
					}
					
					document.location.hash = "/" + path.join("/");
				}
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
			height: "none",
			maxlength: 17
		});
		
		$(".editable-textarea").editable(function(value, settings) {
			value = peeq.utils.template.nl2br(value);
			peeq.editable.save({"description": value}, function(response) {
				//console.log(response);
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
		}).each(function() {
			$(this).html($(this).html().replace(/&lt;br \/&gt;/g, "<br />"));	
		});
	},
	save: function(data, callback) 
	{
		var ary_hash = document.location.hash.split("/"),
			slice_end = ary_hash.length > 2 ? ary_hash.length - 1 : 2;	/* > 2 in state page and editing view, else in project page editing project */
			resource = "/project/" + ary_hash.slice(1, slice_end).join("/");
			
		peeq.api.request(resource, data, "POST", function(response) {
			if($.isFunction(callback)) 
			{
				callback(response);
			}
		});
	}
};