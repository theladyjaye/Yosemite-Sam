(function(ns) {
	ns.deeplink = 
	{
		title: document.title,
		main: function()
		{			
			if(isDirectAccess())
			{
				document.location.href = "/";
			}
						
			ns.deeplink.projects();
			$.phui.transitions({
				"json": "/resources/json/transitions.json",
				"loaded": function() {
					console.log('loaded');
					setupjQueryAddress();
					transition($.address.pathNames());
				}
			});
			
			
			$("html").bind("transitionInComplete.projects", function(evt, settings) {
				console.log('projects in complete');				
			}).bind("transitionOutComplete.projects", function(evt, settings) {
				console.log('projects out complete')
			}).bind("transitionInComplete.views", function(evt, settings) {
				console.log('view complete');
			});
			
			$("#body").bind("transitionIn", function(evt, page, callback) {
				$(this).load(page + " #body .body-content", function(html) {
					$("#body .body-content").css({"opacity": 0});
					if($.isFunction(callback)) 
					{
						callback();
					}
				});
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
				//$.phui.transitions.projects.transitionOut();
				
				return false;
			})
		}
	}
	
	function isDirectAccess()
	{
		return document.location.pathname.indexOf(".php") != -1;
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
			console.log(links, match, names, event);
               $('a').each(function() {
                   $(this).toggleClass('selected', $(this).text() == match);
               });
               $.address.title([ns.deeplink.title].concat(names).join(' | '));
			   transition(names);
           });
	}
	
	function transition(pathnames)	
	{
		var href, transitionKey, callback = function() {};
		console.log(pathnames);
		switch(pathnames.length)
		{
			case 1: // /ollie				
				if(pathnames[0].toLowerCase() == "settings")
				{
					console.log('settings');
					href = "/settings.php";
					transitionKey = "settings";
					callback = function() {
						ns.modal.main();
						ns.forms.main();
					};
				}
				else
				{
					console.log('views');
					$("#project-list .dp[href=#/" + pathnames[0].toLowerCase() + "]").click();				
					href = "/views.php",
					transitionKey = "views";
				}
				break;
			case 2: // /ollie/default
				console.log('state');
				href = "/view-detail.php";
				transitionKey = "viewdetail";
				callback = function() {
					ns.progressbar.main();
					ns.modal.main();
					ns.forms.main();
				}
				break;
			case 3: // /ollie/default/adlkjfiej234
				console.log('editor');
				href = "/editor.php";
				transitionKey = "editor";
				callback = function() {
					ns.notes.main();
				}
				break;
			default:// /
				console.log('projects');
				href = "/projects.php";
				transitionKey = "projects";
				callback = function() {
					ns.progressbar.main();
					ns.modal.main();
					ns.forms.main();
				};
		}
		
		$("#body").trigger("transitionOut", [function() {
			$("#body").trigger("transitionIn", [href, function() {
			callback();	
			$.phui.transitions[transitionKey].transitionIn();
		}])}]);
	}
	
})($.phui.yss);