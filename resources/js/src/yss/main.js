(function(ns) 
{
	ns.yss = 
	{
		main: function() 
		{
			Cufon.replace('.font-replace', { fontFamily: 'Vegur', hover: true });
			callMains(this);
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
