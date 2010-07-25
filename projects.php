<?php
	require 'application/system/YSSEnvironment.php';
	YSSPage::Controller('ProjectsController.php');
?>

<!DOCTYPE html>
<html>
<head>
	<? include("application/templates/head.php"); ?>
</head>
<body>
	<? include("application/templates/header.php"); ?>
	<div id="container">
		<div id="body">
			<div class="body-content">				
				<ul id="table-list" class="">
					<li>
						<?php YSSUI::BTN_ADD_PROJECT('#', 'btn-img');?>
					</li>
					<?=$page->data?>				 									
				</ul>
				<?php
					$modal = array(
							"id" => "add-project",
							"title" => "Add Project",
							"submit-label" => "Add",
							"action" => "#",
							"body" => <<<MODAL_CONTENTS
								<p>
									<label for="label" class="font-replace">Name</label>
									<span class="error">Oh noes! Name taken.</span>					
									<input type="text" name="label" />
								</p>
								<p>
									<label for="description" class="font-replace">Description</label>					
									<span class="error">Describe Me. Please!</span>
									<textarea name="description"></textarea>
								</p>					
MODAL_CONTENTS
					);	
				?>	
				<?php include("application/templates/modal.php"); ?>


				<?php
					$modal = array(
						"params" => array(
							"what" => "project",
							"id" => ""
						)			
					);
				?>
				<?php include("application/templates/modal-delete.php"); ?>
			</div>
		</div>
	</div>
</body>
</html>
