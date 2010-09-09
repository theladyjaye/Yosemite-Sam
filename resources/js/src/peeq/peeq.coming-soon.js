$(function() {
	var $annotation = $(".annotation"),
		$cursor = $(".cursor"),
		$annotation_num = $(".annotation-num"),
		$view_sign_up = $("#view-sign-up"),
		$view_sign_up_container = $("#view-sign-up-container"),
		$view_signed_up_container = $("#view-signed-up-container"),
		offset_y = 20, /* offset for annotation num */
		offset_x = 30, /* offset for annotation num */
		annotation_width = 422,
		annotation_height = 255,
		offset = 50; /* misplace annotation offset */
	
	$("input").toggle_form_field();
	
	$(".btn-submit").click(function() {
		// process form
		
		// success => transition views
		$view_sign_up_container.animate({
			"top": "+=20",
			"opacity": 0
		}, 200, "easeOutQuad");
		
		$view_signed_up_container.css({
			"top": "-=20",
			"opacity": 0,
			"display": "block"
		}).delay(150).animate({
			"top": 0,
			"opacity": 1
		}, 500, "easeOutQuad");
	});
	
if(true)
{	
	// ANNOTATION ANIMATION
	$cursor.animate({
		"opacity": 1	/* fade in */	
	}, 800, "easeOutQuad").animate({ /* move to annotation num */
		"top": -offset_y,
		"left": -offset_x - offset
	}, 500, "easeOutQuad", function() { /* change cursor and resize */
		$annotation_num.css({
			"height": 0,
			"width": 0
		}).animate({ /* animate annotation num in */
			opacity: 1,
			"height": 20,
			"width": 21
		}, 500, "easeOutBack", function() {
			$cursor.toggleClass("cursor-pointer cursor-resize");			

			$(".ui-resizeable-handle").animate({
				"opacity": 0.8
			}, 200).delay(3000).animate({
				"opacity": 0
			}, 200);
			
			$annotation.addClass("show").css("opacity", 1).animate({
				"width": annotation_width,
				"height": annotation_height
			}, 1000, "easeOutQuad");
		
			$cursor.animate({
				"top": annotation_height - offset_y,
				"left": annotation_width - offset_x - offset
			}, 1000, "easeOutQuad", function() { 

				$annotation.delay(1000).animate({ /* move annotation */
					"left": "+=" + offset
				}, 1500, "easeOutQuad")

				$annotation_num.delay(1000).animate({ /* move annotation num */
					"left": "+=" + offset
				}, 1500, "easeOutQuad");

				$view_sign_up.animate({
					"opacity": 1
				}, 500, "easeOutQuad").delay(500).animate({
					"left": "+=" + offset
				}, 1500, "easeOutQuad");

				$cursor.toggleClass("cursor-resize cursor-move").animate({ /* change cursor and move 'cursor' to center of annotation */
					"top": Math.ceil((annotation_height - offset_y) * 0.5) - 20,
					"left": Math.ceil((annotation_width - offset_x) * 0.5) - offset
				}, 800, "easeOutQuad").delay(200).animate({ /* move annotation */
					"left": Math.ceil((annotation_width - offset_x) * 0.5)
				}, 1500, "easeOutQuad").animate({ /* fade out */
					"opacity": 0
				}, 1000, "easeOutQuad");
			});	
		});				
	});
}
});
