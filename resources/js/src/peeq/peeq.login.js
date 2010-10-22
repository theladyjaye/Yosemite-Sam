peeq.login = 
{
	main: function() 
	{
		peeq.login.register_events();
		peeq.login.on_deeplink();
	},	
	on_deeplink: function() 
	{
		if(document.location.hash == "#login") // if deeplink to login then give focus to sign in form
		{
			$(".btn-modal.modal-view-login").click();
			$("#frm-login").find("input:eq(0)").focus();
		}
	},	
	register_events: function() 
	{
		// setup modal
		peeq.login.setup_modal();		
		
		// nav
		$("a[href$='#login']").click(function() {
			return false;
		});
		
		// forgot password / login toggle
		$(".btn-forgot-password").click(function() {
			$("#forgot-password-container").show();			
			$("#login-container").hide();
			$(".modal-view-login").find(".title").text("Forgot Password?");
			$("#frm-forgot-password input[name=domain]").focus();
		}).keypress(function() {
			return false;
		});

		$(".btn-login-form").click(function() {
			$("#forgot-password-container").hide();
			$("#login-container").show();
			$(".modal-view-login").find(".title").text("Login");
			$("#frm-login input[name=domain]").focus();
		});
		
		// setup validation
		peeq.login.login_validation();
		peeq.login.forgotpassword_validation();
	},	
	setup_modal: function()
	{	
		if($(".modal").length)
		{	
			$(".modal").addClass("jqmWindow").jqm({
				overlay: 90,
				trigger: false,
				closeClass: "btn-modal-close",
				onShow: function(hash) {
					hash.w.css({
						"top": "-1000px",
						"display": "block",
						"opacity": 0
					}).animate({
						"top": "7%",
						"opacity": 1
					}, 300, "easeOutQuad");

					$("input").toggle_form_field();
				},
				onHide: function(hash) {
					hash.w.animate({
						"top": "-1000px",
						"opacity": 0
					}, 150, "easeOutQuad");

					hash.o.fadeOut(150, function() {
						$(this).remove();
					});
				}
			});

			$(".btn-modal").click(function() {
				$(".modal.modal-view-login").jqmShow();
				return false;
			});
		}
	},	
	login_validation: function() 
	{
		$("#frm-login .btn-login").click(function() {
			var $frm = $("#frm-login"),
				$error_msg = $frm.find(".login-message");

			$("#forgot-password-container").hide();  // fix bug when pressing enter
			$("#login-container").show();

		
			$.post("/api/account/login", $frm.serialize(), function(response) {
				response = $.parseJSON(response);
				if(response.ok)
				{
					// success
					$error_msg.hide()									
					// proceed
					document.location.href = "http://" + response.user.domain + ".yss.com";	
				}
				else 
				{
					// error
					$error_msg.fadeIn();		
				}
			});
		
			return false;
		});
	},	
	forgotpassword_validation: function() 
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
					$frm.find(".login-message").fadeIn();
			
					var timer = setTimeout(function() {
						$frm.find(".login-message").fadeOut();
						$frm[0].reset();
						clearTimeout(timer);
					}, 3000);
				});
			}
			return false;
		});
	}
};

$(function() {
	peeq.login.main();
});