function api(resource, data, method, successCallback)
{
	var url = "http://yss.com/api/" + resource;

	$.ajax({
		beforeSend: function(xhr) {
			xhr.setRequestHeader("X-HTTP-Method-Override", method || "GET");
		},
		type: "POST",
		data: JSON.stringify(data),
		contentType: "application/json; charset=utf-8",
		url: url,
		async: false, /* ugh */
		success: function(data) {
			if($.isFunction(successCallback)) {
				successCallback(data);
			}			
		}
	});
}