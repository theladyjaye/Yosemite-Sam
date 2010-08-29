function peeq()
{
	// PRIVATE -------------------------------
	var register_events = function()
	{
		$("input").toggle_form_field();
		
		// subdomain key input
		$("input[name=domain]").focus(function() {
			$(this).parent().addClass("focus");			
		}).blur(function() {
			$(this).parent().removeClass("focus");
		}).parent().find(".domain").click(function() {
			$(this).parent().find("input").focus();
		})
	};
	
	var transition_in = function() 
	{
		$("#bg-default").animate({
			"opacity": 1
		});
		
		$("#main").animate({
			"opacity": 1
		});
	}
	
	// PUBLIC --------------------------------
	this.main = function() 
	{
		// transition in
		transition_in();
		
		// register events
		register_events();
		
		// first sign up field gets focus
		$("input:eq(0)").focus();
	};
};

var peeq = new peeq();
peeq.main();