/**
 * Handles form validation by a set of user configured rules.  All rules can contain the following:
		elt: 		the query selector for the element that exists in the plugin selector to apply the rule
		rule: 		the rule to test the value of the elt against (@see examples below)
		checkevent: the event used to start check against the rule, if none is set then the default check event is used
		onerror(evt, $elt) : function called when rule fails
		onsuccess(evt, $elt) : function called when rule succeeds

   This plugin does not prevent the user from submitting an invalid form, only valids fields against rules.

   EVENTS

   You can call $(form).trigger("validate"), which will validate all rules set.  When finished with validation, trigger("complete.validate", is_valid) is fired.

	You can listen for the validate complete event by:
	
	$(form).bind("complete.validate", function(evt, is_valid) {
		console.log(is_valid);
		
		if(is_valid)
		{
			// submit form
		}
	});

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
				
				Example custom rule
				{}
					elt: "input[name=lastname]",
					rule: "custom",					// must define rule as 'custom'
					success: function(evt, $elt)	// success function that returns true if validation is correct, otherwise false 
					{
						return ($elt.val() == "test");
					}
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
			// make sure selector (form) exists
			if(!this.length) return;		
			
			// make sure rules are set
			if(!config.rules.length)
			{
				if(window.console && window.console.warn)
				{
					console.warn("No rules set in jquery validation plugin.");
				}
				return;
			}
			
			// set up data used for custom events
			var	len = config.rules.length,
				$frm = $(this).data("validation", {
													"rules_count": len,
													"is_validating": false,
													"validation_count": 0,
													"is_valid": true
													});
			
			// add rules for each elt field					
			for(var i = 0; i < len; i++)
			{
				add_rule($frm, config.rules[i])				
			}	
			
			// CUSTOM EVENTS
						
			// validate -- will start validation on all elts in rules
			$frm.bind("validate", function() {
				var $this = $(this),
					data = $this.data("validation");
				
				// reset data for clean run	
				data.is_validating = true;
				data.validation_count = 0;
				data.is_valid = true;					
				$this.data("validation", data);				
								
				// trigger each rule, which will validate each
				for(var i = 0, rule_item; i < len; i++)
				{					
					rule_item = config.rules[i];
					$(rule_item.elt).trigger("rulecheck.validate");
				}
			})
			// rules.validate -- called after each rule is validated (used mainly internally) 
			//					 when all rules have been validated complete.validate event is triggered with is_valid param
			// @param isSuccess is true when field validates successfully			
			.bind("rule.validate", function(evt, isSuccess) {
				var $this = $(this),
					data = $this.data("validation"),
					validation_count = data.validation_count + 1,
					is_valid = true,
					is_validating = true;
					
				// once one rule is invalid, the whole form is invalid
				if(!isSuccess)
				{
					is_valid = false;
				}

				if(validation_count >= data.rules_count)
				{
					is_valid = is_valid && data.is_valid; // is form valid?												
					
					// trigger complete.validate
					$this.trigger("complete.validate", is_valid);					
				}
				
				// save updated data
				$this.data("validation", {
					"rules_count": data.rules_count,
					"is_validating": is_validating,
					"validation_count": validation_count,
					"is_valid": is_valid
				});
			}).bind("reset.validate", function() {
				// remove error class/success class from each field with rule
				for(var i = 0, rule_item; i < len; i++)
				{					
					rule_item = config.rules[i];
					$(rule_item.elt).removeClass(config.error_class + " " + config.success_class);					
				}
				// clear form fields
				$frm[0].reset();
			});		
		});
		
		// setup rule for rule item
		function add_rule($frm, rule_item)
		{
			$frm.delegate(rule_item.elt, (rule_item.checkevent || config.checkevent) + " rulecheck.validate", function(evt) {
				var $elt = $(this),
					val = config.trim ? $.trim($elt.val()) : $elt.val();
			
				// check against rule
				if(rule_item.rule)
				{
					// matching field rule (typically for matching password fields)
					// can't be empty
					if(rule_item.rule == "match" && rule_item.matching_field && $(rule_item.matching_field).length)
					{
						(val.length && val == $(rule_item.matching_field).val()) ? success(evt, $frm, $elt, rule_item) : error(evt, $frm, $elt, rule_item);
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
								(response == (rule_item.success || "success")) ? success(evt, $frm, $elt, rule_item) : error(evt, $frm, $elt, rule_item);
							}
						});
					}
					else if(rule_item.rule == "custom" && $.isFunction(rule_item.success))
					{
						rule_item.success(evt, $elt) ? success(evt, $frm, $elt, rule_item) : error(evt, $frm, $elt, rule_item);
					}
					else // reg exp rule
					{
						rule_item.rule.test(val) ? success(evt, $frm, $elt, rule_item) : error(evt, $frm, $elt, rule_item);
					}
				}
				else // if no rule is set at least check field is filled out
				{
					elt.is(":filled") ? success(evt, $frm, $elt, rule_item) : error(evt, $frm, $elt, rule_item);				
				}
			});
		}
		
		// success 
		function success(evt, $frm, $elt, rule_item)
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
			
			$frm.trigger("rule.validate", true);
		}
		
		// error
		function error(evt, $frm, $elt, rule_item)
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
			$frm.trigger("rule.validate", false);
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