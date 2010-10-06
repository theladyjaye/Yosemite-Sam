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
		
		// forgot password / sign-in toggle
		$(".btn-forgot-password").click(function() {
			$("#forgot-password-container").show();
			$("#sign-in-container").hide();
			$("#frm-forgot-password input[name=domain]").focus();
		}).keypress(function() {
			return false;
		});
		
		$(".btn-sign-in-form").click(function() {
			$("#forgot-password-container").hide();
			$("#sign-in-container").show();
			$("#frm-sign-in input[name=domain]").focus();
		});
		
		 // whenever a user presses enter in the last input field of the form it will fire .btn-submit (submitting the form) 
		$("form").lastfieldentersubmit({
			submit: function($frm) {
				$frm.find(".btn-submit").click();
			}
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
	
	var signin_validation = function() 
	{
		$("#frm-sign-in .btn-sign-in").click(function() {
			var $frm = $("#frm-sign-in"),
				$error_msg = $frm.find(".error-message");

			$("#forgot-password-container").hide();  // fix bug when pressing enter
			$("#sign-in-container").show();

			
			$.post("/api/account/login", $frm.serialize(), function(response) {
				response = $.parseJSON(response);
				if(response.ok)
				{
					// success
					$error_msg.css({"visibility": "hidden"});									
					// proceed
					document.location.href = "http://" + response.user.domain + ".yss.com";	
				}
				else 
				{
					// error
					$error_msg.css({"visibility": "visible"});					
				}
			});
			
			return false;
		});
	}
	
	var forgotpassword_validation = function() 
	{
		$("#frm-forgot-password .btn-reset-password").click(function() {
			var $frm = $("#frm-forgot-password"),
				domain = $frm.find("input[name=domain]").val(),
				email = $frm.find("input[name=email]").val();
				
			if(domain && email)
			{			
				$.post("/api/account/" + domain + "/users/reset/" + email, $frm.serialize(), function(response) {
					// proceed
					$(".btn-sign-in-form").click();
					$(".msg-password-sent").css({"visibility": "visible"});
				
					var timer = setTimeout(function() {
						$(".msg-password-sent").css({"visibility": "hidden"});
						clearTimeout(timer);
					}, 3000);
				});
			}
			return false;
		});
	}
	
	// PUBLIC --------------------------------
	this.main = function() 
	{
		// transition in
		transition_in();
		
		// register events
		register_events();
		
		if(document.location.hash == "#login") // if deeplink to login then give focus to sign in form
		{
			$("#frm-sign-in").find("input:eq(0)").focus();
		}
		else
		{
			// first sign up field gets focus
			$("input:eq(0)").focus();
		}
		
		// setup sign up validation
		signup_validation();
		
		// setup sign in validation
		signin_validation();	
		
		// setup forgot password validation
		forgotpassword_validation();
		
	};
};

var peeq = new peeq();
peeq.main();