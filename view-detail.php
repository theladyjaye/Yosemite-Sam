<?php
	require 'application/system/YSSEnvironment.php';
	//YSSPage::Controller('ViewsController.php');
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("application/templates/head.php"); ?>
</head>
<body>
	<? include("application/templates/header.php"); ?>
	<div id="container">
		<div id="body">
			<div class="body-content">
				<ul id="view-detail">
					<li class="detail">
						<a href="/#/lucy/logout/default">
							<img class="img-preview" src="/resources/img/fpo-comp-preview.jpg" alt="" />
						</a>
						<h2>Logout</h2>		
						<div class="description">
							<p>lorem ipsum...</p>
						</div>
					</li>
					<li class="modules">
						<div class="module module-created">
							<p class="author">Uploaded by bross</p>
							<p class="created_at">7/16/2010 at 4:30pm</p>
						</div>
						<div class="module module-progress">
							<div class="progress-bar">																						
								<div class="progress-value">
									<span class="value">34</span>
								</div>
							</div>	
							<p><span class="col">Completed:</span><span class="tasks-completed col">{{completed}}</span></p>
							<p><span class="col">Total:</span><span class="tasks-total col">{{total}}</span></p>
						</div>
						<div class="module module-tasks">
							<h3>Tasks</h3>
							<ol>
								<li>header</li>
								<li>footer</li>
								<li>nav</li>
								<li>logo</li>
							</ol>
						</div>
						<div class="module module-notes">
							<h3>Notes</h3>
							<ol>
								<li>make logo bigger</li>
								<li>increase font size</li>
								<li>make it pop</li>
								<li>more color</li>
							</ol>
						</div>
						<div class="module module-states">
							<h3>States</h3>
							<ol>
								<li><a href="/#/lucy/logout/default">Default</a> <a href="#" class="delete btn-modal modal-view-delete">Delete</a></li>
								<li><a href="/#/lucy/logout/login">Log in</a> <a href="#" class="delete btn-modal modal-view-delete">Delete</a></li>
							</ol>
						</div>
						<div class="module module-delete">
							<a href="#" class="delete btn-modal modal-view-delete">Delete</a>
						</div>
					</li>
				</ul>
				<?php
					$modal = array(
						"params" => array(
							"what" => "user",
							"user_id" => ""
						)			
					);					
				?>
				<?php include("application/templates/modal-delete.php"); ?>
			</div>
		</div>
		<?php 
		/*			
			$data = array();
			foreach($page->data as $key => $val)
			{
				$view = array(
					"item-name"=> $val->label,
					"percentage"=> YSSUtils::calc_percentage($val->tasks->completed, $val->tasks->total),
					"tasks-closed"=> $val->tasks->completed,
					"tasks-total"=> $val->tasks->total,					
					"attachments" => array(
						
					),
					"desc"=> $val->description
				);
				
				$view["states"] = array();
				foreach($val->states as $stateKey => $stateVal)
				{
					array_push($view["states"], array(
							"item-name" => $stateVal->label,
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					);
				}
				
				array_push($data, $view);
			}	
		
				/*
			$data = array(
				array(
					"item-name"=> "Buzz",
					"percentage"=> "25",
					"tasks-closed"=> ,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "Logged In",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						),
						array(
							"item-name" => "Logged Out",
							"percentage" => "33",
							"tasks-closed" => 33,
							"tasks-total" => 100
						)	
					),
					"attachments" => array(
						
					),
					"desc"=> "<p class='editable-area'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.<br /><br />

					Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Games",
					"percentage"=> "32",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"wireframe", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Home",
					"percentage"=> "49",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "Logged In",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)	
					),
					"attachments" => array(
						"wireframe", "tech spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Learn",
					"percentage"=> "67",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"wireframe", "tech spec", "func spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Media",
					"percentage"=> "95",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"wireframe", "tech spec", "func spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Music",
					"percentage"=> "16",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"wireframe", "tech spec", "func spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "The Stage",
					"percentage"=> "25",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"wireframe", "func spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				),
				array(
					"item-name"=> "Buzz",
					"percentage"=> "55",
					"tasks-closed"=> 84,
					"tasks-total"=> 152,
					"states" => array(
						array(
							"item-name" => "default",
							"percentage" => "50",
							"tasks-closed" => 20,
							"tasks-total" => 40
						)
					),
					"attachments" => array(
						"tech spec", "func spec", "documentation"
					),
					"desc"=> "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mollis lorem ac nunc pretium ut sagittis mi egestas. Nullam ornare iaculis aliquam. Sed et neque dolor. Fusce rhoncus ligula ut felis venenatis pretium. Quisque enim lorem, sodales non mollis sit amet, condimentum a tellus. Curabitur eget augue lectus, et fermentum nisl. Nulla facilisi. Nulla pharetra dui sed libero ornare eget varius nibh sollicitudin. Vestibulum tincidunt pulvinar sodales. Etiam ac orci ut est dictum pharetra. Nam et diam purus. Phasellus ut libero vitae nulla aliquam pellentesque et facilisis enim. Sed sed nunc diam, sed venenatis nunc. Quisque rhoncus pharetra tempor. Suspendisse et elit dolor, ac placerat ipsum. Nulla purus mauris, consectetur nec mattis vitae, vulputate sit amet leo. Maecenas semper enim in tellus pretium vehicula.</p>

					<p>Duis fermentum nulla at justo ultricies eu lobortis ipsum fringilla. Aenean adipiscing neque non nisl faucibus eget tincidunt felis tempor. Fusce rutrum leo sit amet mi dignissim a dictum libero dignissim. Mauris porta metus et nulla varius tempor. Pellentesque libero urna, sodales accumsan blandit sed, accumsan sed augue. Donec</p>"
				)
			);
			*/
			//include("application/templates/body-views.php"); 
		?>
	</div>
	<?php
	/*
		$modal = array(
				"id" => "add-view",
				"title" => "Add View",
				"submit-label" => "Add",
				"action" => "#",
				"body" => <<<MODAL_CONTENTS
					<p>
						<label for="name" class="font-replace">Name</label>					
						<input type="text" name="name" />
					</p>
					<p>
						<label for="description" class="font-replace">Description</label>					
						<textarea name="description" class="small"></textarea>
					</p>					
					<p>
						<label for="parent_view" class="font-replace">Parent View</label>					
						<select name="parent_view">
							<option value="0">Root</option>
							<option value="186453">Home</option>
							<option value="168998">Buzz</option>
						</select>
					</p>
MODAL_CONTENTS
		);	
	?>	
	<?php include("application/templates/modal.php"); ?>
	
	<?php
		$modal = array(
				"id" => "add-attachment",
				"title" => "Add Attachment",
				"submit-label" => "Add",
				"action" => "#",
				"body" => <<<MODAL_CONTENTS
					<p>
						<label for="name" class="font-replace">Name</label>					
						<input type="text" name="name" />
					</p>
					<div class="file-input-container">
						<label for="attachment" class="font-replace">Attachment</label>
						<div class="file-input">						
							<input type="file" name="attachment" class="file" />
							<div class="fakefile">
								<input />
								<img src="resources/img/icon-folder.png" alt="Browse" />
							</div>
						</div>
					</div>
					<p>
						<label for="description" class="font-replace">Description</label>					
						<textarea name="description" class="small"></textarea>
					</p>					
MODAL_CONTENTS
		);	
	?>	
	<?php include("application/templates/modal.php"); ?>
	
	<?php
		$modal = array(
				"id" => "add-state",
				"title" => "Add State",
				"submit-label" => "Add",
				"action" => "#",
				"body" => <<<MODAL_CONTENTS
					<p>
						<label for="name" class="font-replace">Name</label>					
						<input type="text" name="name" />
					</p>
					<div class="file-input-container">
						<label for="attachment" class="font-replace">Image</label>
						<div class="file-input">						
							<input type="file" name="attachment" class="file" />
							<div class="fakefile">
								<input />
								<img src="resources/img/icon-folder.png" alt="Browse" />
							</div>
						</div>
					</div>				
MODAL_CONTENTS
		);	
	?>	
	<?php include("application/templates/modal.php"); ?>
	
	<?php
		$modal = array(
			"params" => array(
				"what" => "user",
				"user_id" => ""
			)			
		);
		*/
	?>
	<?php //include("application/templates/modal-delete.php"); ?>
	
</body>
</html>
