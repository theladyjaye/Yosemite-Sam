Cufon.replace('.font-replace', { fontFamily: 'Vegur', hover: true });


$(function() {
	
	$("#view-table-list").delegate("li", "mouseenter", function() {
		$(this).addClass("over");
	}).delegate("li", "mouseleave", function() {
		$(this).removeClass("over");		
	}).delegate(".item-details", "click", function() {
		$(this).parents("li").toggleClass("expanded");	
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
		$(".task-list-container").fadeIn()
	}, function() {
		$(".task-list-container").fadeOut();
	});
	
	$(".task-list-container .btn-close-task-list").click(function() {
		$(".task-list-container").fadeOut();
	});
});

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