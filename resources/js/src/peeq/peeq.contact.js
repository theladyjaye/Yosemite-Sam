function peeq()
{
	var domain = "yss.com";
	
	// PRIVATE -------------------------------
	var register_events = function()
	{
		$("input").toggle_form_field();
						
		// logout
		$("#btn-logout").click(function() {
			$.post("/api/account/logout", function(response) {
				// redirect 
				document.location.href = "http://" + domain;
			});
		});
	};
	
	var contact_validation = function() 
	{
		var $form = $("#frm-contact");
		
		$form.one("complete.validate", function(evt, is_valid) {				
			if(is_valid)
			{
				$.post("/api/contact/general", $frm.serialize(), function(response) {															
					$("#contact-container").fadeOut(500, function() {
						$("#thanks").fadeIn();
					});
				});
			}
		});
		
		if(!$form.data("validation")) // setup validation b/c not set up yet
		{
			$form.validation({
				"rules": [
					{
						elt: "input[name=name]",
						rule: /[\w\d\s- ]{2,}/,
						onerror: function(evt, $elt)
						{
							$elt.parent().find(".icon-error").text("Please enter your name.");
						}
					},
					{
						elt: "input[name=email]",
						rule: $.validation.EMAIL,
						onerror: function(evt, $elt)
						{
							$elt.parent().find(".icon-error").text("Invalid Email Address.")
						}
					},
					{
						elt: "textarea",
						rule: /[\w\d\s- ]{5,}/,
						onerror: function(evt, $elt)
						{
							$elt.parent().find(".icon-error").text("Please enter your comments/questions.")
						}
					}
				],
				checkevent: "submit",
			});
		}	
		
		$("#frm-contact").find(".btn-submit").click(function() {		
			$("#frm-contact").trigger("validate"); // validate on submit
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