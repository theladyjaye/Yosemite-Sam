/*!
 * PHUI | PHIL UI
 *
 */
(function($) {	
	function init()
	{						
		determineBrowser();	
		
		var today 			= new Date();
		
		// add browser name, browser name - version, date-mm-dd-yyyy
		$("body").addClass($.phui.utils.browser.name + " " + $.phui.utils.browser.name + "-" + $.phui.utils.browser.version + " date-" + (today.getMonth() + 1) + "-" + today.getDate() + "-" + today.getFullYear());
				
	};
		
	function determineBrowser()
	{
		var browser = "webkit";
		if($.browser.msie)
		{
			browser = "msie";
		}
		else if($.browser.mozilla)
		{
			browser = "mozilla";
		}
		else if(navigator.userAgent.match(/iPhone/i))
		{
			browser = "iPhone";
		}
		else if(navigator.userAgent.match(/iPad/i))
		{
			browser = "iPad";
		}
		else if(navigator.userAgent.match(/iPod/i))
		{
			browser = "iPod";
		}
		
		$.phui.utils.browser.name = browser;		
		$.phui.utils.browser.version = parseInt($.browser.version);
	};
	
	$.phui = {
		params: {
			phui: {}
		},			
		utils: {
			browser: {
				name: "",
				version: ""			
			},
			getParams: function(url) {
				var result = {};
				var queryString;
				
				if(url)
				{
					result.hash = (url.indexOf("#") != -1) ? url.substring(url.indexOf("#") + 1, url.indexOf("?")) : "";
					result.pageURL = url.substring(url.lastIndexOf('/') + 1);
					queryString = url.substr(url.indexOf("?") + 1);		
				}
				else
				{
					result.hash = document.location.hash.substr(1);
					result.pageURL = document.location.pathname.substring(document.location.pathname.lastIndexOf('/') + 1);
					queryString = document.location.search.substr(1);
				}
			
				result.pageName = result.pageURL.split(".")[0];
				
				queryString.replace(/([^=&]+)=([^&]*)/g, function(match, key, value) {
					result[unescape(key)] = unescape(value);
				});
				
				return result;
			}
		}
	};
	
	
	$(function() {
		init();
	});
})(jQuery);
