peeq.prototype.annotate = 
{
	main: function() 
	{
		$("#representation").annotatableImage(peeq.annotate.ui_note, {
			xPosition: "left",
			yPosition: "top"
		});

		$("#representation").delegate(".note .btn-save", "click", function(evt) {
			// save to db
			var $note = $(this).parents(".note"),
				$frm_note = $note.find(".frm-note");
			console.log($frm_note.serialize());				
			$frm_note.fadeOut();				
			return false;
		}).delegate(".note .btn-cancel", "click", function(evt) {
			$(this).parents(".note").fadeOut(function() {
				$(this).remove();
			});
			return false;
		}).delegate(".note .btn-delete", "click", function(evt) {
			$(this).parents(".note").fadeOut(function() {
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
			$(".note-num").css({"z-index": "2"});
		}, function() {
			$(".task-list-container").fadeOut();
			$(".ui-resizable-handle").show();
			$(".note-num").css({"z-index": "200002"});
		});
	},
	get_notes: function() 
	{
		return $("#representation img .note").serializeAnnotations();
	},
	add_notes: function() 
	{
		// dummy info
		var notes = [
			{x: 0.3, y: 0.4, width: 200, height: 300},
			{x: 0.65, y: 0.28, width: 500, height: 100},
			{x: 0.58, y: 0.31, width: 300, height: 40}
		];

		$("#representation img").addAnnotations(peeq.annotation.ui_note, notes, {
			xPosition: "left",
			yPosition: "top"
		});
	},
	get_next_note_count: function() 
	{
		if(!peeq.annotate.get_next_note_count.count)
		{
			peeq.annotate.get_next_note_count.count = 1;
		}
		return peeq.annotate.get_next_note_count.count++;
	},			
	ui_note: function()
	{
		var note = $("<div />", {
			"class": "note"			
		}).resizable({
			handles: "e, s, w, ne, se, sw",
			resize: function(evt, ui) 
			{
				$(this).find(".frm-note").css({
					"top": ui.size.height + 10
				});
			}
		}).draggable({
			containment: [5, $("#representation").position().top + 60, 1900, 800],
			handle: ".overlay"
		}).click(function() {
			$(".note").removeClass("active");
			$(this).addClass("active");
			$(this).find(".frm-note").fadeIn();
		});

		// drag initial size
		$("#representation").mousemove(function(evt) {
			var pos = note.position();
			note.width(evt.pageX - pos.left - $("#representation").position().left).height(evt.pageY - pos.top - 50);
			note.find(".frm-note").css({
				"top": note.height() + 10
			});
			return false;
		}).mouseup(function(evt) {
			$(this).unbind("mousemove");
		});

		var border = $("<div />", {
			"class": "border"
		}).appendTo(note);

		var overlay = $("<div />", {
			"class": "overlay"
		}).appendTo(note);

		var note_num = $("<span />", {
			"class": "note-num",
			"html": peeq.annotate.get_next_note_count(),
			"click": function(evt) {
				var $note = $(this).parents(".note"),
					$frm_note = $note.find(".frm-note");

				if($note.hasClass("minimized"))
				{
					var data = $note.data("yss");
					/*
					$note.removeClass("minimized").animate({
						"width": data.width,
						"height": data.height
					});
					*/
					$frm_note.fadeIn();
				}
				else
				{
					$note.data("yss", {
						"width": $note.width(),
						"height": $note.height()
					}); /*.addClass("minimized").animate({
						"width": 0,
						"height": 0
					});
					*/
					$frm_note.fadeOut();
				}
				return false;
			}
		}).appendTo(note);

		var frm = $("<form />", {
			"class": "frm-note",
			"action": "post"		
		}).appendTo(note);


		var frm_content = $("<div />", {
			"html": '<p><textarea name="desc"></textarea></p><p><label for="type">Type</label><select class="dd-type" name="type"><option value="HTML">HTML</option><option value="Flash">Flash</option></select></p><p><label for="assigned-to">Assigned To</label><select class="dd-assigned-to" name="assigned-to"><option value="bross">bross</option><option value="alincoln">alincoln</option></select><a href="#" class="btn-close-task">Close task</a></p><p class="group-cta"><a href="#" class="btn-save">Save</a><a href="#" class="btn-cancel">Cancel</a><a href="#" class="btn-delete">Delete</a></p>'
		}).appendTo(frm);

		return note;
	}
};
