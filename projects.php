<!DOCTYPE html>
<html>
<head>
	<?php include("application/templates/head.php"); ?>
</head>
<body>
	<div id="container">
		<?php include("application/templates/header.php"); ?>
		<?php 
			$data = array(
				array(
					"item-name"=> "Apple",
					"percentage"=> "55",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"views-count"=> 7,
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Gaiaki",
					"percentage"=> "13",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"views-count"=> 7,
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Guitar Hero",
					"percentage"=> "60",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"views-count"=> 7,
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Microsoft",
					"percentage"=> "25",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"views-count"=> 7,
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Wunderman",
					"percentage"=> "5",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"views-count"=> 7,
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				)
			);
			include("application/templates/body.php"); 
		?>
	</div>
</body>
</html>