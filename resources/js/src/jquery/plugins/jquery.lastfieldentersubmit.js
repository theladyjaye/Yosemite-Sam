(function($) {	
	$.fn.lastfieldentersubmit = function(settings) {
		var config = {	
			submit: null
		};

		if (settings) $.extend(config, settings);
		
		this.each(function(i) {	
			$frm = $(this);
			// submit form when user presses enter on last field
			$frm.delegate("input[type=text], textarea", "keypress", function(evt) {
				if(evt.keyCode == 13)
				{
					if(config.submit && $.isFunction(config.submit))
					{
						config.submit($(this).parents("form"));
					}
					else
					{
						$frm.submit();
					}			
				}
			});
		});
		
		return this;
	};
})(jQuery);