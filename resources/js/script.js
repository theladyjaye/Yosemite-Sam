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
		
		$progress_value.countup({
			"end": percent,
			"time": percent/200,
			"step": 20
		});	
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
});

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
		handles: "n, e, s, w, ne, se, sw",
		resize: function(evt, ui) 
		{
			$(this).find(".frm-note").css({
				"top": ui.size.height + 10
			});
		}
	}).draggable({
		containment: [0, $("#view-body-editor").position().top, 1900, 800],
		handle: ".overlay"
	}).trigger("resize");
	
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
			$note.toggleClass("minimized");
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