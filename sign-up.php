<?php 
 
// redirect to coming soon page for beta
header('Location: /coming-soon');

require 'application/system/YSSEnvironment.php'; 
YSSPage::Controller('PageController.php'); 
?>
<!DOCTYPE HTML>
<? //<html manifest="peeq.cache">?>
<html>
<head>
	<meta charset="utf-8">
	<title>peeq | Sign up</title>
	<meta name="description" content="about peeq">
	<meta name="author" content="peeq">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<link rel="shortcut icon" href="" />
	<link rel="apple-touch-icon" href="" />
	<link rel="stylesheet" href="resources/css/sign-up.css?v=1">
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
						<li><a href="/">Home</a></li>
						<li><a class="on" href="/sign-up">Sign up</a></li>
						<li><a href="/#login" class="btn-modal modal-view-login">Login</a></li>
					</ul>
				</nav> 
			</section>
		</header>
		<article id="main">
			<section class="wrap sign-up">
				<div class="column">
					<section class="column-body">
						<div class="column-body-inner">	
							<div id="sign-up-container" class="view">										
								<h2><em>See what all the <a href="/#buzz" class="incomplete">buzz</a> is about.</em></h2>
								<h2>Sign up for <span class="peeq">peeq</span>!</h2>
								<form id="frm-sign-up" action="" method="post">
									<ul>
										<li>
											<ul>
												<li class="field">								
													<input type="text" name="firstname" maxlength="15" />
													<label for="firstname">First Name</label>
													<span class="hint">The name you go by.</span>
													<span class="icon icon-success"></span>
													<span class="icon icon-error"></span>
												</li>
												<li class="field">								
													<input type="text" name="lastname" maxlength="15" />
													<label for="lastname">Last Name</label>
													<span class="hint">The family name...</span>
													<span class="icon icon-success"></span>
													<span class="icon icon-error"></span>
												</li>
											</ul>
										</li>
										<li class="field">
											<input type="text" name="username" maxlength="15" />
											<label for="username">Username</label>
											<span class="hint">your <span class="peeq">peeq</span> login...</span>
											<span class="icon icon-success"></span>
											<span class="icon icon-error"></span>
										</li>
										<li class="field">
											<input type="email" name="email" />
											<label for="email">Email</label>
											<span class="hint">We don't spam.</span>
											<span class="icon icon-success"></span>
											<span class="icon icon-error"></span>
										</li>
										<li class="field">
											<input type="text" name="company" maxlength="15" />
											<label for="company">Company</label>
											<span class="hint">Who will be <span class="peeq">peeq</span>ing?</span>
											<span class="icon icon-success"></span>
											<span class="icon icon-error"></span>
										</li>
										<li class="field-domain">
											<div>
												<input type="text" name="domain" maxlength="12"/>								
												<span class="domain">.peeqservice.com</span>
												<p>
													<label for="domain">Domain</label>
													<span class="icon icon-success"></span>
													<span class="icon icon-error"></span>
												</p>
											</div>														
										</li>
									</ul>
									<a href="#" class="btn btn-signup btn-submit left clearboth">Sign Up</a>
								</form>
							</div>
							<div id="confirmation" class="view">
								<h2><em>Glad we could <span class="peeq">peeq</span> your interest!</em></h2>
								<h2>You'll be receiving an email shortly.</h2>
							</div>
						</div>
					</section>
				</div>
				<div class="column sidebar">
					<!-- <nav>
					</nav> -->
					<section class="column-body">
						<div class="column-body-inner">
							<h2>Signing up is simple!</h2>
							<ol>
								<li>Enter your name.</li>
								<li>Enter your username.</li>
								<li>Enter your email.</li>
								<li>Enter your company name.</li>
								<li>Enter your domain.</li>
							</ol>			
							<p>Once you setup your account you'll receive an email asking you to verify your account.</p>
						</div>
					</section>
				</div>
			</section>
			<?php include('application/templates/login.php');?>
		</article>
	</div>
	<?php include('application/templates/footer.php');?>	
	<?php include('application/templates/tracking.php');?>
	
	<? if("release" == YSSConfiguration::applicationConfiguration()):?>
	<script src="resources/js/sign-up.min.js"></script>
	<?else: ?>
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/plugins/jqModal.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.easing.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.validation.js"></script>
	<script src="resources/js/src/peeq/peeq.sign-up.js"></script>
	<script src="resources/js/src/peeq/peeq.login.js"></script>
	<?endif;?>
</body>
</html>

