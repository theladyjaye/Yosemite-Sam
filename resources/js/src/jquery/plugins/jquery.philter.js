/**
 * Queries over a collection of items search for matches.  The filtered results are shown.
 * Fires complete.philter event with the number of matches when search has completed
 * @author Phil
 * @version 1.0
 */

(function($) {	
	$.fn.philter = function(settings) {
		var config = {	
			query_over: ".paginate li",		// collection of elements to search over
			query_by: ".title",				// element inside query_over to search against
			min_chars: 2,					// number of characters before initializing filter
			case_sensitive: false			// whether filtering is case sensitive
		};

		if (settings) $.extend(config, settings);

		this.each(function(i) {	
			var $this = $(this),
				$query_over = $(config.query_over),
				len = $query_over.length;
				
			$this.keyup(function() {
				var val = $(this).val();				
				if(val.length > config.min_chars)
				{
					for(var i = 0, matches = 0, $parent_elt, $elt, elt_text; i < len; i++)
					{
						$parent_elt = $query_over.eq(i);
						$elt = $parent_elt.find(config.query_by);
						elt_text = $elt.text();
						
						if(!config.case_sensitive)
						{
							elt_text = elt_text.toLowerCase();
							val = val.toLowerCase();
						}						
						
						if(elt_text.indexOf(val) != -1)
						{
							$parent_elt.show();
							matches++;
						}
						else
						{
							$parent_elt.hide();
						}
					}
				}
				else // not min chars entered, show all
				{
					$query_over.show();
					matches = -1;
				}
				
				// fire complete event when finished searching
				$this.trigger("complete.philter", matches);
			});
		});
		
		return this;
	};
})(jQuery);