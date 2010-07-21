(function(ns) {
	ns.progressbar = 
	{
		main: function() 
		{
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
		}
	} 
})($.phui.yss);