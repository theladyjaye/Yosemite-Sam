function peeq()
{
	// PRIVATE -------------------------------
	var register_events = function()
	{
		$("input").toggle_form_field();
				
		 // whenever a user presses enter in the last input field of the form it will fire .btn-submit (submitting the form) 
		$("form").lastfieldentersubmit({
			submit: function($frm) {
				$frm.find(".btn-submit").click();
			}
		});
	};
	
	var contact_validation = function() 
	{
		$("#frm-contact").find(".btn-submit").click(function() {
			var $frm = $("#frm-contact");
			
			/*
			$.post("", $frm.serialize(), function(response) {
				$("#contact-container").fadeOut();
				$("#thanks").fadeIn();
			});
			*/
//			console.log($frm.serialize());
			$("#contact-container").fadeOut(500, function() {
				$("#thanks").fadeIn();
			});
			
			return false;
		});
	}
	
	
	// PUBLIC --------------------------------
	this.main = function() 
	{	
		// register events
		register_events();
		
		// first contact field gets focus
		$("input:eq(0)").focus();
		
		// setup contact validation
		contact_validation();		
	};
};

var peeq = new peeq();
peeq.main();