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
				<div class="breadcrumbs">
					<a class="project-title" href="/#">Projects</a> &raquo; <a class="project-title" href="/#/lucy">Lucy</a>
					<a href="#" class="delete btn-modal modal-view-delete">Delete</a>
				</div>								
				<ul id="view-detail">
					<li class="detail">
						<a href="/#/lucy/logout/default/edit" class="img-preview">
							<img src="/resources/img/fpo-comp-preview.jpg" alt="" />
						</a>
						<h2 class="editable" id="editable-label">Logout</h2>		
						<div class="description editable-textarea" id="editable-description">
							lorem ipsum<br />lorem ipsum lorem ipsum<br /><br />lorem ipsum<br /><a href="http://google.com">link</a>
						</div>
					</li>
					<li class="modules">
						<div class="module module-created">
							<p class="author">Uploaded by bross</p>
							<p class="created_at">7/16/2010 at 4:30pm</p>
						</div>
						<div class="module module-progress">
							<span class="border-left">
								<span class="border-arrow-left"></span>								
							</span>
							<span class="border-right">
								<span class="border-arrow-right"></span>
							</span>
							<div class="progress-bar">																						
								<div class="progress-value">
									<span class="value">34</span>
									<span class="percentage">%</span>
								</div>
							</div>	
							<p><span class="col">Completed:</span><span class="tasks-completed col">{{completed}}</span></p>
							<p><span class="col">Total:</span><span class="tasks-total col">{{total}}</span></p>
						</div>
						<div class="module module-attachments">
							<div class="module-header open">
								<a href="#" class="toggle no-dp">&gt;</a>
								<h3>Attachments</h3>
								<a href="#" class="add no_ btn-modal modal-view-add-attachment">
									<img src="/resources/img/icon-attachment-add.png" alt="" />
									Add
								</a>
							</div>
							<div class="module-body">
								<ol>
									<li>
										<a href="#"><span class="bullet">1</span>wireframe</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">2</span>functional spec</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">3</span>technical spec</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">4</span>documentation</a>
										<a href="#" class="delete">delete</a>
									</li>
								</ol>
							</div>
						</div>
						<div class="module module-tasks">
							<div class="module-header open">
								<a href="#" class="toggle no-dp">&gt;</a>
								<h3>Tasks</h3>
								<ul class="filters">
									<li><a href="#" class="filter-open">open</a></li>
									<li><a href="#" class="filter-closed">closed</a></li>
									<li><a href="#" class="filter-created-by-me">created by me</a></li>
									<li><a href="#" class="filter-assigned-to-me">assigned to me</a></li>
									<li><a href="#" class="on filter-all">all</a></li>									
								</ul>
							</div>
							<div class="module-body">
								<ol>
									<li class="open">
										<a href="#"><span class="bullet">1</span>header</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
									<li class="open">
										<a href="#"><span class="bullet">2</span>footer</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
									<li class="closed">
										<a href="#"><span class="bullet">3</span>nav</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
									<li class="created-by-me open">
										<a href="#"><span class="bullet">4</span>logo</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
									<li class="created-by-me closed">
										<a href="#"><span class="bullet">5</span>created by me</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
									<li class="assigned-to-me closed">
										<a href="#"><span class="bullet">6</span>assigned to me</a>
										<a href="#" class="delete">delete</a>
										<a href="#" class="status">reopen</a>										
									</li>
								</ol>
							</div>
						</div>
						<div class="module module-notes">
							<div class="module-header open">
								<a href="#" class="toggle no-dp">&gt;</a>
								<h3>Notes</h3>	
							</div>
							<div class="module-body">
								<ol>
									<li>
										<a href="#"><span class="bullet">5</span>make logo bigger</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">6</span>increase font size</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">7</span>make it pop</a>
										<a href="#" class="delete">delete</a>
									</li>
									<li>
										<a href="#"><span class="bullet">8</span>more color</a>
										<a href="#" class="delete">delete</a>
									</li>
								</ol>
							</div>
						</div>
						<div class="module module-states">
							<div class="module-header open">
								<a href="#" class="toggle no-dp">&gt;</a>
								<h3>States</h3>
								<a href="#" class="add btn-modal modal-view-add-state">
									<img src="/resources/img/icon-states-add.png" alt="" />
									Add
								</a>
							</div>
							<div class="module-body">
								<ol>
									<li><a href="/#/lucy/logout/default">Default</a> <a href="#" class="delete btn-modal modal-view-delete">Delete</a></li>
									<li><a href="/#/lucy/logout/login">Log in</a> <a href="#" class="delete btn-modal modal-view-delete">Delete</a></li>
								</ol>
							</div>
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
			</div>
		</div>
	</div>
</body>
</html>
