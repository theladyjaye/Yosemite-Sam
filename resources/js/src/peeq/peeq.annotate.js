peeq.prototype.annotate = 
{
	config: {
		users: {},
		context_options: 
		{
			"Flash": "Flash",
			"HTML": "HTML",
			"PHP": "PHP",
			"dotnet": ".NET",
			"design": "Design",
			"general": "General"
		},
		task_groups: {}
	},
	main: function() 
	{
		var $representation = $("#representation");
		
		// set up annotations
		$representation.annotatableImage(peeq.annotate.ui_annotation, {
			xPosition: "left",
			yPosition: "top"
		});
		
		// save annotation
		$representation.delegate(".annotation .btn-save", "click", function(evt) {
			// save to db
			var $annotation = $(this).parents(".annotation"),
				$frm_annotation = $annotation.find(".frm-annotation"),
				label = $annotation.find("input[name=label]").val(),
				description = $annotation.find("textarea[name=description]").val(),
				obj_serialized = peeq.utils.querystring_to_object($frm_annotation.serialize()),
				annotation_props = $annotation.serializeAnnotation();
			
			// clean up
			// annotation_props => obj_serialized
			obj_serialized = $.extend(obj_serialized, annotation_props);
			obj_serialized.label = label || "No Title";
			obj_serialized.description = description;
			
			
			// estimate
			obj_serialized.estimate = (obj_serialized.estimate != "") ? obj_serialized.estimate + " " + obj_serialized.estimate_time : "";
			// priority
			obj_serialized.priority = (obj_serialized.priority == "on") ? 1 : 0
			// status
			obj_serialized.status = (obj_serialized.status == "on") ? 1 : 0
						
			// clean up form vars
			delete obj_serialized.estimate_time;	
				
			
			var id = $annotation.data("annotation-id"); // if exists then updating
			var hash = document.location.hash.split("/");
			hash = hash.slice(1, hash.length - 1).join("/");
			
			if(!id) // create new, b/c id does not exist
			{
				id = "project/" + hash + "/annotations";
				method = "PUT";
			}
			else // updating
			{
				id = "project/" + hash + "/" + peeq.utils.template.annotations.sanitize_id(id);
				method = "POST";
			}
			peeq.api.request("/" + id, obj_serialized, method, function(response) {
				if(response.ok)
				{
					$annotation.data("annotation-id", response.id);
					$annotation.addClass("annotation-" + peeq.utils.template.annotations.sanitize_id(response.id));

					if(obj_serialized.type == "task")
					{
						peeq.annotate.save_group($annotation, obj_serialized.task_groups, obj_serialized.new_group);
					}
				}
			});			
			return false;
		}).delegate(".annotation .btn-cancel", "click", function(evt) {  // cancel
			var $annotation = $(this).parents(".annotation");
			
			if(peeq.annotate.is_empty_annotation($annotation)) // no data => delete
			{
				$annotation.find(".btn-delete").click();
			}
			else // has data, just hide
			{
				$annotation.find(".annotation-num").trigger("click", true);
			}
			return false;
		}).delegate(".annotation .btn-delete", "click", function(evt) { // delete
			var $annotation = $(this).parents(".annotation");
			
			$annotation.fadeOut(function() {
				$(this).remove();
			});

			// get id
			var id = $annotation.data("annotation-id"); // if exists then saved annotation and can delete from db
			if(id) 
			{
				peeq.api.request("/" + id, {}, "DELETE", function(response) {
					console.log(response);
				});
			}

			return false;
		}).delegate(".tbl-related-tasks a", "click", function() { 	// task group deeplink
			var $this = $(this),
				ary_href = $this.attr("href").split(":"),
				ary_hash = document.location.hash.split(":");
				
				
			// deeplink in same state
			if(ary_href[0] == ary_hash[0])
			{
				peeq.annotate.deeplink_to_annotation(ary_href[1]);
				return false;
			}
		});
		
	},
	save_group: function($annotation, group_id, new_group_name)
	{
		// -1 => creating new group
		// 0  => no group
				
		var current_group = $annotation.data("group-id"),
			current_group_id = (current_group) ? current_group.split("/")[4] : "",
			annotation_id = $annotation.data("annotation-id"),
			project = document.location.hash.split("/")[1];
			
		if(group_id.indexOf("/") != -1)
		{
			group_id = group_id.split("/")[4];
		}
		
		//console.log(group_id, current_group_id);
				
		if(project)
		{		
			if(group_id == -1) // create new group
			{
				// create group and add task to group
				peeq.api.request("/project/" + project + "/group/task", {label: new_group_name, task_id: annotation_id}, "POST", function(response) {
					if(response.ok)
					{
						$annotation.data("group-id", response.id);
						
						// add new group to select
				
						// remove current custom menu dropdown
						peeq.annotate.cleanup();				
						
						// add new group label
						var $task_groups = $("#representation").find(".annotation .dd-task-groups");
						$("<option value='" + response.id + "'>" + new_group_name + "</option>").insertBefore($task_groups.find("option:last"));
						
						// change value to new group in custom menu						
						$annotation.find(".dd-task-groups option[value=" + response.id + "]").attr("selected", "selected");
						$annotation.find("input[name=new_group]").val("").hide();
						$annotation.find(".btn-see-related-tasks").show();
						
						// recreate custom menu
						$task_groups.selectmenu({
							width: 175
						});
					}
				});
			}		
			else if(current_group_id && group_id != current_group_id) // move task to different group
			{
				// delete task from group
				peeq.api.request("/project/" + project + "/group/task/" + current_group_id, {}, "DELETE", function(response) {				
					if(response.ok && group_id > 0)
					{
						// add task to group				
						peeq.api.request("/project/" + project + "/group/task/" + group_id + "/" + encodeURIComponent(annotation_id), {}, "POST", function(response) {
							$annotation.data("group-id", response.id);
						});
					}
				});
			}
			else if(group_id != current_group_id && group_id != 0)
			{
				// add task to group
				// ? gets 404				
				peeq.api.request("/project/" + project + "/group/task/" + group_id + "/" + encodeURIComponent(annotation_id), {}, "POST", function(response) {
					$annotation.data("group-id", response.id);
				});
			}
		}		
	},
	cleanup: function() 
	{
		// remove task group dropdowns
		$("ul[id^=dd-task-groups]").remove();
		$("a[id^=dd-task-groups]").remove();	
	},
	get_annotations: function() 
	{
		return $("#representation .annotation").serializeAnnotations();
	},
	add_annotations: function(annotations, deeplink_id) 
	{
		// dummy info
		/*
		var annotations = [
			{x: 0.3, y: 0.4, width: 200, height: 300},
			{x: 0.65, y: 0.28, width: 500, height: 100},
			{x: 0.58, y: 0.31, width: 300, height: 40}
		];
		*/
		
		$("#representation").addAnnotations(peeq.annotate.ui_annotation, annotations, {
			xPosition: "left",
			yPosition: "top"
		}).trigger("mouseup");	
	
		$(".annotation:last").trigger("deactivate").addClass("minimized");
	
		if(deeplink_id)
		{			
			peeq.annotate.deeplink_to_annotation(deeplink_id);
		}			
	},
	deeplink_to_annotation: function(deeplink_id)
	{
		var $deeplink_annotation = $("#representation .annotation-" + deeplink_id),
			top = parseInt($deeplink_annotation.css("top"));
	
		$deeplink_annotation.trigger("activate");
		window.scrollTo(0, top);
	},
	get_next_annotation_count: function() 
	{
		return $("#representation .annotation").length ? parseInt($("#representation .annotation:last .annotation-num").text()) + 1 : 1;
	},		
	is_empty_annotation: function($annotation) 
	{
		return $annotation.find("input[name=label]").val() == "" && !$annotation.data("annotation-id");
	},
	ui_annotation: function(options)
	{
		// DEFAULTS => OPTIONS
		var defaults = {
			id: "",
			context: "",
			description: "",
			height: 0,
			width: 0,
			label: "",
			type: "note",
			x: null,
			y: null,
			group: "none"
		};

		options = $.extend(defaults, options);
		if(options._id)
		{
			options.id = options._id;
			delete options._id;
		}				
		options.type_class = options.type == "note" ? "type-note" : "type-task";
		
		// END OPTIONS SETUP
		
		// variables
		var $representation = $("#representation");
			
		// minimize all current annotations
		$representation.find(".annotation").each(function() {
			var $this = $(this);
			
			// delete task if not saved and title is empty
			if(peeq.annotate.is_empty_annotation($this))
			{
				$this.find(".btn-delete").click();
			}
			else // otherwise minimize
			{
				$this.find(".annotation-num").trigger("click", true);
			}
		});
	
		// ANNOTATION CREATION
		
		// create annotation (container for everything)
		var $annotation = $("<div />", {
			"class": "annotation " + options.type_class + " annotation-" + peeq.utils.template.annotations.sanitize_id(options.id)
		}).resizable({ // resize annotation
			handles: "e, s, w, ne, se, sw",
			containment: 'parent',
			start: function(evt, ui)
			{
				$(this).trigger("deactivate");				
			},
			stop: function(evt, ui)
			{							
				$(this).find(".frm-annotation").css({
					"top": ui.size.height + 10
				});				
			}
		}).draggable({ // drag annotation
			containment: 'parent',
			handle: ".overlay",
			start: function(evt, ui)
			{
				$(this).trigger("deactivate");
			}
		}).mouseup(function() {
			$(this).addClass("active");
		}).bind("activate", function() {
			$(".annotation").removeClass("active").addClass("minimized");
			$(this).addClass("active").removeClass("minimized");
		}).bind("deactivate", function() {
			$(this).removeClass("active");
		});

		// drag initial size
		$representation.mousemove(function(evt) {
			var pos = $annotation.position();
			$annotation.width(evt.pageX - pos.left - $representation.offset().left).height(evt.pageY - pos.top - 50);
			$annotation.find(".frm-annotation").css({
				"top": $annotation.height() + 10
			});
			return false;
		}).mouseup(function(evt) {
			$(this).unbind("mousemove").unbind("mouseup");
		});

		// border elt
		var $border = $("<div />", {
			"class": "border"
		}).appendTo($annotation);

		// overlay elt
		var $overlay = $("<div />", {
			"class": "overlay",
			"click": function() {
				$(this).parents(".annotation").trigger("activate");
			}
		}).appendTo($annotation);
		
		// get next annotation id
		var annotation_id = peeq.annotate.get_next_annotation_count();

		// create annotation number
		var $annotation_num = $("<span />", {
			"class": "annotation-num",
			"html": annotation_id,
			"click": function(evt, isForceMinimize) {
				var $annotation = $(this).parents(".annotation"),
					$frm_annotation = $annotation.find(".frm-annotation");

				if(isForceMinimize || !$annotation.hasClass("minimized"))
				{				
					if(!$annotation.hasClass("minimized"))
					{
						$annotation.data("peeq", {
							"width": $annotation.width(),
							"height": $annotation.height()
						});
					}
					
					$annotation.addClass("minimized");
					
					//$frm_annotation.fadeOut();
				}
				else
				{
					var data = $annotation.data("peeq");
					// minimize all others
					$representation.find(".annotation").each(function() {
						if($(this) != $annotation)
						{
							$(this).find(".annotation-num").trigger("click", true);
						}
					});
					
					$annotation.removeClass("minimized").addClass("active").css({
						"width": data.width,
						"height": data.height
					});
					
					$frm_annotation.fadeIn();
				}
			
				return false;
			}
		}).appendTo($annotation);

		// create actual form
		var $frm = $("<form />", {
			"class": "frm-annotation",
			"action": "post"		
		}).appendTo($annotation);

		// create form content
		var $frm_content = $("<div />", {
			"html": '<div class="type"><a href="#" class="btn btn-note selected"><span class="icon icon-note"></span></a><a href="#" class="btn btn-task"><span class="icon icon-task"></span></a><input type="radio" value="note" name="type" checked="checked" class="visuallyhidden" /><input type="radio" value="task" name="type" class="visuallyhidden" /></div><div class="frm-annotation-container"><div class="frm-annotation-inner"><p class="field"><a href="#" class="btn btn-priority" title="Low Priority">!</a><input type="checkbox" name="priority" class="visuallyhidden"/><input type="text" name="label" value="' + options.label + '" /><label for="label">Title</label></p><p class="field"><textarea name="description">' + options.description + '</textarea><label for="description">Description</label></p><div class="task-fields"><p><label for="context">Context</label><select id="dd-context-' + annotation_id + '" class="dd-context" name="context"></select></p><p><label for="assigned_to">Assigned To</label><select id="dd-assigned-to-' + annotation_id + '" class="dd-assigned-to" name="assigned_to"></select><a href="#" class="btn btn-status status-close">Close Task</a><input type="checkbox" name="status" class="visuallyhidden" /></p><p class="field field-estimate"><label for="estimate">Estimate</label><input type="text" name="estimate" maxlength="4" /> <select id="dd-estimate-' + annotation_id + '" class="dd-estimate" name="estimate_time"><option value="hours">hours</option><option value="days">days</option><option value="weeks">weeks</option></select></p><p class="field field-groups"><label for="task-groups">Group</label><select id="dd-task-groups-' + annotation_id + '" class="dd-task-groups" name="task_groups"></select><input type="text" name="new_group" /><a href="#" class="btn-see-related-tasks">See Related Tasks &raquo;</a></p></div><p class="group-cta"><a href="#" class="btn btn-save">Save</a><a href="#" class="btn btn-cancel">Cancel</a><a href="#" class="btn btn-delete">Delete</a></p><div class="view-related-tasks"><a href="#" class="btn btn-back btn-back-to-form">Back</a><ol class="tbl-related-tasks"></ol></div></div></div>'
		}).appendTo($frm);
		
		// add context options
		peeq.utils.add_options_to_select(peeq.annotate.config.context_options, $frm.find(".dd-context"));
		// add user options
		peeq.utils.add_options_to_select(peeq.annotate.config.users, $frm.find(".dd-assigned-to"));
		// add task group options
		peeq.utils.add_options_to_select(peeq.annotate.config.task_groups, $frm.find(".dd-task-groups"));

		// type toggle: note
		$frm.find(".type .btn-note").click(function() {
			var $annotation = $(this).parents(".annotation");
			
			$(this).addClass("selected");
			$frm.find("input[name=type]:eq(1)").removeAttr("checked");
			$frm.find("input[name=type]:eq(0)").attr("checked", "checked");
			$frm.find(".task-fields").hide();
			$annotation.removeClass("type-task").addClass("type-note");

			$annotation.find(".type .btn-task").removeClass("selected");
			return false;
		});
		
		// type toggle: task
		$frm.find(".type .btn-task").click(function() {
			var $annotation = $(this).parents(".annotation");
			
			$(this).addClass("selected");
			$frm.find("input[name=type]:eq(0)").removeAttr("checked");
			$frm.find("input[name=type]:eq(1)").attr("checked", "checked");
			$frm.find(".task-fields").show();
			$annotation.removeClass("type-note").addClass("type-task");
			
			$annotation.find(".type .btn-note").removeClass("selected");
			
			return false;
		});
		
		// task group, create new group toggle		
		$frm.find(".dd-task-groups").change(function() {
			var $this = $(this),
				val = $this.val(),
				$new_group_field = $frm.find("input[name=new_group]"),
				$btn_see_related_tasks = $frm.find(".btn-see-related-tasks");
			
			if(val == 0)
			{
				$btn_see_related_tasks.hide();
			}
			else if(val == -1) 
			{
				$btn_see_related_tasks.hide();
				$new_group_field.show();
				
				var timer = setTimeout(function() {
					$new_group_field.focus();				
					clearTimeout(timer);
				}, 100);
			}
			else
			{
				$new_group_field.hide();
				$btn_see_related_tasks.show();
			}
		});
	
		// show related tasks view
		$frm.find(".btn-see-related-tasks").click(function() {
			
			var task_group = $frm.find(".dd-task-groups").val();
			
			peeq.api.request("/" + task_group, {}, "GET", function(response) {
				if(response.ok)
				{
					// populate related tasks
					var $tbl_related_tasks = $frm.find(".tbl-related-tasks"),
						related_task_lis = [], 
						related_task = "", 
						deeplink = [], 
						task_id = "",
						current_task_id = $frm.parents(".annotation").data("annotation-id"),
						len = response.tasks.length,
						max_len = 7;
										
					if(len > 0)
					{		
						for(var i = 0; i < len; i++)
						{
							if(i > (max_len - 1)) // reached visible max
							{
								related_task_lis.push('<li class="plus-more">plus <span class="count incomplete">' + (len - i) + '</span> more.</li>');
								break;
							}
							
							related_task = response.tasks[i];

							if(related_task._id != current_task_id)
							{							
								deeplink = related_task._id.split("/").slice(1);
								task_id = deeplink.pop();
								deeplink.push("annotate:" + task_id);
								deeplink = deeplink.join("/");
						
						
								related_task_lis.push('<li><a href="#/' + deeplink + '"><span class="title">' + peeq.utils.template.truncate(related_task.label, 50) + '</span><br />in ' + related_task.view.label + ' &raquo; ' + related_task.state.label + '</a></li>');
							}
						}						
					}
					
					if(related_task_lis.length < 1)
					{
						related_task_lis.push('<li class="none">No related tasks found.</li>');
					}
					
					$tbl_related_tasks.html(related_task_lis.join(""));
					
					// animate in
					$annotation.find(".frm-annotation-inner").animate({
						"left": -402
					}, 250, "easeOutQuad");
				}
			});			
		
			return false;
		});
		
		// back to form from related tasks view
		$frm.find(".btn-back-to-form").click(function() {
			$annotation.find(".frm-annotation-inner").animate({
				"left": 0
			}, 250, "easeOutQuad");
			
			return false;
		});
		
		// priority toggle
		$frm.find(".btn-priority").click(function() {
			var $this = $(this),
				$chkbox = $this.next("input[name=priority]"),
				priority = "Low";
							
			$this.toggleClass("checked");
			$chkbox.attr("checked", false);
						
			if($this.hasClass("checked"))
			{
				priority = "High";
				$chkbox.attr("checked", true);
			}
			
			$this.attr("title", priority + " Priority");
			
			return false;
		});
		
		// status toggle
		$frm.find(".btn-status").click(function() {
			var $this = $(this),
				$chkbox = $this.next("input[name=status]");
				
			if($this.hasClass("status-close"))
			{
				$this.removeClass("status-close").text("Reopen task");
				$chkbox.attr("checked", true);
			}
			else
			{
				$this.addClass("status-close").text("Close Task");
				$chkbox.attr("checked", false);
			}
			return false;
		});
		
		// make sure input for estimate is only numbers and .
		$frm.find("input[name=estimate]").numeric(".");


		// POPULATE FIELDS
		
		if(options.width && options.height)
		{
			$annotation.data("peeq", {
				"width": options.width,
				"height": options.height
			});
			
			// set the form 10px below the height of the annotation
			$frm.css({
				"top": 10 + parseInt(options.height)
			});	
		}
		
		// type of field (task|note)
		if(options.type == "task")
		{
			$frm.find(".type .btn-task").click();
			$frm.find(".task-fields").show();
		}
		
		// context for tasks
		if(options.context)
		{
			$frm.find(".dd-context option[value=" + options.context + "]").attr("selected", "selected");
		}
		
		// assigned to for tasks
		if(options.assigned_to)
		{
			$frm.find(".dd-assigned-to option[value=" + options.assigned_to + "]").attr("selected", "selected");
		}
		
		// estimate for tasks
		if(options.estimate)
		{
			var estimate_parts = options.estimate.split(" ");
			$frm.find("input[name=estimate]").val(estimate_parts[0]);
			$frm.find(".estimate_time option[value=" + estimate_parts[1] + "]");
		}
		
		// task group
		if(options.group)
		{		
			if(options.group == "none" || options.group < 1)
			{
				$frm.find(".btn-see-related-tasks").hide();
			}
			else
			{
				$frm.find(".dd-task-groups option[value=" + options.group + "]").attr("selected", "selected");
				$annotation.data("group-id", options.group);
			}
		}
		else
		{
			$frm.find(".btn-see-related-tasks").hide();
		}
		
		// priority
		if(options.priority && options.priority == 1)
		{
			$frm.find(".btn-priority").click();
		}
		
		// status
		if(options.status && options.status == "1")
		{
			$frm.find(".btn-status").click();
		}
	
		// set annotation id
		if(options.id)
		{
			$annotation.data("annotation-id", options.id);
		}


		// face melting custom dropdowns, for all except estimate b/c that one is not as wide
		$frm.find('select:not(.dd-estimate)').selectmenu({
			width: 175,
			format: function(text)
			{
				var newText = text;
				//array of find replaces
				var findreps = [
					{find:/^([^\-]+) \- /g, rep: '<span class="ui-selectmenu-item-header">$1</span>'},
					{find:/([^\|><]+) \| /g, rep: '<span class="ui-selectmenu-item-content">$1</span>'},
					{find:/([^\|><\(\)]+) (\()/g, rep: '<span class="ui-selectmenu-item-content">$1</span>$2'},
					{find:/([^\|><\(\)]+)$/g, rep: '<span class="ui-selectmenu-item-content">$1</span>'},
					{find:/(\([^\|><]+\))$/g, rep: '<span class="ui-selectmenu-item-footer">$1</span>'}
				];

				for(var i in findreps){
					newText = newText.replace(findreps[i].find, findreps[i].rep);
				}
				return newText;
			}
		});
		
		// estimate face melting custom dropdown
		$frm.find("select.dd-estimate").selectmenu({
			width: 95,
			menuWidth: 75
		});

		return $annotation;
	}
};
