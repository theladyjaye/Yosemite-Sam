<!DOCTYPE html>
<html>
<head>
	<? include("application/templates/head.php"); ?>
	<style type="text/css">
		body {margin: 0; padding: 0;}
		#container {margin: 0 auto; float: left; display: inline; position: relative; width: 100%; margin: 0 0 0 -30px;}
			#container ul {margin: 0 auto; width: 970px;}			
		li {width: 280px; float: left; display: inline; margin: 0px; padding: 20px; -webkit-transition: width, clear, margin, padding .4s ease-out; -moz-transition: width, clear, margin, padding .4s ease-out;}
		.list li {width: 100%; clear: left; margin: 10px 0; padding: 0; -webkit-transition: width, clear, margin, padding .4s ease-out; -moz-transition: width, clear, margin, padding .4s ease-out;}
		li .img {width: 280px; height: 250px; border: 1px solid #ccc; float: left; clear: left; margin: 0 10px 10px 0; padding: 10px;}
		.list li .img {clear: none;}
		a.img:hover {background: #eee;}
		
		.btn {position: absolute; top: 0; left: 0; z-index: 999;}
	</style>
	
	
	<script type="text/javascript">
		$(function() {
			$(".btn").click(function() {
				$("#container").toggleClass("list");
			});
		});
	</script>
</head>
<body>
	<a href="#" class="btn">toggle</a>
	<div id="container">
		<ul>
			<?for($i = 0; $i < 25; $i++):?>
			<li>
				<a class="img dp" href="#/item<?=$i?>">
				</a>
				<h2>item <?=$i?></h2>				
				<p class="content">
					lorem ipsum
				</p>
			</li>
			<?endfor;?>
		</ul>
	</div>
</body>
</html>