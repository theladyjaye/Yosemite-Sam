<!DOCTYPE html>
<html>
<head>
	<?php include("application/templates/head.php"); ?>
</head>
<body class="view-editor">
	<div id="container">
		<?php
			$is_views = true;
		 	include("application/templates/header.php"); 
		?>
		<?php 			
			$data = array(
				"percentage"=> "55",
				"tasks-closed"=> 84,
				"tasks-total"=> 152,
			);

			include("application/templates/body-editor.php"); 
		?>
	</div>
	<div id="view-body-editor">
		<div id="view-body-editor-image">
			<img src="resources/img/fpo-comp.jpg" alt="" width="1200" />		
		</div>
	</div>
	
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
					<p>
						<label for="attachment" class="font-replace">Attachment</label>
						<input type="file" name="attachment" />
					</p>
					<p>
						<label for="description" class="font-replace">Description</label>					
						<textarea name="description" class="small"></textarea>
					</p>					
MODAL_CONTENTS
		);	
	?>	
	<?php include("application/templates/modal.php"); ?>
</body>
</html>
