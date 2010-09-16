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
				change_nav();
							
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
								$("body").attr("id", "");
								setup_modals();
								
								/*
								// pagination				
								var items_per_page = 2;				
								if($(".paginate li").length > items_per_page)
								{
									$(".paginate").pajinate({
										num_page_links_to_display: 3,
										items_per_page: items_per_page,
										nav_panel_id: ".page-navigation",
										nav_label_prev: "&lsaquo;",
										nav_label_next: "&rsaquo;",
										nav_label_first: "&laquo;",
										nav_label_last: "&raquo;"							
									});
								}   
								*/
								
								$(".search").philter({
									query_over: ".paginate li",
									query_by: ".title"
								}).bind("complete.philter", function(evt, matches) {
									$(".paginate li.last").removeClass("last");
									if(matches > 0)
									{
										$(".no-matches").hide();
										var txt_matches = matches > 1 ? "matches" : "match";
										$(".matches").html('<span class="complete">' + matches + '</span> ' + txt_matches);
										$(".paginate li:visible:last").addClass("last");
									}
									else if(matches == 0)
									{
										$(".no-matches").show();
										$(".matches").text("");									
									}
									else
									{
										$(".no-matches").hide();
										$(".matches").text("");	
									}
								}).parents("form").submit(function() {
									return false;
								});
								
								// move to separate function
								$("input").toggle_form_field();
								$(".excerpt").each(function() {
									$(this).html($(this).html().replace(/&lt;br \/&gt;/g, "<br />"));	
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
			})
			// views
			.get("#/:project", function(context) {
				change_nav();
				
				// settings page
				if(context.params["project"].toLowerCase() == "settings")
				{
					this.trigger("settings", context);
				}
				else // view
				{
					
					// get project data from local storage (!reading from service now)
					var local_storage_projects = context.session("blitz.projects"),
						len = local_storage_projects.length,
						project_data;
				
					// get project from service
					peeq.api.request("/projects", {}, "get", function(project_data) {					
						for(var i = 0; i < len; i++)
						{
						    //if(local_storage_projects[i]["_id"] == "project/" + context.params["project"])
						    if(project_data[i]._id == "project/" + context.params["project"])
						    {
						    	//project_data = local_storage_projects[i];
						    	project_data = project_data[i];
						    	break;
						    }
						}
							
						if(project_data)
						{
							// get views of project
							peeq.api.request("/project/" + context.params["project"] + "/views", {}, "get", function(data) {			
								var data = data || {},							
									storage_key = "blitz." + context.params["project"] + ".views";
						
								// if offline, try to grab from local storage, or fallbacks to cookie, or in-memory storage
								if(!peeq.is_online)
								{						
									data = context.session(storage_key, function() {
										return {};
									});					
								}

								var template = (data.length && data[0]) ? "views" : "views-none";			
								//console.log(project_data);
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
												xpos: 90,
												ypos: 90,
												width: 190,
												height: 170
											});
							
											$("body").attr("id", "");
									
											/*
											// pagination				
											var items_per_page = 2;				
											if($(".paginate li").length > items_per_page)
											{
												$(".paginate").pajinate({
													num_page_links_to_display: 3,
													items_per_page: items_per_page,
													nav_panel_id: ".page-navigation",
													nav_label_prev: "&lsaquo;",
													nav_label_next: "&rsaquo;",
													nav_label_first: "&laquo;",
													nav_label_last: "&raquo;"							
												});
											}   
											*/

											$(".search").philter({
												query_over: ".paginate li",
												query_by: ".title"
											}).bind("complete.philter", function(evt, matches) {
												$(".paginate li.last").removeClass("last");
												if(matches > 0)
												{
													$(".no-matches").hide();
													var txt_matches = matches > 1 ? "matches" : "match";
													$(".matches").html('<span class="complete">' + matches + '</span> ' + txt_matches);
													$(".paginate li:visible:last").addClass("last");
												}
												else if(matches == 0)
												{
													$(".no-matches").show();
													$(".matches").text("");									
												}
												else
												{
													$(".no-matches").hide();
													$(".matches").text("");	
												}
											}).parents("form").submit(function() {
												return false;
											});
									
											// move to separate function
											$("input").toggle_form_field();
									
											// download attachments (in new window)
											$("#table-attachments tbody").delegate("tr", "click", function() {
												var attachment_id = $(this).find(".btn-delete").attr("href").substr(1);
												window.open("/api/attachments/project" + encodeURIComponent("/" + context.params["project"] + "/attachment/" + attachment_id));
											});
																											
											setup_modals();
											peeq.editable.main();
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
					});
				}		
			})
			.get("#/:project/:view", function(context) {
				// redirect to project
				this.redirect("");
			})
			// states
			.get("#/:project/:view/:state", function(context) {
				change_nav();
				
				// get view data from local storage (!read from service now)
				var local_storage_view = context.session("blitz." + context.params["project"] + ".views"),
					len = local_storage_view.length,
					view_data;
				
				// get view from service
				peeq.api.request("/project/" + context.params["project"] + "/views", {}, "get", function(view_data) {	
				
					for(var i = 0; i < len; i++)
					{
						//if(local_storage_view[i]["_id"] == "project/" + context.params["project"] + "/" + context.params["view"])
						if(view_data[i]._id == "project/" + context.params["project"] + "/" + context.params["view"])
						{
							//view_data = local_storage_view[i];
							view_data = view_data[i];
							break;
						}
					}

					if(view_data)
					{
						// get states of project
						peeq.api.request("/project/" + context.params["project"] + "/" + context.params["view"] + "/states", {}, "get", function(data) {			
							var data = data || {},							
								storage_key = "blitz." + context.params["project"] + "-" + context.params["view"] + "-states";
					
							var current_state = peeq.utils.template.states.get_current(data, context.path.replace("#", "project"));
										
							// if offline, try to grab from local storage, or fallbacks to cookie, or in-memory storage
							if(!peeq.is_online)
							{						
								data = context.session(storage_key, function() {
									return {};
								});					
							}
												
							var template = "states";		
	
							$("#main").stop(false, true).animate({
								"opacity": 0
							}, 300, "linear", function() {
								change_bg("states");
								$(this).html("").render_template({
									"name": template,
									"data": {"view": view_data,
											 "state": current_state},
									"complete": function() {							
										$("#pie-chart-project").piechart({
											radius: 60,
											xpos: 90,
											ypos: 90,
											width: 190,
											height: 170
										});
						
										$("body").attr("id", "");
										setup_modals();
										peeq.editable.main();
										$("#main").animate({
											"opacity": 1
										});
									
										// change states
										$("#table-states tbody").delegate("tr:not(.current)", "click", function() {
											var state_id = $(this).find(".btn-delete").attr("href").substr(1);
											document.location.href = "#/" + context.params["project"] + "/" + context.params["view"] + "/" + state_id;
										});
									
										// get annotations
										peeq.api.request("/project/" + context.params["project"] + "/" + context.params["view"] + "/" + context.params["state"] + "/annotations", {}, "get", function(annotations) {
										
											if(annotations.length)
											{										
												// table
												$("#table-annotations-container").html("").render_template({
													"name": "states.table-annotations",
													"data": {"annotations" : annotations},
													"complete": function() {
														$("#table-annotations-container").find(".table-sortable").tablesorter({
															"cssAsc": "icon-sort-asc",
															"cssDesc": "icon-sort-desc"
														});
													
														// clicking on tr fire annotation in preview's click event (deeplinking into annotation)
														$("#table-annotations-container").delegate(".annotation-item", "click", function() {
															var annotation_id = peeq.utils.template.annotations.get_id_from_elt($(this));
															$(".annotate-preview-container ." + annotation_id).click();
														});
													}
												});
										
												// preview							
												var preview_annotation = null,
												  	preview_position = null,
													$annotation_preview_image = $("#annotate-preview-image"),
													preview_width = $annotation_preview_image.width(),
													preview_height = $annotation_preview_image.height();
																							
												$("#annotate-preview").addAnnotations(function(props) {
													return $("<a />", {
														"href": document.location.href + "/annotate:" + peeq.utils.template.annotations.sanitize_id(props._id),
														"class": "annotation-item annotation-id-" + peeq.utils.template.annotations.sanitize_id(props._id) +  " icon " + peeq.utils.template.get_annotation_marker_class(props) + " ir" ,
														"html": props.type
													});												
												}, annotations, {"containerHeight": preview_height});
											
												// hide preview annotation positioned outside preview image
												for(var i = 0, len = annotations.length, $preview_annotations = $("#annotate-preview").find(".annotation-item"); i < len; i++)
												{
													$preview_annotation = $($preview_annotations[i]);
													preview_position = $preview_annotation.position();
													
													if(preview_position.top < -5 || preview_position.top > preview_height ||
													   preview_position.left < -5 || preview_position.left > preview_width)
													{
														$preview_annotation.css("visibility", "hidden");
													}			
												}
											
											
												$("#main").delegate(".annotation-item", "mouseover", function() {
													var annotation_id = peeq.utils.template.annotations.get_id_from_elt($(this));
													$("." + annotation_id).addClass("on")
												}).delegate(".annotation-item", "mouseout", function() {
													$(".annotation-item.on").removeClass("on");
												}).delegate(".annotation-item", "click", function() {
													if($(this).attr("href"))
													{
														document.location.href = $(this).attr("href");
													}
												});										
											
											}
											// no annotations
											else
											{
												$("#table-annotations-container").html("").render_template({	
													"name": "states.table-annotations-none"
												});
											
												$(".expander").hide();
											}								
										});
									}
								});
							});
							// store data
							context.session(storage_key, data);
						})
					}
				});
			})
			// annotate
			.get("#/:project/:view/:state/annotate", function(context) {
				this.trigger("annotate", context);
			})
			// annotate w/ deeplinking
			.get("#/:project/:view/:state/annotate::id", function(context) {
				this.trigger("annotate", context);
			});
			
			// settings view
			this.bind("settings", function(event, context) {
				change_nav();				
				$("#main").stop(false, true).animate({
					"opacity": 0
				}, 300, "linear", function() {
					change_bg("settings");
					$(this).html("").render_template({
						"name": "settings",
						"data": {"users": "test"},
						"complete": function() {
							setup_modals();
					        $("body").attr("id", "");
					
							$(".settings").find(".table-sortable").tablesorter({
								"cssAsc": "icon-sort-asc",
								"cssDesc": "icon-sort-desc"
							});
					
					        $("#main").animate({
					        	"opacity": 1
					        });
						}
					});
				});
			});
			
			// annotate event
			// handles annotate view and deeplinking to an annotation in annotate view
			this.bind("annotate", function(event, context) {
				change_nav();
				// get states of project
				peeq.api.request("/project/" + context.params["project"] + "/" + context.params["view"] + "/states", {}, "get", function(data) {					
					
					// get state representation
					for(var i = 0, len = data.length; i < len; i++)
					{
						if(data[i]._id == "project/" + context.params["project"] + "/" + context.params["view"] + "/" + context.params["state"])
						{
							data = data[i];
							break;
						}
					}
					
					// get annotations
					peeq.api.request("/project/" + context.params["project"] + "/" + context.params["view"] + "/" + context.params["state"] + "/annotations", {}, "get", function(annotations) {					
						// preview
						$("#main").stop(false, true).animate({
							"opacity": 0
						}, 300, "linear", function() {
							change_bg("annotate");
							$(this).html("").render_template({
								"name": "annotate",
								"data": data,
								"complete": function() {
									$("body").attr("id", "annotate");
								
									// size representation								
									var representation_image = new Image(),
										$representation = $("#representation"),
										$representation_image = $representation.find("img");
									
									representation_image.src = $representation_image.attr("src");
									$representation.width(representation_image.width).height(representation_image.height);
								
									// back to details button
									$("header .btn-back").attr("href", "#/" + context.params["project"] + "/" + context.params["view"] + "/" + context.params["state"]);
								
									peeq.annotate.config.users = {
										"alincoln": "alincoln - Project Manager | BLITZ",
										"jmadison": "jmadison - Software Developer | BLITZ",
										"ajackson": "ajackson - Art Director | BLITZ",
										"bross": "bross - Designer | BLITZ"
									};
									peeq.annotate.main();
									
									var deeplink_id = context.params["id"] || null;
									peeq.annotate.add_annotations(annotations, deeplink_id);
								
									$("#main").animate({
										"opacity": 1
									});
								}
							});
						});
					});
				});
			});
		});
	};
	
	var change_nav = function() 
	{
		var hash = document.location.hash,
			$header = $("header");
			
		$header.find("a").removeClass("off");
		$header.find("a[href=" + hash + "]").addClass("on");
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
		
		if(id)
		{
			$("#bg img[src$=" + id + ".png]").animate({
				"opacity": 1
			}, 400);
		}
	}
	
	
	// registers modal, add, delete events
	var register_events = function() 
	{
		/*
		sammy.$element().delegate(".btn-delete", "click", function() {
			var path = sammy.getLocation().split("/").slice("1");
			peeq.api.request("/project/" + path, {}, "DELETE", function(msg) {
				console.log('deleted', msg);
				//sammy.redirect("");
			});
			return false;
		});
		*/
		
		// forms
		try {
			peeq.forms.main();
		} catch(e) {}
		
		// expander
		$("#main").delegate(".expander", "click", function() {
			$(this).parents(".column").toggleClass("wide");
		});
	};
	
	// setup modals
	var setup_modals = function() 
	{
		$(".modal").addClass("jqmWindow").jqm({
			overlay: 90,
			trigger: false,
			closeClass: "btn-modal-close",
			onShow: function(hash) {
				hash.w.css({
					"top": "-1000px",
					"display": "block",
					"opacity": 0
				}).animate({
					"top": "12%",
					"opacity": 1
				}, 300, "easeOutQuad");
				
				$("input").toggle_form_field();
				$("textarea").toggle_form_field();
			},
			onHide: function(hash) {
				hash.w.animate({
					"top": "-1000px",
					"opacity": 0
				}, 150, "easeOutQuad");
				
				hash.o.fadeOut(150, function() {
					$(this).remove();
				});
			}
		});	
		
		$(".btn-modal").click(function() {
			$(".modal"+ get_modal_view(this)).jqmShow();
			
			// attachment delete modal link 
			// state delete modal link
			// => populate fields
			if($(this).hasClass("modal-view-delete-attachment") || $(this).hasClass("modal-view-delete-state"))
			{
				var $this = $(this),
					id = $this.attr("href").substr(1),
					$frm = $this.hasClass("modal-view-delete-attachment") ? $("#frm-attachment-delete") :  $("#frm-state-delete");
					
				$frm.find("input[name=id]").val(id); // populate id
				$frm.find("p strong").append(" " + $this.parents("tr").find(".table-column-title").text()); // populate label
			}
			
			return false;
		});
		
		function get_modal_view(btn) 
		{
			var regExp = /modal-view-(\w|\d|-)*/,
				class_names = $(btn)[0].className,
				matches = class_names.match(regExp);
			return (matches.length) ? "." + matches[0] : "";
		}
		
		// cancel triggers close
		$(".btn-modal-cancel").click(function() {
			$(".btn-modal-close").click();
			return false;
		});
		
		// file upload skin
		$("input[type=file]").filestyle({ 
	   		image: "/resources/imgs/btn-browse.png",
		    imageheight : 50,
		    imagewidth : 87,
		    width : 260	
		});
	};
	
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