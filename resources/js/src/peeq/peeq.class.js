function peeq() 
{
	// PRIVATE --------------------------------
	var setup_routes = function() 
	{
		var app = $.sammy("#main", function() {
			/// turn off logging
            //Sammy.log = this.log = function() {};
            
            // plugins
            this.use(Sammy.Session);

			// ROUTES

			// projects
			this.get("", function(context) {			
				peeq.api.request("/projects", {}, "get", function(data) {
					var data = data || {};
					
					// if offline, try to grab from local storage, or fallbacks to cookie, or in-memory storage
					if(!navigator.onLine)
					{
						data = context.session('blitz.projects', function() {
							return {};
						});					
					}
					
					var template = (data.length) ? "projects" : "projects-none";			
										
					$("#main").render_template({
						"name": template,
						"data": {"items": data}
					});

					app.$element().transition({
						load: function($elt, args) {						
							peeq.onload();													
						}					
					});	
			
					// store data
					context.session('blitz.projects', data);					
				});		
			})
			// views
			.get("#/:project", function(context) {
				$("#main").render_template({
					"name": "views"
				});
				
				console.log(this.params['project']);
				
				app.$element().transition({
					load: function($elt, args) {						
						peeq.onload();						
					}					
				});
			})
			// view-detail
			.get("#/:project/:view", function(context) {
				console.log(this.params['project'], this.params['view']);
			});
		});
				
		return app;
	};
	
	var transition_in_footer = function()
	{
		$("footer").css({
			"top": "+=10"
		}).delay(250).animate({
			"top": "-=10",
			"opacity": 1
		}, 250, "easeOutQuad");
		
	};	
	
	
	// PUBLIC --------------------------------	
	this.main = function() 
	{
		// setup routes
		var sammy = setup_routes();
		// run sammy
		sammy.run();
		
		// transition in footer
		transition_in_footer();
	};
	
	this.onload = function()
	{
		// setup pie charts
		$(".pie-chart").piechart();	
	};
	
	this.toString = function()
	{
		return "No peeqing!";
	};
};