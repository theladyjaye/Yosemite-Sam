peeq.prototype.forms = 
{
	main: function() 
	{
		// setup submit event
		$("#main").delegate("form .btn-submit", "click", function() {
			var id = $(this).parents("form").attr("id"), // frm-project-add
				path = id.split("-");
				
			if(path[2] == "delete")
			{
				path[2] = "remove";
			}
			
			peeq.forms[path[1]][path[2]](id); // peeq.forms.project.add()
			return false;
		});
	},	
	project:
	{
		add: function(id)
		{		
			peeq.forms.project.submit($("#" + id), "PUT", function(response) {
				document.location.reload(true); // reload from server
			});	
		},
		remove: function(id)
		{
			peeq.forms.project.submit($("#" + id), "DELETE", function(response) {
				if(response.ok)
				{
					// redirect to projects
					document.location.href = "/";
				}
			});
		},
		submit: function($form, method, callback) 
		{
			var id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val());
			peeq.api.request("/project/" + id, $form.serialize(), method || "POST", function(response) {
				if($.isFunction(callback)) 
				{
					callback(response);
				}
			});
		}
	},
	view:
	{
		add: function(id)
		{		
			peeq.forms.view.submit($("#" + id), "PUT", function(response) {
				document.location.reload(true); // reload from server
			});	
		},
		remove: function(id)
		{
			var $form = $("#" + id), 
				id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val()),
				path = peeq.forms.utils.get_pathname(1, 2);
			peeq.api.request("/project/" + path + "/" + id, {}, "DELETE", function(response) {
				if(response.ok)
				{
					// redirect to projects
					document.location.href = "/";
				}
			});
		},
		submit: function($form, method, callback) 
		{
			var id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val()),
				path = peeq.forms.utils.get_pathname();
			peeq.api.request("/project/" + path + "/" + id, $form, method || "POST", function(response) {
				if($.isFunction(callback)) 
				{
					callback(response);
				}
			}, true);
		}
	},
	state:
	{
		add: function(id)
		{		
			peeq.forms.state.submit($("#" + id), "PUT", function(response) {
				//console.log(response, "state add");
				document.location.reload(true); // reload from server
			});	
		},
		remove: function(id)
		{
			peeq.forms.state.submit($("#" + id), "DELETE", function(response) {
				if(response.ok)
				{
					// redirect to projects
					document.location.href = "/";
					//console.log('deleted state');
				}
			});
		},
		submit: function($form, method, callback) 
		{
			var id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val()),
				path = peeq.forms.utils.get_pathname(1, 3);
			peeq.api.request("/project/" + path + "/" + id, $form, method || "POST", function(response) {
				if($.isFunction(callback)) 
				{
					callback(response);
				}
			}, true);
		}
	},
	attachment: 
	{
		add: function(id) 
		{
			peeq.forms.attachment.submit($("#" + id), "PUT", function(response) {
				// console.log(response, "attachment added");
				document.location.reload(true); // reload from server
			});
		},
		remove: function(id)
		{
			var $form = $("#" + id),
				id = encodeURIComponent($form.find("[input[name=id]").val()),
				path = peeq.forms.utils.get_pathname();
			peeq.api.request("/project/" + path + "/attachment/" + id, $form.serialize(), "DELETE", function(response) {
				if(response.ok)
				{
					// redirect to projects
					// document.location.href = "/";
					document.location.reload(true); // reload from server
				}
			});
		},
		submit: function($form, method, callback)
		{
			var id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val()),
				path = peeq.forms.utils.get_pathname();
			peeq.api.request("/project/" + path + "/attachment/" + id, $form, method || "POST", function(response) {
				if($.isFunction(callback)) 
				{
					callback(response);
				}
			}, true);
		},
		update: function(form_id)
		{
			var id = peeq.forms.utils.get_pathname(1),
				$form = $("#" + form_id);

			peeq.api.request("/project/" + id, $form, "POST", function(response) {
				document.location.reload(true);
			}, true);
		}
	},
	utils:
	{
		gen_id_from_label: function(label)
		{
			// replace underscores and spaces to hypens
			return label.replace(/(_|\s)/g, "-").toLowerCase();
		},
		get_pathname: function(slice_start, slice_end)
		{	
			var ary_hash = document.location.hash.split("/");
			return ary_hash.slice(slice_start || 1, slice_end || ary_hash.length).join("/");			
		}
	}
};