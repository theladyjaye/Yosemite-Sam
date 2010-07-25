(function($) {
	if(!$.phui) {$.phui = {};}
	$.phui.transitions = function(settings) {
		if(!this.transitions) return;

		var config = {	
			json: "transitions.json",
			duration: "fast",
			easing: "linear",			
			queue: true,
			specialEasing: {},
			delay: 0,
			loaded: null,
			eventListener: "html"
		};

		if(settings) $.extend(config, settings);			

		// invalid json will fail silently
		$.get(config.json, function(res) {
			for(var transitionName in res)
			{						
				(function(transitionName, res) {
					$.phui.transitions[transitionName] = {
						transitionIn: function(settings) {
							if(settings) $.extend(res[transitionName].transitionIn, settings);
							transition(transitionName, res, "transitionIn");
						},
						transitionOut: function(settings) {
							if(settings) $.extend(res[transitionName].transitionOut, settings);
							transition(transitionName, res, "transitionOut");
						}
					};
				})(transitionName, res);
			}
			
			if($.isFunction(config.loaded))
			{
				config.loaded();
			}
			
			$(config.eventListener).trigger("loaded");
			
			
		}, "json");
		
		function transition(transitionName, json, transitionType) 
		{
			var transJSON = json[transitionName][transitionType],
				order, orderLen, i;
				
			if(!transJSON || !transJSON.order) return;
			
			order = transJSON.order;

			if(order == "reverse")
			{
				// create deep copy and reverse order
				switch(transitionType)
				{
					case "transitionIn":
						order = json[transitionName].transitionOut.order.slice().reverse(); 						
						break;
					default:
						order = json[transitionName].transitionIn.order.slice().reverse();
						break;							
				}
			}

			orderLen = order.length;
			
			for(i = 0; i < orderLen; i++)
			{	
				$(order[i]).data(transitionName, {
					"transition": {
						"index": i
					}
				}).delay(i * (transJSON.delay || config.delay)).animate(transJSON.props, {
					queue: transJSON.queue || config.queue, 
					duration: transJSON.duration || config.duration,
					easing: transJSON.easing || config.easing,
					specialEasing: transJSON.specialEasing || config.specialEasing,
					complete: function() {						
						var data = $(this).data(transitionName).transition;										
						if(data.index == orderLen - 1)
						{								
							$(config.eventListener).trigger(transitionType + "Complete." + transitionName, [transJSON]);
						}
					}					
				});
			}
		};
	};
})(jQuery);