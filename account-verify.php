<?php require 'application/system/YSSEnvironment.php';?>
<?php YSSPage::Controller('AccountVerifyController.php'); ?>
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
	<link rel="stylesheet" href="/resources/css/account-verify.css?v=1">
</head>
<body>
	<div id="bg">
		<img src="/resources/imgs/bg-views.png" alt="" />
		<img id="bg-default" src="/resources/imgs/bg-default.png" alt="" />
	</div>
	<div id="container">
		<header>
			<a class="peeq" href="/"><img src="/resources/imgs/peeq.png" alt="peeq" /></a>
		</header>
		<article id="main">
			<section class="wrap account-verify">
				<div class="column wide">
					<section class="column-body">
						<div class="column-body-inner">
							<h1>Account Verified!</h1>
							<h2><em>You will be receiving an email with your password shortly.</em></h2>
							<h3>So fire up that rusty old email client and come on in.  Meanwhile we'll redirect you to the log in page.</h3>								
						</div>
					</section>
				</div>
			</section>	
		</article>
	</div>
	
	<?php include('application/templates/tracking.php');?>
	
	<? if("release" == YSSConfiguration::applicationConfiguration()):?>
	<script src="/resources/js/account-verify.min.js"></script>
	<?else: ?>
	<script src="/resources/js/src/peeq/account-verify.js"></script>
	<?endif;?>
</body>
</html>
