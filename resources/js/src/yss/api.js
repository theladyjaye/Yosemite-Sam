(function(ns) 
{
	ns.api = 
	{
		uri: "http://yss.com/api",
		request: function(resource, data, method, successCallback)
		{
			if(resource)
			{			
				$.ajax({
					beforeSend: function(xhr) {
						xhr.setRequestHeader("X-HTTP-Method-Override", method || "GET");
					},
					type: "POST",
					data: JSON.stringify(data),
					contentType: "application/json; charset=utf-8",
					url: ns.api.uri + resource,
					async: false, /* ugh */
					success: function(data) {
						if($.isFunction(successCallback)) {
							successCallback($.parseJSON(data));
						}			
					}
				});	
			}
		}
	}	
})($.phui.yss);