function peeq() 
{	
	var sammy;
	
	// PRIVATE --------------------------------
	var setup_routes = function() 
	{
		sammy = $.sammy("#main", function() {
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
										
					$("#main").stop(false, true).animate({
						"opacity": 0
					}, 300, "linear", function() {
						$(this).html("").render_template({
							"name": template,
							"data": {"projects": data},
							"complete": function() {
								$(".pie-chart").piechart();
								$("#main").animate({
									"opacity": 1
								});
							}
						});
					});

					// store data
					context.session(storage_key, data);					
				});		
			})
			// views
			.get("#/:project", function(context) {
				
				// get project data from local storage
				var local_storage_projects = context.session("blitz.projects"),
					len = local_storage_projects.length,
					project_data
					
				for(var i = 0; i < len; i++)
				{
					if(local_storage_projects[i]["_id"] == "project/" + context.params["project"])
					{
						project_data = local_storage_projects[i];
						break;
					}
				}
				
				if(project_data)
				{
					// get views of project
					peeq.api.request("/project/" + context.params["project"] + "/views", {}, "get", function(data) {			
						var data = data || {},							
							storage_key = "blitz." + context.params["project"] + "-views";
						
						// if offline, try to grab from local storage, or fallbacks to cookie, or in-memory storage
						if(!peeq.is_online)
						{						
							data = context.session(storage_key, function() {
								return {};
							});					
						}
		
						var template = (data.length) ? "views" : "views-none";			
						console.log(project_data);
						$("#main").stop(false, true).animate({
							"opacity": 0
						}, 300, "linear", function() {
							change_bg("views");
							$(this).html("").render_template({
								"name": template,
								"data": {"views": data,
										 "project": project_data},
								"complete": function() {
									// all pie charts in view except #pie-chart-project
									$(".pie-chart:not(#pie-chart-project)").piechart({
										radius: 10,
										xpos: 10,
										ypos: 10,
										width: 20,
										height: 20,
										show_labels: false,
										is_hoverable: false
									});
							
									$("#pie-chart-project").piechart({
										radius: 60,
										xpos: 80,
										ypos: 90,
										width: 155,
										height: 170
									});
							
									$("#main").animate({
										"opacity": 1
									});
								}
							});
						});
	
						// store data
						context.session(storage_key, data);
					});
				}	
				else // project not found in local storage so redirect
				{
					context.redirect("");
				}		
			})
			// view-detail
			.get("#/:project/:view", function(context) {
				console.log(this.params['project'], this.params['view']);
			});
		});
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
		$.polling(200, function(check_again) {
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
	};
	
	var change_bg = function(id)
	{
		$("#bg img:not(#bg-default):visible").animate({
			"opacity": 0
		}, 400);
		
		$("#bg img[src$=" + id + ".png]").animate({
			"opacity": 1
		}, 400);
	}
	
	
	// registers modal, add, delete events
	var register_events = function() 
	{
		sammy.$element().delegate(".btn-delete", "click", function() {
			var path = sammy.getLocation().split("/").slice("1");
			peeq.api.request("/project/" + path, {}, "DELETE", function(msg) {
				console.log('deleted', msg);
				//sammy.redirect("");
			});
			return false;
		});
	}
	
	// PUBLIC --------------------------------
	this.is_online = navigator.onLine;
		
	this.main = function() 
	{
		// setup routes
		setup_routes();
		// run sammy
		sammy.run();
		
		register_events();
		
		// transition in footer
		transition_in_footer();
		
		// setup polling for online/offline connectivity
		poll_network_connectivity();
	};
		
	this.toString = function()
	{
		return "No peeqing!";
	};
};