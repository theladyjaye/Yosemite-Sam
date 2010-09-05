peeq.prototype.annotate = 
{
	main: function() 
	{
		$("#representation").annotatableImage(peeq.annotate.ui_annotation, {
			xPosition: "left",
			yPosition: "top"
		});
		
		$("#representation").delegate(".annotation .btn-save", "click", function(evt) {
			// save to db
			var $annotation = $(this).parents(".annotation"),
				$frm_annotation = $annotation.find(".frm-annotation"),
				obj_serialized = peeq.utils.querystring_to_object($frm_annotation.serialize()),
				annotation_props = $annotation.serializeAnnotation();
			
			
			
			// clean up
			// annotation_props => obj_serialized
			obj_serialized = $.extend(obj_serialized, annotation_props);
			
			// estimate
			obj_serialized.estimate = (obj_serialized.estimate != "") ? obj_serialized.estimate + " " + obj_serialized.estimate_time : "";
			// priority
			obj_serialized.priority = (obj_serialized.priority == "on") ? 1 : 0
			// status
			obj_serialized.status = (obj_serialized.status == "on") ? 1 : 0
			
			obj_serialized_estimate_time = null;	
			$frm_annotation.fadeOut();				
			
			var id = $annotation.data("annotation-id"); // if exists then updating
			var method = "POST";
			if(!id) // create new, b/c id does not exist
			{
				var hash = document.location.hash.split("/");
				hash = hash.slice(1, hash.length - 1).join("/");
				id = "project/" + hash + "/annotations";
				method = "PUT";
			}
			peeq.api.request("/" + id, obj_serialized, method, function(response) {
				if(response.ok)
				{
					$annotation.data("annotation-id", response.id);
				}
			});
			
			return false;
		}).delegate(".annotation .btn-cancel", "click", function(evt) {
			$(this).parents(".annotation").find(".annotation-num").trigger("click", true);
			return false;
		}).delegate(".annotation .btn-delete", "click", function(evt) {
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
		});

/*
		$("#representation img img").load(function() {
			$("#representation img").css("width", $(this).width());
		});
*/
		$(".view-editor .item-details").toggle(function() {
			$(".task-list-container").fadeIn();
			$(".ui-resizable-handle").hide(); // hide handles to prevent resizing
			$(".annotation-num").css({"z-index": "2"});
		}, function() {
			$(".task-list-container").fadeOut();
			$(".ui-resizable-handle").show();
			$(".annotation-num").css({"z-index": "200002"});
		});
	},
	get_annotations: function() 
	{
		return $("#representation .annotation").serializeAnnotations();
	},
	add_annotations: function(annotations) 
	{
		// dummy info
		/*
		var annotations = [
			{x: 0.3, y: 0.4, width: 200, height: 300},
			{x: 0.65, y: 0.28, width: 500, height: 100},
			{x: 0.58, y: 0.31, width: 300, height: 40}
		];

		*/

		console.log(annotations);

		$("#representation").addAnnotations(peeq.annotate.ui_annotation, annotations, {
			xPosition: "left",
			yPosition: "top"
		}).trigger("mouseup");
		
	},
	get_next_annotation_count: function() 
	{
		return $("#representation .annotation").length ? parseInt($("#representation .annotation:last .annotation-num").text()) + 1 : 1;
	},			
	ui_annotation: function(options)
	{
		var defaults = {
			id: "",
			context: "",
			description: "",
			height: 0,
			width: 0,
			label: "",
			type: "note",
			x: null,
			y: null
		};

		options = $.extend(defaults, options);
		if(options._id)
		{
			options.id = options._id;
			delete options._id;
			options.type_class = options.type == "note" ? "type-note" : "type-task";
		}
		
		console.log(options);
		
		// minimize all current annotations
		$("#representation").find(".annotation").each(function() {
			$(this).find(".annotation-num").trigger("click", true);
		});
		
		var annotation = $("<div />", {
			"class": "annotation " + options.type_class
		}).resizable({
			handles: "e, s, w, ne, se, sw",
			resize: function(evt, ui) 
			{
				$(this).find(".frm-annotation").css({
					"top": ui.size.height + 10
				});
			}
		}).draggable({
			containment: [5, $("#representation").position().top + 60, 1900, $("#representation").height() + 60],
			handle: ".overlay"
		}).click(function() {
			$(".annotation").removeClass("active");
			$(this).addClass("active");
			$(this).find(".frm-annotation").fadeIn();
		});

		// drag initial size
		$("#representation").mousemove(function(evt) {
			var pos = annotation.position();
			annotation.width(evt.pageX - pos.left - $("#representation").position().left).height(evt.pageY - pos.top - 50);
			annotation.find(".frm-annotation").css({
				"top": annotation.height() + 10
			});
			return false;
		}).mouseup(function(evt) {
			$(this).unbind("mousemove");
		});

		var border = $("<div />", {
			"class": "border"
		}).appendTo(annotation);

		var overlay = $("<div />", {
			"class": "overlay"
		}).appendTo(annotation);
		
		var annotation_id = peeq.annotate.get_next_annotation_count();

		var annotation_num = $("<span />", {
			"class": "annotation-num",
			"html": annotation_id,
			"click": function(evt, isForceMinimize) {
				var $annotation = $(this).parents(".annotation"),
					$frm_annotation = $annotation.find(".frm-annotation");

				if(isForceMinimize || !$annotation.hasClass("minimized"))
				{				
					if(!$annotation.hasClass("minimized"))
					{
						$annotation.data("yss", {
							"width": $annotation.width(),
							"height": $annotation.height()
						});
					}
					
					$annotation.addClass("minimized");
					
					$frm_annotation.fadeOut();
				}
				else
				{
					var data = $annotation.data("yss");
					
					// minimize all others
					$("#representation").find(".annotation").each(function() {
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
		}).appendTo(annotation);

		var frm = $("<form />", {
			"class": "frm-annotation",
			"action": "post"		
		}).appendTo(annotation);

		if(options.height)
		{
			frm.css({
				"top": 10 + options.height
			});
		}

		var frm_content = $("<div />", {
			"html": '<div class="type"><a href="#" class="btn btn-note selected"><span class="icon icon-note"></span></a><a href="#" class="btn btn-task"><span class="icon icon-task"></span></a><input type="radio" value="note" name="type" checked="checked" class="visuallyhidden" /><input type="radio" value="task" name="type" class="visuallyhidden" /></div><p class="field"><a href="#" class="btn btn-priority" title="Low Priority">!</a><input type="checkbox" name="priority" class="visuallyhidden"/><input type="text" name="label" value="' + options.label + '" /><label for="label">Title</label></p><p class="field"><textarea name="description">' + options.description + '</textarea><label for="description">Description</label></p><div class="task-fields"><p><label for="context">Context</label><select id="dd-context-' + annotation_id + '" class="dd-context" name="context"><option value="HTML">HTML</option><option value="Flash">Flash</option></select></p><p><label for="assigned_to">Assigned To</label><select id="dd-assigned-to-' + annotation_id + '" class="dd-assigned-to" name="assigned_to"><option value="bross">bross - Flash Developer | USA</option><option value="alincoln">alincoln - Project Manager | BLITZ</option></select><a href="#" class="btn btn-status status-close">Close Task</a><input type="checkbox" name="status" class="visuallyhidden" /></p><p class="field"><label for="estimate">Estimate</label><input type="text" name="estimate" maxlength="4" /> <select id="dd-estimate-' + annotation_id + '" class="dd-estimate" name="estimate_time"><option value="hours">hours</option><option value="days">days</option><option value="weeks">weeks</option></select></p></div><p class="group-cta"><a href="#" class="btn btn-save">Save</a><a href="#" class="btn btn-cancel">Cancel</a><a href="#" class="btn btn-delete">Delete</a></p>'
		}).appendTo(frm);

		frm.find(".type .btn-note").click(function() {
			var $annotation = $(this).parents(".annotation");
			
			$(this).addClass("selected");
			frm.find("input[name=type][value=note]").click();
			frm.find(".task-fields").hide();
			$annotation.removeClass("type-task").addClass("type-note");

			$annotation.find(".type .btn-task").removeClass("selected");
			return false;
		});
		
		frm.find(".type .btn-task").click(function() {
			var $annotation = $(this).parents(".annotation");
			
			$(this).addClass("selected");
			frm.find("input[name=type][value=task]").click();
			frm.find(".task-fields").show();
			$annotation.removeClass("type-note").addClass("type-task");
			
			$annotation.find(".type .btn-note").removeClass("selected");
			
			return false;
		});
		
		if(options.type == "task")
		{
			frm.find(".type .btn-note").click();
		}
		

		frm.find(".btn-priority").click(function() {
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
		
		if(options.priority && options.priority == 1)
		{
			frm.find(".btn-priority").click();
		}
				
		frm.find(".btn-status").click(function() {
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
		
		if(options.status && options.status == 1)
		{
			frm.find(".btn-status").click();
		}
		
		frm.find("input[name=estimate]").numeric(".");

		//console.log(frm.find(".btn-status"));

/*
		frm.find('select').selectmenu({
			width: 150,
			format: function(text)
			{
				//console.log(text);
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
*/

		return annotation;
	}
};
