<?php
	require 'application/system/YSSEnvironment.php';
	YSSPage::Controller('ProjectsController.php');
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("application/templates/head.php"); ?>
</head>
<body>
	<div id="container">
		<?php include("application/templates/header.php"); ?>
		<?php 		
			$data = array();
			foreach($page->data as $key => $val)
			{
				array_push($data, array(
					"item-name" => $val->label,
					"tasks-total" => $val->tasks->total,
					"tasks-closed" => $val->tasks->completed,
					"views-count" => $val->views,
					"percentage" => YSSUtils::calc_percentage($val->tasks->completed, $val->tasks->total),
					"desc" => $val->description
				));				
			}		
			include("application/templates/body-projects.php"); 
		?>
	</div>
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
</body>
</html>
