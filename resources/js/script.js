Cufon.replace('.font-replace', { fontFamily: 'Vegur', hover: true });

$(function() {
	
	$("#view-table-list").delegate("li", "mouseenter", function() {
		$(this).addClass("over");
	}).delegate("li", "mouseleave", function() {
		$(this).removeClass("over");		
	}).delegate(".item-details", "click", function() {
		$(this).parents("li").toggleClass("expanded");
		return false;	
	});
	
	$(".fakefile input").keypress(function() {
		$(this).val("");
		return false;
	});
	
	$(".progress-bar").each(function() {
		var $progress_value = $(this).find(".progress-value .value"),
			percent = parseInt($progress_value.text());
			
		$(this).progressbar().find(".ui-progressbar-value").css("width", 0).animate({
			"width": percent + "%"
		}, 800, "easeOutQuad");
		
		if(percent > 0)
		{		
			$progress_value.countup({
				"end": percent,
				"time": percent/200,
				"step": 20
			});	
		}
	});
	
	
	$(".editable").editable( "save.php", {
		indicator: "Saving...",
		tooltip: "Click to edit..."
	});
	
	$(".editable-area").editable("save.php", {
		type: "textarea",
		cancel: "Cancel",
		submit: "Update",
		tooltip: "Click to edit..."
	});
	
		
	$("#view-body-editor-image").annotatableImage(ui_note, {
		xPosition: "left",
		yPosition: "top"
	});
	
	$("#view-body-editor-image").delegate(".note .btn-save", "click", function(evt) {
		// save to db
		var $note = $(this).parents(".note");
		console.log($note.find(".frm-note").serialize());
		return false;
	}).delegate(".note .btn-cancel", "click", function(evt) {
		$(this).parents(".note").remove();
		return false;
	}).delegate(".note .btn-delete", "click", function(evt) {
		$(this).parents(".note").remove();
		// delete from db
		return false;
	});
	
	// all modals are triggered with buttons containing the class btn-modal
	// the button must also contain the modal-view it is to show.
	// Example: <a href="#" class="btn-modal modal-view-mywindow">popup modal</a>
	//			<div class="modal modal-view-mywindow"></div>
	// 			Once clicked, this button will show the modal => modal-view-mywindow

	// Important: we are overriding the native alert(), and will display the modal in its place
	
	$(".btn-modal").click(function() {
		alert(get_modal_view(this));
		return false;
	});	
	
	$(".modal").addClass("jqmWindow").jqm({
		trigger: false,
		closeClass: "modal-close"
	});
	
	$(".modal form").submit(function() {
		return false;
	});
	
	$("#view-body-editor-image img").load(function() {
		$("#view-body-editor-image").css("width", $(this).width());
	});
	
	$(".view-editor .item-details").toggle(function() {
		$(".task-list-container").fadeIn();
		$(".ui-resizable-handle").hide(); // hide handles to prevent resizing
		$(".note-num").css({"z-index": "2"});
	}, function() {
		$(".task-list-container").fadeOut();
		$(".ui-resizable-handle").show();
		$(".note-num").css({"z-index": "200002"});
	});
	
	$(".task-list-container .btn-close-task-list").click(function() {
		$(".task-list-container").fadeOut();
	});
	
	$("form[name=frm-add-project]").submit(function() {
		var $this = $(this), 
			label = $this.find("input[name=label]").val(),
			id = gen_id_from_label(label),
		 	serialized = form_serialize($this),
			query_string = serialized + "&id=" + id;

		var params = {};
		query_string.replace(/([^=&]+)=([^&]*)/g, function(match, key, value) {
			params[unescape(key)] = value.replace(/(\+)/g, " ");
		});
		
		if(Validation.add_project($this)) {
			api("project/" + id, params, "PUT", function(data) {
				if(data.ok)
				{
					console.log("success");
				}
				else
				{
					console.log("failed");
				}
			});
		}
		return false;
	});
});

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

var Validation = {
	regexp: {
		label: /(\w|\d|_|-){2,}/ // at least 2 (word, digit, _, -)
	},
	add_project: function($frm) {
		var label = $frm.find("input[name=label]").val();
//		 	description = $frm.find("textarea[name=description]").val();

		return Validation.regexp.label.test(label);		
	}
};

function form_serialize($frm) 
{
	var serialized = $frm.serialize();
	
	for(var i in serialized)
	{
		serialized[i] = $.trim(serialized[i]);
	}
	
	return serialized;
}

function gen_id_from_label(label)
{
	// replace underscores and spaces to hypens
	return label.replace(/(_|\s)/g, "-").toLowerCase();
}

function get_notes()
{
	$("#view-body-editor-image .note").serializeAnnotations();
}

function add_notes() 
{
	var notes = [
		{x: 0.3, y: 0.4, width: 200, height: 300},
		{x: 0.65, y: 0.28, width: 500, height: 100},
		{x: 0.58, y: 0.31, width: 300, height: 40}
	];
	
	$("#view-body-editor-image").addAnnotations(ui_note, notes, {
		xPosition: "left",
		yPosition: "top"
	});
}

function get_next_note_count() {
	if(!get_next_note_count.count)
	{
		get_next_note_count.count = 1;
	}
	return get_next_note_count.count++;
}

function ui_note()
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
		containment: [0, $("#view-body-editor").position().top, 1900, 800],
		handle: ".overlay"
	}).click(function() {
		$(".note").removeClass("active");
		$(this).addClass("active");
	})
	
	// drag initial size
	$("#view-body-editor-image").mousemove(function(evt) {
		var pos = note.position();
		note.width(evt.pageX - pos.left).height(evt.pageY - pos.top - 200);
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
		"html": get_next_note_count(),
		"click": function(evt) {
			var $note = $(this).parents(".note");
			
			if($note.hasClass("minimized"))
			{
				var data = $note.data("yss");
				$note.removeClass("minimized").animate({
					"width": data.width,
					"height": data.height
				});
			}
			else
			{
				$note.data("yss", {
					"width": $note.width(),
					"height": $note.height()
				}).addClass("minimized").animate({
					"width": 0,
					"height": 0
				})
			}
		}
	}).appendTo(note);
	
	var frm = $("<form />", {
		"class": "frm-note",
		"action": "post",		
	}).appendTo(note);
	
	
	var frm_content = $("<div />", {
		"html": '<p><textarea name="desc"></textarea></p><p><label for="type">Type</label><select class="dd-type" name="type"><option value="HTML">HTML</option><option value="Flash">Flash</option></select></p><p><label for="assigned-to">Assigned To</label><select class="dd-assigned-to" name="assigned-to"><option value="bross">bross</option><option value="alincoln">alincoln</option></select><a href="#" class="btn-close-task">Close task</a></p><p class="group-cta"><a href="#" class="btn-save">Save</a><a href="#" class="btn-cancel">Cancel</a><a href="#" class="btn-delete">Delete</a></p>'
	}).appendTo(frm);
	
	
	return note;
}

function get_modal_view(btn)
{
	var regExp = /modal-view-(.*)/;
	var class_names = $(btn)[0].className;
	var matches = class_names.match(regExp);
	return (matches.length) ? "." + matches[0] : "";
}

/* -- Overrides -- */
function alert(view) 
{
	$(".modal" + view).jqmShow();
}