var peeq = function() 
{
	function setup_routes() 
	{
		var app = $.sammy("#main", function() {
			/// turn off logging
            //Sammy.log = this.log = function() {};
            
            // plugins
            this.use(Sammy.Session);

			this.get("", function(context) {				
				$("#main").render_template({
					"name": "projects"
				});
				
				app.$element().transition({
					load: function($elt, args) {
						//console.log('loaded', args);
						peeq_app.onload();
						
					},
					load_params: ["one", "two"]
				});
			});
		});
				
		return app;
	};
	
	function transition_in_footer()
	{
		$("footer").css({
			"top": "+=10"
		}).delay(250).animate({
			"top": "-=10",
			"opacity": 1
		}, 250, "easeOutQuad");
		
	}
			
	return {
		main: function() 
		{						
			// setup routes
			var sammy = setup_routes();
			// run sammy
			sammy.run();
			
			// transition in footer
			transition_in_footer();

		},
		onload: function() 
		{
			// setup pie charts
			$(".pie-chart").piechart();
		}
	};
};

var peeq_app = new peeq();

$(function() {
	peeq_app.main();
});