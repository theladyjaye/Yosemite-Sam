<?php
	require 'application/system/YSSEnvironment.php';
	YSSPage::Controller('ViewsController.php');
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
				<div class="breadcrumbs">
					<a class="project-title" href="/#">Projects</a>
				</div>
				<ul id="table-list">
					<li>
						<?php YSSUI::BTN_ADD_VIEW('#', 'btn-img');?>
					</li>
					<?=$page->data?>
				<? /*
					<li>
						<?php YSSUI::BTN_ADD_VIEW('#', 'btn-img');?>
					</li>
					<?for($i = 0; $i < 5; $i++):?>
					<li>
						<a class="btn-img dp" href="#/lucy/logout">
							<img src="/resources/img/fpo-comp-thumb.jpg" alt="" />
						</a>
						<a class="btn-delete delete btn-modal modal-view-delete" href="">Delete</a>						
						<h2 class="">{{label}}</h2>
					<? /*	{{#tasks}}  ?>
						<p class="view-info">
							{{views}} views
						</p>
						<div class="progress-bar">
							<div class="progress-value">
								<span class="value">34</span>
								<span class="percentage-sign">%</span>
							</div>
							<p class="task-info">
								<span class="tasks-completed">{{completed}}</span>/<span class="tasks-total">{{total}}</span>
							</p>					
						</div>						
					<? /*	{{/tasks}}	 ?>
						<div class="description">
							<p>{{description}}</p>
						</div>
					</li>
					<?endfor;?>
					*/ ?>
				</ul>
				<?php
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
				?>
				<?php include("application/templates/modal-delete.php"); ?>				
			</div>
		</div>
	</div>
</body>
</html>
