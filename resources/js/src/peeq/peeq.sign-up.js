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
		});

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
	
	var signup_validation = function() 
	{
		$("#frm-sign-up").validation({
			rules: [
				{
					elt: "input[name=firstname]",
					rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/
				},
				{
					elt: "input[name=lastname]",
					rule: /^[a-zA-Z]{2,}[a-zA-Z ]{0,}$/
				},				
				{
					elt: "input[name=username]",
					rule: /^[\w\d]{4,}/				
				},
				{
					elt: "input[name=email]",
					rule: $.validation.EMAIL
				},
				{
					elt: "input[name=company]",
					rule: /\w{2,}/
				},
				{
					elt: "input[name=domain]", /* change to ajax */
					rule: /^[a-zA-Z0-9-]+$/
				}
			],
			checkevent: "submit",
			onerror: function(evt, $elt)
			{
				var data = $("#frm-sign-up").data("validation");
				if(!data.is_validating && (!$elt.hasClass("error") && !$elt.hasClass("success")) && $elt.val().length < 2)
				{
					return false;
				}
				return true;
			}
		}).bind("complete.validate", function(evt, is_valid) {			
			if(is_valid)
			{
				$.post("/api/account/register", $("#frm-sign-up").serialize(), function(response) {
					response = $.parseJSON(response);
					if(response.ok)
					{
						$("#sign-up-container").fadeOut(200, function() {
							$("#confirmation").fadeIn();							
						});
					}
					else
					{
						if(response.errors)
						{					
							for(var i = 0, len = response.errors.length, $field; i < len; i++)
							{
								$field = $("#frm-sign-up").find("input[name=" + response.errors[i].key + "]").parents("li");
								$field.find(".icon-success").hide();
								$field.find(".icon-error").html(response.errors[i].message).show();
							}
						}
					}
				});
			}
		}).find(".btn-signup").click(function() {			
			$("#frm-sign-up").trigger("validate"); // validate on submit
			return false;
		});
	};
	
	
	
	// PUBLIC --------------------------------
	this.main = function() 
	{
		// transition in
		transition_in();
		
		// register events
		register_events();
		
		// first sign up field gets focus
		$("input:eq(0)").focus();
		
		// setup sign up validation
		signup_validation();
	};
};

var peeq = new peeq();
peeq.main();