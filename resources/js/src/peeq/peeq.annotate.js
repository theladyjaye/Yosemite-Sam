peeq.prototype.annotate = 
{
	main: function() 
	{
		$("#representation").annotatableImage(peeq.annotate.ui_annotation, {
			xPosition: "left",
			yPosition: "top"
		})
		
		$("#representation").delegate(".annotation .btn-save", "click", function(evt) {
			// save to db
			var $annotation = $(this).parents(".annotation"),
				$frm_annotation = $annotation.find(".frm-annotation");
			console.log($frm_annotation.serialize());				
			$frm_annotation.fadeOut();				
			return false;
		}).delegate(".annotation .btn-cancel", "click", function(evt) {
			$(this).parents(".annotation").fadeOut(function() {
				$(this).remove();
			});
			return false;
		}).delegate(".annotation .btn-delete", "click", function(evt) {
			$(this).parents(".annotation").fadeOut(function() {
				$(this).remove();
			});
			// delete from db
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
		return $("#representation img .annotation").serializeAnnotations();
	},
	add_annotations: function() 
	{
		// dummy info
		var annotations = [
			{x: 0.3, y: 0.4, width: 200, height: 300},
			{x: 0.65, y: 0.28, width: 500, height: 100},
			{x: 0.58, y: 0.31, width: 300, height: 40}
		];

		$("#representation img").addAnnotations(peeq.annotation.ui_annotation, annotations, {
			xPosition: "left",
			yPosition: "top"
		});
	},
	get_next_annotation_count: function() 
	{
		if(!peeq.annotate.get_next_annotation_count.count)
		{
			peeq.annotate.get_next_annotation_count.count = 1;
		}
		return peeq.annotate.get_next_annotation_count.count++;
	},			
	ui_annotation: function()
	{
		var annotation = $("<div />", {
			"class": "annotation"			
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

		var annotation_num = $("<span />", {
			"class": "annotation-num",
			"html": peeq.annotate.get_next_annotation_count(),
			"click": function(evt) {
				var $annotation = $(this).parents(".annotation"),
					$frm_annotation = $annotation.find(".frm-annotation");

				if($annotation.hasClass("minimized"))
				{
					var data = $annotation.data("yss");
					/*
					$annotation.removeClass("minimized").animate({
						"width": data.width,
						"height": data.height
					});
					*/
					$frm_annotation.fadeIn();
				}
				else
				{
					$annotation.data("yss", {
						"width": $annotation.width(),
						"height": $annotation.height()
					}); /*.addClass("minimized").animate({
						"width": 0,
						"height": 0
					});
					*/
					$frm_annotation.fadeOut();
				}
				return false;
			}
		}).appendTo(annotation);

		var frm = $("<form />", {
			"class": "frm-annotation",
			"action": "post"		
		}).appendTo(annotation);


		var frm_content = $("<div />", {
			"html": '<p class="field"><a href="#" class="btn btn-priority" title="Low Priority">!</a><input name="label"></input><label for="label">Title</label></p><p class="field"><textarea name="description"></textarea><label for="description">Description</label></p><p><label for="type">Type</label><select class="dd-type" name="type"><option value="HTML">HTML</option><option value="Flash">Flash</option></select></p><p><label for="assigned_to">Assigned To</label><select class="dd-assigned-to" name="assigned_to"><option value="bross">bross</option><option value="alincoln">alincoln</option></select><a href="#" class="btn btn-close-task">Close task</a></p><p class="group-cta"><a href="#" class="btn btn-save">Save</a><a href="#" class="btn btn-cancel">Cancel</a><a href="#" class="btn btn-delete">Delete</a></p>'
		}).appendTo(frm);

		frm.find(".btn-priority").click(function() {
			var $this = $(this),
				priority = "Low";
				
			$this.toggleClass("checked");
			if($this.hasClass("checked"))
			{
				priority = "High";
			}
			
			$this.attr("title", priority + " Priority");
			return false;
		});

		
		return annotation;
	}
};
