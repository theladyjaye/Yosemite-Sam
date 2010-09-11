/**
 * Handles form validation by a set of user configured rules.  Rules of the following:
		elt: 		the query selector for the element that exists in the plugin selector to apply the rule
		rule: 		the rule to test the value of the elt against (@see examples below)
		checkevent: the event used to start check against the rule, if none is set then the default check event is used
		onerror(evt, $elt) : function called when rule fails
		onsuccess(evt, $elt) : function called when rule succeeds

   This plugin does not prevent the user from submitting an invalid form, only valids fields against rules.

 * @author Phil
 * @version 1.0
 */

(function($) {	
	$.fn.validation = function(settings) {
		var config = {	
			rules: [
			/* 
				Example rule
				
				{
					elt: "input[name=firstname]",
					rule: /\w{2,}/,
					checkevent: 'keydown',
					onerror: function(evt, $elt) {
						console.log('error', evt, $elt);
					},
					onsuccess: function(evt, $elt) {
						console.log('success', evt, $elt);					
					}						
				},
				
				Example matching field
				
				{
					elt: "input[name=password]",
					rule: "match",
					matching_field: ".passwordmatch"											
				},
				
				Example validating against ajax
				
				{
					elt: "input[name=username]",
					rule: "ajax",
					url: "handler.php",		// handler to check against 
					type: "get",			// method to send ajax request (default: get) 
					data: "username",		// key in data sent (default: data) 
					success: "ok"			// success response value (default: success) 
											// NOTE: response data type is text, if returning json then '{"ok":true}'					
				}
				
			*/
			],
			error_class: "error",
			success_class: "success",
			checkevent: "blur",	/* default check event is on blur */
			trim: true, /* whether to trim val when validating */
			onerror: function(evt, $elt) {return true;}, /* global on error function  */
			onsuccess: function(evt, $elt) {return true;} /* global on success function */ 
		};
		
		if(settings) $.extend(config, settings);

		this.each(function(i) {	
			if(!this.length) return;		
			
			var $this = $(this),
				rule_item;
								
			for(var i = 0, len = config.rules.length; i < len; i++)
			{
				add_rule($this, config.rules[i])				
			}						
		});
		
		// setup rule for rule item
		function add_rule($this, rule_item)
		{
			$this.delegate(rule_item.elt, rule_item.checkevent || config.checkevent, function(evt) {
				var $elt = $(this),
					val = config.trim ? $.trim($elt.val()) : $elt.val();
			
				// check against rule
				if(rule_item.rule)
				{
					// matching field rule (typically for matching password fields)
					if(rule_item.rule == "match" && rule_item.matching_field && $(rule_item.matching_field).length)
					{
						val == $(rule_item.matching_field).val() ? success(evt, $elt, rule_item) : error(evt, $elt, rule_item);
					}
					// ajax call to validate field 
					else if(rule_item.rule == "ajax" && rule_item.url)
					{
						var data = {};
						data[rule_item.data || "data"] = val; // set up data to send
						$.ajax({
							type: rule_item.type,
							url: rule_item.url,
							data: data,
							dataType: "text",
							success: function(response) {
								// check if response is success
								(response == (rule_item.success || "success")) ? success(evt, $elt, rule_item) : error(evt, $elt, rule_item);
							}
						});
					}
					else // reg exp rule
					{
						rule_item.rule.test(val) ? success(evt, $elt, rule_item) : error(evt, $elt, rule_item);
					}
				}
				else // if no rule is set at least check field is filled out
				{
					elt.is(":filled") ? success(evt, $elt, rule_item) : error(evt, $elt, rule_item);				
				}
			});
		}
		
		// success 
		function success(evt, $elt, rule_item)
		{			
			// if previous success, no need to do anything
			if(!$elt.hasClass(config.success_class))
			{	
				// run global on success
				if(config.onsuccess(evt, $elt))
				{					
					$elt.removeClass(config.error_class).addClass(config.success_class);	
					if($.isFunction(rule_item.onsuccess)) 
					{	
						rule_item.onsuccess(evt, $elt);								
					}
				}
			}
		}
		
		// error
		function error(evt, $elt, rule_item)
		{
			// if previously error, no need to do anything
			if(!$elt.hasClass(config.error_class))
			{
				// run global on error 		
				if(config.onerror(evt, $elt))
				{
					$elt.removeClass(config.success_class).addClass(config.error_class);				
					if($.isFunction(rule_item.onerror)) 
					{
						rule_item.onerror(evt, $elt);				
					}
				}					
			}
		}
		
		return this;
	};	
	
	// Helper Rules (extensible)
	$.validation = {
		EMAIL: /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
	};
	
	
})(jQuery);

// Custom selectors
$.extend($.expr[":"], {
	blank: function(elt) {return !$.trim("" + elt.value);},
	filled: function(elt) {return !!$.trim("" + elt.value);}
});