(function(ns) {
	ns.validation = 
	{
		main: function() 
		{
		},		
		regexp: 
		{
			label: /(\w|\d|_|-){2,}/ // at least 2 (word, digit, _, -)
		}	
	}
})($.phui.yss.forms);