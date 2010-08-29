/**
 * Toggles the visibility of an element with a "hint" class by a form field
 * The hint typically is displayed on top of the form field then as a the field is given focus and
 * values are inputted the opacity of "hint" element changes
 * @author Phil
 * @version 1.0
 */

(function($) {	
	$.fn.toggle_form_field = function(settings) {
		var config = {	
			hint_class: "hint",
			opacity_focus: 0.2,
			opacity_blur: 0.5,
			opacity_keyup: 0,
			duration: 250
		};

		if (settings) $.extend(config, settings);

		this.each(function(i) {	
			// input field focus/blur/key toggle
			$(this).focus(function() {
				var $this = $(this);
				if($this.val() == "")
				{
					$this.parent().find("." + config.hint_class).animate({
						"opacity": config.opacity_focus
					}, config.duration);
				}
			}).blur(function() {
				var $this = $(this);
				if($this.val() == "")
				{
					$this.parent().find("." + config.hint_class).animate({
						"opacity": config.opacity_blur
					}, config.duration);
				}
			}).keyup(function() {	
				var $this = $(this);			
				if($this.val() != "")
				{
					$this.parent().find("." + config.hint_class).animate({
						"opacity": config.opacity_keyup
					}, config.duration);
				}
			});			
		});
		
		return this;
	};
})(jQuery);