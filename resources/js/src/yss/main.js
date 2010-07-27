(function(ns) 
{
	ns.yss = 
	{
		main: function() 
		{
			Cufon.replace('.font-replace', {fontFamily: 'Vegur', hover: true });			
			callMains(this);
		},
		utils: 
		{
			getItemPath: function($this) 
			{
				var href = $this.parents("li").find(".dp").attr("href");
				var aryPath = href.split("/").slice(1);
				if(aryPath[aryPath.length - 1] == "edit")
				{
					aryPath = aryPath.slice(0, aryPath.length - 1);
				}

				var path = "/project/" + aryPath.join("/");
				
				return path;
			}
		}	
	};
	
	function callMains(ns)
	{		
		for(i in ns)
		{
			if(typeof(ns[i]) == "object")
			{
				if($.isFunction(ns[i].main)) 
				{
					ns[i].main();
				}
				callMains(ns[i]);
			}		
		}
	}
	
	$(function() {
		ns.yss.main();
	});
})($.phui);
