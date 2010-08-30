peeq.prototype.api =
{
	successCallbackHandler: function(callback, data)
	{
	    if($.isFunction(callback)) {
			callback(data);
		}
	},
	createForm: function(action, $original_form, successCallback)
	{
	    var id = "frm-ajax-on-demand";
	    
	    // remove form if it currently exists
	    $("#" + id).remove();
	    
	    var $frm = $("<form />", {
	        id: id,
	        method: "post",
	        action: action,
	        enctype: "multipart/form-data"	        
	    });
	    
	    $original_form.find("input").each(function() {
			$("<input />", {
	            type: "hidden",	
	            name: $(this).attr("name"),
	            val: $(this).val()
	        }).appendTo($frm);
		}).end().find("textarea").each(function() {
			$("<textarea />", {	            
	            name: $(this).attr("name"),
	            html: $(this).val()
	        }).appendTo($frm);
		});
				
	    $frm.appendTo("body");
	    
	    return $frm;
	},
	request: function(resource, data, method, successCallback, isUpload)
	{
		var api_path = "http://yss.com/api",
			isUpload = isUpload || false;
		
		if(resource)
		{	
		    var handler = api_path + resource;

            // if uploading file
		    if(isUpload)
		    {
		        var $frm = peeq.api.createForm(handler, data);
		        $frm.submit(function() {
		           $.post(handler, $frm.serialize(), function(data) {
		               $frm.remove();
		               peeq.api.successCallbackHandler(successCallback, data);
		           })
		           return false; 
		        }).submit();			       
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
   					type: "POST",
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