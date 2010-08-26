function peeq()
{
	// PRIVATE -------------------------------
	var register_events = function()
	{
		// input field focus/blur/key toggle
		$("input").focus(function() {
			var $this = $(this);
			if($this.val() == "")
			{
				$this.parent().find(".hint").animate({
					"opacity": 0.2
				}, 250);
			}
		}).blur(function() {
			var $this = $(this);
			if($this.val() == "")
			{
				$this.parent().find(".hint").animate({
					"opacity": 0.5
				}, 250);
			}
		}).keyup(function() {	
			var $this = $(this);			
			if($this.val() != "")
			{
				$this.parent().find(".hint").animate({
					"opacity": 0
				}, 250);
			}
		});
		
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