(function(ns) {
	ns.tableorientation = 
	{
		main: function()
		{
			$(".orientation a").click(function() {
				var $this = $(this);

				if(!$this.hasClass("selected"))
				{
					$(".orientation a").removeClass("selected");
					$this.addClass("selected").animate({"opacity": 1}, 200);
					$(".orientation a:not(.selected)").animate({"opacity": 0.2}, 200);				

				
					if($this.hasClass("cascade"))
					{
						$("#table-list").addClass("cascade");
					}
					else
					{
						$("#table-list").removeClass("cascade");
					}

				}
				return false;
			});
			
			$(".orientation a:not(.selected)").css({"opacity": 0.2});
		}
	};	
})($.phui.yss);