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
	
	var signup_validation = function() 
	{
		$("#frm-sign-up").validation({
			rules: [
				{
					elt: "input[name=firstname]",
					rule: /\w{2,}/
				},
				/*
				{
					elt: "input[name=lastname]",
					rule: /\w{2,}/
				},
				*/
				{
					elt: "input[name=lastname]",
					rule: "custom",
					success: function(evt, $elt)
					{
						return ($elt.val() == "test");
					}
				},
				{
					elt: "input[name=username]",
					rule: "ajax",
					type: "post",
					url: "/handler.php",
					data: "username",
					success: '{"ok":true}'					
				},
				{
					elt: "input[name=email]",
					rule: $.validation.EMAIL
				},
				{
					elt: "input[name=password]",
					rule: /\w{2,}/
				},
				{
					elt: "input[name=passwordmatch]",
					rule: "match",
					matching_field: "input[name=password]",
					checkevent: "blur"
				},
				{
					elt: "input[name=company]",
					rule: /\w{2,}/
				},
				{
					elt: "input[name=domain]", /* change to ajax */
					rule: /\w{2,}/
				}
			],
			checkevent: "keyup",
			onerror: function(evt, $elt)
			{
				var data = $("#frm-sign-up").data("validation");
				if(!data.is_validating && (!$elt.hasClass("error") && !$elt.hasClass("success")) && $elt.val().length < 2)
				{
					return false;
				}
				return true;
			}
		}).find("input[name=password]").keypress(function() {
			 // clear matching password if changing password and matching password has already been entered 
			var $password_match = $(this).parents("form").find("input[name=passwordmatch]");
			if($password_match.val().length)
			{
				$password_match.val("").removeClass("success error");
			}
		}).end().bind("complete.validate", function(evt, is_valid) {
			console.log(is_valid);
			
			if(is_valid)
			{
				// submit form
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
			/*
			$.post("/login", $frm.serialize(), function(response) {
				if(response.ok)
				{
					// success
					$error_msg.hide();
					// proceed
				}
				else 
				{
					// fail
					$error_msg.html(reponse.error).fadeIn();					
				}
			});
			*/
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
		
		// first sign up field gets focus
		$("input:eq(0)").focus();
		
		// setup sign up validation
		signup_validation();
		
		// setup sign in validation
		signin_validation();	
		
	};
};

var peeq = new peeq();
peeq.main();