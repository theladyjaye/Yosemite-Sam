<?php $debug = false; ?>

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
	<link rel="stylesheet" href="resources/css/coming-soon.css?v=1">
</head>
<body>
	<div id="bg"></div>
	<div id="main">		
		<div class="column">
			<h1>peeq ... <em>coming soon</em></h1>
		</div>
		
		<div class="column">
			<div id="view-sign-up-container">
				<div id="view-sign-up">			
					<p id="about"><span class="peeq">peeq</span> is an <strong>online service</strong> that will shape the way you consider <strong>project development</strong> creating an environment for team collaboration from design to engineering to launch.</p>
	
					<div id="first-to-know">
						<p>Sign up to take a sneeq <span class="peeq">peeq</span>:</p>
						<form id="frm-signup" method="post" action="">
							<ul>
								<li class="field">
									<input type="email" name="email" autocomplete="off" />
									<span class="hint">me@domain.com</span>
									<span class="error">Invalid Email Address</span>
								</li>
								<li>
									<a class="btn btn-submit" href="#">Sign up</a>
								</li>
							</ul>
						</form>
					</div>
				</div>	
				<div id="annotation-container">
					<div class="annotation">
						<div class="ui-resizeable-handle icon icon-resizer"></div>
						<div class="border"></div>
						<div class="overlay"></div>
					</div>
					<span class="annotation-num">1</span>
					<span class="cursor cursor-pointer"></span>
				</div>
			</div>	
			<div id="view-signed-up-container">
				<p>Glad we could <span class="peeq">peeq</span> your interest!</p>
				<p>You’ll hear from us shortly.  Till then be sure to follow <a href="http://twitter.com/peeqservice">@peeqservice</a> on twitter.</p>
			</div>	
		</div>
	</div>
	
	<?if($debug):?>
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.easing.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/peeq/peeq.coming-soon.js"></script>
	<?else:?>
	<script src="resources/js/coming-soon.min.js"></script>
	<?endif;?>
</body>
</html>