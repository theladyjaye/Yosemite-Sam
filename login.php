<?php
require 'application/system/YSSEnvironment.php'; 
YSSPage::Controller('PageController.php');
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>peeq</title>
	<meta name="description" content="about peeq">
	<meta name="author" content="peeq">
	<link rel="shortcut icon" href="" />
	<link rel="apple-touch-icon" href="" />
	<link rel="stylesheet" href="resources/css/login.css?v=1">
</head>
<body>
	<div id="bg">
		<img src="resources/imgs/bg-views.png" alt="" />
		<img id="bg-default" src="resources/imgs/bg-default.png" alt="" />
	</div>
	<div id="container">
		<header>
			<a class="peeq" href="/"><img src="resources/imgs/peeq.png" alt="peeq" /></a>
		</header>
		<article id="main">
			<section class="wrap login">
				<div class="column">
					<section class="column-body">
						<div class="column-body-inner">	
							<div id="login-container">
								<h1>Login</h1>
								<form id="frm-login" action="" method="post">
									<p class="login-message">Invalid Credientials</p>
									<ul>
										<li class="field">
											<input type="text" name="domain" />
											<label for="domain">Domain</label>
										</li>
										<li class="field">
											<input type="email" name="username" />
											<label for="username">Username/Email</label>
										</li>
										<li class="field">
											<input type="password" name="password" />
											<label for="password">Password</label>
											<a href="#" class="btn-forgot-password forgot-password incomplete">forgot password?</a>
										</li>
									</ul>	
									<a href="#" class="btn btn-submit btn-login left">Login</a>				
								</form>	
							</div>
							<div id="forgot-password-container">	    	
								<h1>Forgot Password</h1>
								<a href="#" class="btn-login-form incomplete">&laquo; Nevermind, I remember.</a>
								<form id="frm-forgot-password" action="" method="post">
									<p class="login-message">Password Sent!</p>
									<ul>
										<li class="field">
											<input type="text" name="domain" />
											<label for="domain">Domain</label>
										</li>
										<li class="field">
											<input type="email" name="email" />
											<label for="username">Email</label>
										</li>
									</ul>	
									<a href="#" class="btn btn-reset-password btn-submit left">Reset Password</a>				
								</form>
							</div>
						</div>
					</section>
				</div>
				<div class="column sidebar">
					<!-- <nav>
					</nav> -->
					<section class="column-body">
						<div class="column-body-inner">
							<h2>Need an account?</h2>									
							<p>Signing up is simple! Swing over to the <a href="/sign-up">sign up page</a> and you'll  be <span class="peeq">peeq</span>ing in no time.</p>
						</div>
					</section>
				</div>
			</section>
		</article>
	</div>	
	<?php include('application/templates/tracking.php');?>
	<?php include('application/templates/footer.php');?>
	
	<?if("release" == YSSConfiguration::applicationConfiguration()):?>
	<script src="resources/js/login.min.js"></script>
	<?else:?>
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.validation.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.lastfieldentersubmit.js"></script>
	<script src="resources/js/src/peeq/peeq.login.index.js"></script>
	<script src="resources/js/src/peeq/peeq.login.js"></script>
	<?endif;?>
</body>
</html>

