<?php
	require 'application/system/YSSEnvironment.php';
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("application/templates/head.php"); ?>
</head>
<body>
	<?php include("application/templates/header.php"); ?>
	<div id="container">
		<div id="body">
			<div class="body-content">
				<?php 
					$logged_in_user = "bross";
					$data = array(
						array(
							"first-name" => "Adams",
							"last-name" => "John",
							"username"=> "jadams",
							"email" => "jadams@usa.com",
							"user-level" => "admin"					
						),
						array(
							"first-name" => "Abraham",
							"last-name" => "Lincoln",
							"username"=> "alincoln",
							"email"=> "alincoln@usa.com",
							"user-level"=> ""					
						),
						array(
							"first-name" => "Jayson",
							"last-name" => "Padding",
							"username"=> "jsonp",
							"email"=> "jsonp@gmail.com",
							"user-level"=> ""					
						),
						array(
							"first-name" => "Betsy",
							"last-name" => "Ross",
							"username"=> "bross",
							"email"=> "bross@usa.com",
							"user-level"=> "admin"					
						)			
					);
					include("application/templates/body-settings.php"); 
				?>
			
				<?php
					$modal = array(
							"id" => "add-user",
							"title" => "Add User",
							"submit-label" => "Add",
							"action" => "#",
							"body" => <<<MODAL_CONTENTS
								<p>
									<label for="first_name" class="font-replace">First Name</label>					
									<input type="text" name="first_name" />
								</p>
								<p>
									<label for="last_name" class="font-replace">Last Name</label>					
									<input type="text" name="last_name" />
								</p>
								<p>
									<label for="username" class="font-replace">Username</label>					
									<input type="text" name="username" />
								</p>
								<p>
									<label for="email" class="font-replace">Email</label>					
									<input type="email" name="email" />
								</p>
								<p>
									<label for="is_admin" class="font-replace">Admin</label>					
									<input type="checkbox" name="is_admin" />
								</p>
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
