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
		validate: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.forms.project.submit($form, "PUT", function(response) {
						if(response.ok)
						{
							document.location.reload(true);
						}
						
						// peeq.forms.utils.reset($form);
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=label]",
							rule: /[\w\d- ]{2,}/,
							checkevent: "keypress",
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.");
							}
						}
					]
				});
			}
			
			$form.trigger("validate");				
		},
		add: function(id)
		{	
			peeq.forms.project.validate(id);
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
		validate: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.forms.view.submit($form, "PUT", function(response) {
						if(response.ok)
						{
							document.location.reload(true);
						}
						
						
						// peeq.forms.utils.reset($form);
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=label]",
							rule: /[\w\d- ]{2,}/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},
						{
							elt: "input[name=attachment]",
							rule: /.jpg$/,
							onerror: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").text("jpg only.").show();
								$field.find(".icon-success").hide();
							},
							onsuccess: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").hide();
								$field.find(".icon-success").show();
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		add: function(id)
		{	
			peeq.forms.view.validate(id);
		},
		remove: function(id)
		{
			var $form = $("#" + id), 
				id = peeq.forms.utils.gen_id_from_label($form.find("[input[name=label]").val()),
				path = peeq.forms.utils.get_pathname(1, 2);
			peeq.api.request("/project/" + path + "/" + id, {}, "DELETE", function(response) {
				if(response.ok)
				{
					$form.parents(".modal").find(".btn-modal-close").click();
					// redirect to views
					document.location.href = "/#/" + path;
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
		validate: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.forms.state.submit($form, "PUT", function(response) {
						if(response.ok)
						{
							document.location.reload(true);
						}
						
						
						// peeq.forms.utils.reset($form);
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=label]",
							rule: /[\w\d- ]{2,}/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},
						{
							elt: "input[name=attachment]",
							rule: /.jpg$/,
							onerror: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").text("jpg only.").show();
								$field.find(".icon-success").hide();
							},
							onsuccess: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").hide();
								$field.find(".icon-success").show();
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		add: function(id)
		{	
			peeq.forms.state.validate(id);
		},
		remove: function(id)
		{
			peeq.forms.state.submit($("#" + id), "DELETE", function(response) {
				if(response.ok)
				{
					// redirect to views
					var path = peeq.forms.utils.get_pathname(1, 3);
					$form.parents(".modal").find(".btn-modal-close").click();
					document.location.href = "/#/" + path;
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
		validate: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.forms.attachment.submit($form, "PUT", function(response) {
						if(response.ok)
						{
							document.location.reload(true);
						}
						
						
						// peeq.forms.utils.reset($form);
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=label]",
							rule: /[\w\d- ]{2,}/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},
						{
							elt: "input[name=attachment]",
							rule: /\.(jpg|png|doc|docx|pdf|xls|xlsx|swf|txt)$/,
							onerror: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").text("jpg, png, swf, txt, doc, pdf, xls only").show();
								$field.find(".icon-success").hide();
							},
							onsuccess: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").hide();
								$field.find(".icon-success").show();
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		validate_update: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					var project_id = peeq.forms.utils.get_pathname(1);

					peeq.api.request("/project/" + project_id, $form, "POST", function(response) {
						if(response.ok)
						{
							document.location.reload(true);

							// peeq.forms.utils.reset($form);
						}
					}, true);
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=attachment]",
							rule: /\.(jpg)$/,
							onerror: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").text("jpg only").show();
								$field.find(".icon-success").hide();
							},
							onsuccess: function(evt, $elt)
							{
								var $field = $elt.parents(".field");
								$field.find(".icon-error").hide();
								$field.find(".icon-success").show();
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		add: function(id) 
		{
			peeq.forms.attachment.validate(id);
		},
		remove: function(id)
		{
			var $form = $("#" + id),
				id = encodeURIComponent($form.find("[input[name=id]").val()),
				path = peeq.forms.utils.get_pathname();
			peeq.api.request("/project/" + path + "/attachment/" + id, $form.serialize(), "DELETE", function(response) {
				if(response.ok)
				{
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
			peeq.forms.attachment.validate_update(form_id);
		}
	},
	user:
	{
		add: function(id)
		{
			var $frm = $("#" + id);
			
			peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users", $frm.serialize(), "POST", function(response) {
			
				if(response.ok)
				{
					// add row
					var $row = $("<tr />").appendTo(".table-users tbody");
										
					// update row
					peeq.forms.user.update_row_in_table($row, response.user);
					
					// hide modal
					$(".modal").jqmHide();
				}
			});
		},
		edit: function(id)
		{
			var $frm = $("#" + id),
				username = $frm.find("input[name=username]").data("original");
			
			// clear original
			$frm.find("input[name=username]").data("original", "");
			
			peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users/" + username, $frm.serialize(), "POST", function(response) {
				
				if(response.ok)
				{
					var $row = $(".table-users").find(".username-" + username);
					
					// update row
					peeq.forms.user.update_row_in_table($row, response.user);
					
					// hide modal
					$(".modal").jqmHide();
				}
			});
		}, 
		remove: function(id)
		{
			var $form = $("#" + id),
				username = $form.find("[input[name=id]").val();
			peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users/" + username, $form.serialize(), "DELETE", function(response) {
				if(response.ok)
				{
					// remove row
					var $row = $(".table-users").find(".username-" + username).remove();
					// hide modal
					$(".modal").jqmHide();
				}
			});
		},
		changepassword: function(id)
		{
			var $form = $("#" + id),
				username = $(".modal-view-changepassword-user").attr("href").substr(1);
			peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users/" + username, $form.serialize(), "POST", function(response) {
				if(response.ok)
				{
					// hide modal
					$(".modal").jqmHide();
				}
			});
		},
		update_row_in_table: function($row, user)
		{		
			// update row
			$row.removeClass("username-" + username).addClass("username-" + username);
			$row.find(".table-column-name").text(user.lastname + ", " + user.firstname);
			$row.find(".table-column-username").text(user.username);
			$row.find(".table-column-email").text(user.email);
			$row.find(".table-column-admin").text((user.level >= 7) ? '<td class="table-column-admin"><span class="icon icon-success ir">admin</span></td>' : '');
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
		},
		reset: function($form)
		{
			/*
			// clear fields
			$form.trigger("reset.validate");
			// reset toggle form fields
			$form.find("input").trigger("reset.toggle_form_field");
			$form.find("textarea").trigger("reset.toggle_form_field");
			
			// close modal
			$form.parents(".modal").find(".btn-modal-close").click();
			*/
		}
	}
};