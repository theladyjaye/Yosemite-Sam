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
			duration: 400
		};

		if (settings) $.extend(config, settings);

		this.each(function(i) 
		{	
			var $this = $(this),
				values = [],
	            labels = [],
				val = 0,
				isComplete = false;

	        $this.find("tr").each(function () {
				if(!isComplete)
				{
					val = parseInt($(this).find("td").text(), 10);
		            values.push(val);
		            labels.push($(this).find("th").text());

					if(val >= 100)
					{
						isComplete = true;
					}
				}
			});
			
			$this.find("table").hide();
			if(isComplete)
			{
				// create complete circle with complete text
				var paper = Raphael($this.attr("id"), config.width, config.height);
				var pie = paper.set();
				
				var circle = paper.circle(config.xpos, config.ypos, config.radius).attr({"gradient": "90-#fa0065-#fa2279", stroke: config.stroke_color, "stroke-width": config.stroke_width});
				
				var complete = paper.text(config.xpos, config.ypos, config.complete_text).attr({fill: "#fff", stroke: "none", "font-family": 'Calibri, Lucida Sans, Helvetica, Arial', "font-size": "16px", "font-weight": "bold"});
				
				pie.push(circle);
				pie.push(complete);
				
				pie.mouseover(function () {
	                pie.animate({scale: [1.1, 1.1, config.xpos, config.ypos]}, config.duration, "backOut");
	            }).mouseout(function () {
	                pie.animate({scale: [1, 1, config.xpos, config.ypos]}, config.duration, "backOut");
				});
				
			}
			else			
			{
		    	Raphael($this.attr("id"), config.width, config.height).piechart(config.xpos, config.ypos, config.radius, values, labels, config.stroke_color, config.stroke_width, config.duration);
			}
	   });
		
	   return this;
	};
})(jQuery);