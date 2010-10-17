<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>peeq</title>
	<meta name="description" content="about peeq">
	<meta name="author" content="peeq">
	<link rel="shortcut icon" href="" />
	<link rel="apple-touch-icon" href="" />
	<link rel="stylesheet" href="resources/css/not-supported.css?v=1">
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
			<section class="wrap not-supported">
				<div class="column wide">
					<section class="column-body">
						<div class="column-body-inner">	
							<?if($_REQUEST['mobile'] == "true"):?>
							<h2>At this moment <span class="peeq">peeq</span> is not ready for mobile.</h2>
							<?else:?>
							<h2>We believe in using modern browsers for <span class="peeq">peeq</span>.</h2>
							<h3>We suggest using any of the following:</h3>
							<ul>
								<li><a href="http://www.google.com/chrome" target="_blank">Google Chrome</a></li>
								<li><a href="http://www.apple.com/safari/" target="_blank">Apple Safari</a></li>
								<li><a href="http://www.mozilla.com/" target="_blank">Mozilla Firefox</a></li>
							</ul>
							<?endif;?>
						</div>
					</section>
				</div>
			</section>
		</article>
	</div>	
	<?php include('application/templates/tracking.php');?>
</body>
</html>

