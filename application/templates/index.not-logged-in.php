<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>peeq</title>
	<meta name="description" content="about peeq">
	<meta name="author" content="peeq">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<link rel="shortcut icon" href="" />
	<link rel="apple-touch-icon" href="" />
	<link rel="stylesheet" href="resources/css/logged-in.css?v=1">
</head>
<body>
	<div id="bg">
		<img src="resources/imgs/bg-views.png" alt="" />
		<img id="bg-default" src="resources/imgs/bg-default.png" alt="" />
	</div>
	<div id="container">
		<header>
			<a class="peeq" href="/"><img src="resources/imgs/peeq.png" alt="peeq" /></a>
			<section>
				<nav>
					<ul>
						<li><a href="/home">Home</a></li>
						<li><a class="on" href="/sign-up">Sign up</a></li>
						<li><a href="/#login" class="btn-modal modal-view-login">Login</a></li>
					</ul>
				</nav> 
			</section>
		</header>
		<article id="main" style="opacity: 1">
			This is the not logged in homepage. TBD.
			<?php include('/application/templates/login.php');?>
		</article>
	</div>
	
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/plugins/jqModal.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.easing.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.validation.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.lastfieldentersubmit.js"></script>
	<script src="resources/js/src/peeq/peeq.sign-up.js"></script>
	<script src="resources/js/src/peeq/peeq.login.js"></script>
</body>
</html>