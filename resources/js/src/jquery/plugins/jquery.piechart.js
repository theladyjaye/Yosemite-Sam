// selector must have an ID
(function($) {	
	$.fn.piechart = function(settings) {
		var config = {	
			radius: 50,
			xpos: 100,
			ypos: 100,
			width: 250,
			height: 250,
			stroke_color: "rgba(50, 50, 50, 0.1)",
			stroke_width: 2,
			complete_text: "COMPLETE!",
			complete_label: "complete",
			complete_class: "complete",
			incomplete_label: "incomplete",
			duration: 400,
			show_labels: true,
			is_hoverable: true
						 
		};

		if (settings) $.extend(config, settings);

		this.each(function(i) 
		{	
			var $this = $(this),
				values = [],
	            labels = [],
				val = 0,
				label = "",
				is_complete = false,
				is_not_started = false;

	        $this.find("tr").each(function () {
				if(!is_complete && !is_not_started)
				{
					val = parseInt($(this).find("td").text(), 10);
					label = $(this).find("th").text();
					
		            values.push(val);
		            labels.push(label);

					if(label.toLowerCase() == config.complete_label.toLowerCase() && val >= 100)
					{
						is_complete = true;
						$this.addClass("complete");
					}
					
					if(label.toLowerCase() == config.incomplete_label.toLowerCase() && val >= 100)
					{
						is_not_started = true;
					}
				}
			});
			
			$this.find("table").hide();
			if(is_complete || is_not_started)
			{
				// create circle with text (if complete)
				var paper = Raphael($this.attr("id"), config.width, config.height),
					pie = paper.set(),				
					complete_gradient = "90-#fa0065-#fa2279",
					not_started_gradient = "90-#f8faf3-rgba(230, 230, 230, .2)",
									
					circle = paper.circle(config.xpos, config.ypos, config.radius).attr({"gradient": is_complete ? complete_gradient : not_started_gradient, stroke: config.stroke_color, "stroke-width": config.stroke_width});
				
				pie.push(circle);
				
				if(is_complete && config.show_labels)
				{
					var	complete = paper.text(config.xpos, config.ypos, config.complete_text).attr({fill: "#fff", stroke: "none", "font-family": 'Calibri, Lucida Sans, Helvetica, Arial', "font-size": "16px", "font-weight": "bold"});
					pie.push(complete);
				}			
				
				if(config.is_hoverable)
				{
					pie.mouseover(function () {
		                pie.animate({scale: [1.1, 1.1, config.xpos, config.ypos]}, config.duration, "backOut");
		            }).mouseout(function () {
		                pie.animate({scale: [1, 1, config.xpos, config.ypos]}, config.duration, "backOut");
					});
				}
			}
			else			
			{
		    	Raphael($this.attr("id"), config.width, config.height).piechart(config.xpos, config.ypos, config.radius, values, labels, config.stroke_color, config.stroke_width, config.duration, config.show_labels, config.is_hoverable);
			}
	   });
		
	   return this;
	};
})(jQuery);