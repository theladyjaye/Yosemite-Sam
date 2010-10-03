<?php require 'application/system/YSSEnvironment.php'; ?>
<?
if(isset($_SESSION['YSS']))
{
	$current_user = $_SESSION['YSS']['currentUser'];
}
else
{
	header("Location: /sign-up.php");
}
?>
<!DOCTYPE HTML>
<? //<html manifest="peeq.cache">?>
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
			<a class="btn btn-back" href="#">Back to Details</a>
			<section>
				<h1 class="username">G'day <a href="#/settings"><?=$current_user->firstname?></a></h1>
				<nav>
					<ul>
						<li><a href="#/settings">Settings</a></li>
						<li id="btn-logout" class="logout"><a href="#">Logout</a></li>
					</ul>
				</nav>
			</section>
		</header>
		<article id="main">
			
		</article>
	</div>
	<? /*
	<div id="loading">
		Loading
	</div>
	*/ ?>
	<div id="network-connectivity">
		<p class="icon icon-offline" title="You are offline"></p>
	</div>
	<footer>
		<section class="wrap">
			<p>Copyright &copy; 2010 <a href="/" class="peeq">peeq</a>. All rights reserved.</p>
			<nav>
				<ul>
					<li><a href="#">Twitter</a> |</li>
					<li><a href="#">Contact</a></li>
				</ul>
			</nav>
		</section>
	</footer>
	
	<?php include('application/templates/tracking.php');?>
	
	<?if("release" == YSSConfiguration::applicationConfiguration()):?>
	<script src="resources/js/script.min.js"></script>
	<?else:?>
	<script src="resources/js/src/raphael/raphael.js"></script>
	<script src="resources/js/src/jquery/jquery.js"></script>
	<script src="resources/js/src/jquery/jquery-ui.custom.min.js"></script>
	<script src="resources/js/src/raphael/plugins/raphael.piechart.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.piechart.js"></script>
	<script src="resources/js/src/jquery/plugins/jqModal.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.easing.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.tmpl.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.polling.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.tablesorter.min.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.toggle_form_field.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.annotate.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.jeditable.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.philter.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.filestyle.js"></script>
	<script src="resources/js/src/jquery/plugins/jqueryui.selectmenu.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.numeric.js"></script>
	<script src="resources/js/src/jquery/plugins/jquery.validation.js"></script>
	<script src="resources/js/src/sammy/sammy.min.js"></script>
	<script src="resources/js/src/sammy/plugins/sammy.cache.js"></script>
	<script src="resources/js/src/sammy/plugins/sammy.json.js"></script>
	<script src="resources/js/src/sammy/plugins/sammy.storage.js"></script>
	<script src="resources/js/src/sammy/plugins/sammy.template.js"></script>
	<script src="resources/js/src/sammy/plugins/sammy.title.js"></script>
	<script src="resources/js/src/peeq/peeq.class.js"></script>
	<script src="resources/js/src/peeq/peeq.api.js"></script>
	<script src="resources/js/src/peeq/peeq.forms.logged-in.js"></script>
	<script src="resources/js/src/peeq/peeq.editable.js"></script>
	<script src="resources/js/src/peeq/peeq.annotate.js"></script>
	<script src="resources/js/src/peeq/peeq.utils.js"></script>
	<script src="resources/js/src/peeq/main.js"></script>
	<?endif;?>
</body>
</html>

