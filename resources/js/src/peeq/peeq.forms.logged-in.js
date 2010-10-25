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
		validate_add: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
						peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users", $form.serialize(), "POST", function(response) {

						if(response.ok)
						{							
							var $add_user_container = $(".add-user-container"),
								$add_user_inactive_container = $(".add-user-inactive-container");
							
							// user needs to verify before we can add them
							$add_user_container.hide();
							$add_user_inactive_container.show();
							$add_user_inactive_container.find("strong").text(response.user.email);
							
							// add inactive user row
							// clone first row (w/ events and data) that is not logged in user (.is_me) and append to table
							// remove classes and put in .inactive
							var $row = $(".table-users tbody tr:not(.is_me):first").clone(true).attr("class", "inactive");

							// update row
							peeq.forms.user.update_row_in_table($row, response.user, response.user.username);
							
							// hide modal
							var timer = setTimeout(function() {
								$(".modal").jqmHide();																														
								clearTimeout(timer);								
							}, 2000);
						}
						else
						{
							if(response.errors)
							{					
								for(var i = 0, len = response.errors.length, $field; i < len; i++)
								{
									$field = $form.find("input[name=" + response.errors[i].key + "]").parents("li");
									$field.find(".icon-success").hide();
									$field.find(".icon-error").html(response.errors[i].message).show();
								}
							}
						}
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=firstname]",
							rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},	
						{
							elt: "input[name=lastname]",
							rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},				
						{
							elt: "input[name=email]",
							rule: $.validation.EMAIL,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Invalid email address.")
							}
						},
						{
							elt: "input[name=username]",
							rule: /^[\w\d]{4,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 4 characters.")
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		validate_edit: function(id, username)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.api.request("/account/" + peeq.utils.get_subdomain() + "/users/" + username, $form.serialize(), "POST", function(response) {

						if(response.ok)
						{
							var $row = $(".table-users").find(".username-" + username);

							// update row
							peeq.forms.user.update_row_in_table($row, response.user, username);

							// hide modal
							$(".modal").jqmHide();
						}
						else
						{
							if(response.errors)
							{					
								for(var i = 0, len = response.errors.length, $field; i < len; i++)
								{
									$field = $form.find("input[name=" + response.errors[i].key + "]").parents("li");
									$field.find(".icon-success").hide();
									$field.find(".icon-error").html(response.errors[i].message).show();
								}
							}
						}
					});
				}
			});
			
			if(!$form.data("validation")) // setup validation b/c not set up yet
			{
				$form.validation({
					"rules": [
						{
							elt: "input[name=firstname]",
							rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},	
						{
							elt: "input[name=lastname]",
							rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 2 characters.")
							}
						},				
						{
							elt: "input[name=email]",
							rule: $.validation.EMAIL,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Invalid email address.")
							}
						},
						{
							elt: "input[name=username]",
							rule: /^[\w\d]{4,}$/,
							onerror: function(evt, $elt)
							{
								$elt.parent().find(".icon-error").text("Must be at least 4 characters.")
							}
						}
					]
				});
			}
			
			$form.trigger("validate");	
		},
		validate_company_logo: function(id)
		{
			var $form = $("#" + id);
			
			$form.one("complete.validate", function(evt, is_valid) {				
				if(is_valid)
				{
					peeq.api.request("/account/" + peeq.utils.get_subdomain(), $form, "POST", function(response) {						
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
							elt: "input[name=logo]",
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
			peeq.forms.user.validate_add(id);		
		},
		edit: function(id)
		{
			var $frm = $("#" + id),
				username = $frm.find("input[name=username]").data("original");
			
			// clear original
			$frm.find("input[name=username]").data("original", "");
			
			peeq.forms.user.validate_edit(id, username);
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
					$(".settings").find(".msg-password-changed").fadeIn().delay(5000).fadeOut();
				}
				// hide modal
				$(".modal").jqmHide();
			});
		},
		updatecompanylogo: function(id)
		{
			peeq.forms.user.validate_company_logo(id);	
		},
		update_row_in_table: function($row, user, username)
		{		
			// update row
			$row.removeClass("username-" + username).addClass("username-" + username);
			$row.find(".table-column-name").text(user.lastname + ", " + user.firstname);
			$row.find(".table-column-username").text(user.username);
			$row.find(".table-column-email").text(user.email);
			$row.find(".table-column-admin").text((user.level >= 7) ? '<td class="table-column-admin"><span class="icon icon-success ir">admin</span></td>' : '');
			$row.find(".table-column-delete a").attr("href", "#" + username);
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