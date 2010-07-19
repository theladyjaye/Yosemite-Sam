$(function() {
	$("#view-table-list").delegate("li", "mouseenter", function() {
		$(this).addClass("over");
	}).delegate("li", "mouseleave", function() {
		$(this).removeClass("over");		
	}).delegate(".item-details", "click", function() {
		$(this).parents("li").toggleClass("expanded");
		return false;	
	});
});