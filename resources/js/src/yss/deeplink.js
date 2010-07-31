(function(ns) {
	ns.deeplink = 
	{
		title: document.title,
		main: function()
		{			
			if(isDirectAccess())
			{
				redirect();
			}
						
			ns.deeplink.projects();
			$.phui.transitions({
				"json": "/resources/json/transitions.json",
				"loaded": function() {
					setupjQueryAddress();
					transition($.address.pathNames());
				}
			});
			
			
			$("html").bind("transitionInComplete.projects", function(evt, settings) {
				//console.log('projects in complete');				
			}).bind("transitionOutComplete.projects", function(evt, settings) {
				//console.log('projects out complete')
			}).bind("transitionInComplete.views", function(evt, settings) {
				//console.log('view complete');
			});
			
			$("#body").bind("transitionIn", function(evt, page, params, callback) {
				$(this).load(page + " #body .body-content", params, function(html) {
					$("#body .body-content").css({"opacity": 0});
					if($.isFunction(callback)) 
					{
						callback();
					}
				}, "html");
			}).bind("transitionOut", function(evt, callback) {
				$("#body .body-content").animate({
					"opacity": 0
				}, 300, "linear", function() {
					if($.isFunction(callback))
					{
						callback();
					}
				});
			});
		},
		
		projects: function() 
		{
			$("#project-list .dp").click(function() {
				var $this_li = $(this).parents("li"),
					$li;

				return false;
			})
		},
		
		parsePath: function(pathnames)
		{
			var obj = {};

			if(pathnames.length > 0) obj.project = pathnames[0].toLowerCase();
			if(pathnames.length > 1) obj.view = pathnames[1].toLowerCase();
			if(pathnames.length > 2) obj.state = pathnames[2].toLowerCase();
			if(pathnames.length > 3) obj.annotation = pathnames[3].toLowerCase();

			return obj;
		}
	}
	
	function isDirectAccess()
	{
		return document.location.pathname.indexOf(".php") != -1;
	}
	
	function redirect()
	{
		document.location.href = "/";
	}	
	
	function setupjQueryAddress()
	{
		$('.dp').address();
		$.address.init(function(event) {
		     
           }).change(function(event) {
			var names = $.map(event.pathNames, function(n) {
				return n.substr(0, 1).toUpperCase() + n.substr(1);
               }).concat(event.parameters.id ? event.parameters.id.split('.') : []);
               var links = names.slice();
               var match = links.length ? links.shift() + ' ' + links.join('.') : 'Home';
/*
               $('a').each(function() {
                   $(this).toggleClass('selected', $(this).text() == match);
               });
*/
               $.address.title([ns.deeplink.title].concat(names).join(' | '));
			   transition(names);
           });

		$("#body").delegate(".no-dp", "click", function() {
			return false;
		});
	}
	
	function transition(pathnames)	
	{
		var href, 
			transitionKey, 
			params = ns.deeplink.parsePath(pathnames),
			callback = function() {};

		switch(pathnames.length)
		{
			case 1: 	
				// /settings
				if(pathnames[0].toLowerCase() == "settings")
				{
					href = "/settings.php";
					transitionKey = "settings";
					callback = function() {
						ns.modal.main();
						ns.forms.main();
						ns.editablefields.main();
					};
				}
				// /ollie
				else
				{
					$("#project-list .dp[href=#/" + pathnames[0].toLowerCase() + "]").click();				
					href = "/views.php",
					transitionKey = "views";
					callback = function() {
						ns.progressbar.main();
						ns.modal.main();
						ns.forms.main();
					};
				}
				break;
			case 2: // /ollie/logout
				// redirect to default (first) state
				href = "/view-detail.php";				
				transitionKey = "viewdetail";
				callback = function() {
					ns.progressbar.main();
					ns.modal.main();
					ns.forms.main();
					ns.modules.main();
					ns.editablefields.main();
				}
				break;
			case 3: // /ollie/logout/default
				href = "/view-detail.php";
				transitionKey = "viewdetail";
				callback = function() {
					ns.progressbar.main();
					ns.modal.main();
					ns.forms.main();
					ns.modules.main();
					ns.editablefields.main();
				}
				break;
			case 4: // /ollie/logout/default/edit
				if(pathnames[3].toLowerCase() == "edit")
				{
					href = "/editor.php";
					transitionKey = "editor";
					callback = function() {
						ns.notes.main();
					}
				}
				break;
			default:// /
				href = "/projects.php";
				transitionKey = "projects";
				callback = function() {
					ns.progressbar.main();
					ns.modal.main();
					ns.forms.main();
					ns.editablefields.main();
					ns.tableorientation.main();
				};
		}
		
		if(href)
		{
			$("#body").trigger("transitionOut", [function() {
				$("#body").trigger("transitionIn", [href, params, function() {
				callback();	
				$.phui.transitions[transitionKey].transitionIn();
			}])}]);
		}
		else
		{
			redirect();
		}
	}	
})($.phui.yss);