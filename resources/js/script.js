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
});