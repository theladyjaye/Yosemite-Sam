(function(ns) {
	ns.modules = 
	{
		main: function() 
		{
			$(".module .toggle").click(function() {
				var $this = $(this),
					$module_header = $this.parents(".module-header"),
					$module_body = $this.parents(".module").find(".module-body");
					
				$module_header.toggleClass("open");
				
				if($module_header.hasClass("open"))
				{
					$module_body.stop(false, true).slideDown();
					$module_header.find(".filters").fadeIn();
				}
				else
				{
					$module_body.stop(false, true).slideUp();
					$module_header.find(".filters").fadeOut();
				}
			});
			
			// filters
			$(".filters a").click(function() {
				var $this = $(this),
					$module = $this.parents(".module");
				
				$(".filters a").removeClass("on");
				$this.addClass("on");
				var filter = get_filter($this);
				if(filter)
				{
					if(filter == "all")
					{
						$module.find(".module-body li").fadeIn();	
					}
					else
					{
						$module.find(".module-body li:not(." + filter + ")").stop(false, true).fadeOut(200, function() {
							$module.find(".module-body li." + filter).stop(false, true).fadeIn();
						});
					}
				}
				return false;
			});
		}
	};
	
	function get_filter($filter_a)
	{	
		var matches = $filter_a.attr("class").match(/filter-(\w|-)*/);
			
		if(matches)
		{
			return matches[0].substr(7);
		}
		return null;
	}
})($.phui.yss);