<? 
$sfw 	= false; // safe for work
$debug 	= true;
$peak	= "peak";
$peeq 	= "peeq";


if($sfw)
{
	$peak = "!!!!";
	$peeq = "oooo";
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$peeq?></title>
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
		<div id="left">
			<h1 <?if($sfw):?>class="hide"<?endif;?>>Logo ... <em>coming soon</em></h1>
		</div>
		
		<div id="right">
			<p id="about"><span class="peeq"><?=$peeq?></span> is an <strong>online service</strong> that will shape the way you consider <strong>project development</strong> creating an environment for team collaboration from design to engineering to launch.</p>
	
			<div id="first-to-know">
				<p>Be the first to know when <?=$peak?> @ <span class="peeq"><?=$peeq?></span></p>
				<form method="post" action="">
					<ul>
						<li class="field">
							<input type="email" name="email" />
							<span class="hint">me@domain.com</span>
							<span class="icon icon-success"></span>
							<span class="icon icon-error"></span>
						</li>
						<li>
							<a class="btn" href="#">Let me know</a>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
	
	<?if($debug):?>
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/peeq/peeq.coming-soon.js"></script>
	<?else:?>
	<script src="resources/js/coming-soon.min.js"></script>
	<?endif;?>
</body>
</html>