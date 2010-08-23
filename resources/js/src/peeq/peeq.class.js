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
					var data = data || {},
						storage_key = "blitz.projects";

					// if offline, try to grab from local storage, or fallbacks to cookie, or in-memory storage
					if(!peeq.is_online)
					{
						data = context.session(storage_key, function() {
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
					context.session(storage_key, data);					
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
	
	// checking every 500ms for network connection
	var poll_network_connectivity = function() 
	{
		$.polling(500, function(check_again) {
			if(this.is_online != navigator.onLine)
			{
				this.is_online = navigator.onLine;
				if(navigator.onLine) // online => hide #network-connectivity
				{
					$("#network-connectivity:visible").animate({
						"bottom": "-30px"
					}, 200, "easeOutBack");
				}
				else // offline => show #network-connectivity
				{
					$("#network-connectivity").animate({
						"bottom": "-3px"
					}, 200, "easeOutBack");
				}
			}
			check_again();
		});
	}
	
	// PUBLIC --------------------------------
	this.is_online = navigator.onLine;
		
	this.main = function() 
	{
		// setup routes
		var sammy = setup_routes();
		// run sammy
		sammy.run();
		
		// transition in footer
		transition_in_footer();
		
		// setup polling for online/offline connectivity
		poll_network_connectivity();
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