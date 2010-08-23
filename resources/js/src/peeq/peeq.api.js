peeq.prototype.api =
{
	successCallbackHandler: function(callback, data)
	{
	    if($.isFunction(callback)) {
			callback(data);
		}
	},
	createForm: function(action, params, successCallback)
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
	    
	    var $field;
		    
	    for(var i in params)
	    {

	        $("<input>", {
	            type: "hidden",	
	            name: i,
	            val: params[i]
	        }).appendTo($frm);
	    }

	    $frm.appendTo("body");
	    
	    return $frm;
	},
	request: function(resource, data, method, successCallback)
	{
		var api_path = "http://yss.com/api";
		
		if(resource)
		{	
		    var handler = api_path + resource;

               // if POSTing
		    if(method && method.toUpperCase() == "POST")
		    {
		        var $frm = createForm(handler, data);
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
   				$.ajax({
   					beforeSend: function(xhr) {
   						xhr.setRequestHeader("X-HTTP-Method-Override", method || "GET");
   					},
   					type: "POST",
   					data: data, 
					dataType: "json",
   					contentType: "application/json; charset=utf-8",
   					url: handler,
   					success: function(data) {
   						peeq.api.successCallbackHandler(successCallback, data);
   					}
   				});	
   			}
		}
	}
};