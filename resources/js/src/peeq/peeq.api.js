peeq.prototype.api =
{
	successCallbackHandler: function(callback, data)
	{
	    if($.isFunction(callback)) {
			callback(data);
		}
	},
	do_ajax_upload: function(action, $original_form, successCallback)
	{
		var iframe = "iframe-ajax-on-demand";
	    
	    // remove iframe if it currently exists
	    $("#" + iframe).remove();
	
		var $iframe = $('<iframe name="' + iframe + '" id="' + iframe + '" style="display:none;width:0;height:0" src="http://blitz.yss.com/#/settings" target="_self" />');   
		
		$iframe.insertAfter("#" + $original_form.attr("id"));
		
		$original_form.attr("action", action).submit();
		
		$iframe.load(function() {
		/*	var response = $.parseJSON($("#" + iframe)[0].contentDocument.body.innerHTML);
			if(response.ok)
			{
				// CHANGE TO AJAX refresh
				//document.location.reload(true);
			}
		*/
			document.location.reload(true);
			$("#" + iframe).remove();
		});		
		
	    return false;
	},
	request: function(resource, data, method, successCallback, isUpload)
	{
		var api_path = "/api",
			isUpload = isUpload || false;
		
		if(resource)
		{	
		    var handler = api_path + resource;

            // if uploading file
		    if(isUpload)
		    {
		        peeq.api.do_ajax_upload(handler, data, successCallback);			       
		    }
		    else
		    {		    
				if(method == "PUT")
				{
					method = "POST";
				}
			 			
   				$.ajax({
   					beforeSend: function(xhr) {
   						xhr.setRequestHeader("X-HTTP-Method-Override", method || "GET");
   					},
   					type: /*method || */ "POST",
   					data: data, 
					dataType: "json",
   					url: handler,
   					success: function(response) {

   						peeq.api.successCallbackHandler(successCallback, response);
   					}
   				});	
   			}
		}
	}
};