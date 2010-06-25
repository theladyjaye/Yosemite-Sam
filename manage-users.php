<?php require 'application/system/YSSEnvironment.php' ?>
<?php YSSPage::Controller('ManageUsersController.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>manage-users</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Adam Venturella">
	<!-- Date: 2010-06-23 -->
</head>
<body>
	<div>
		<fieldset>
			<legend>Create New User</legend>
			<form action="/manage/users" method="post" accept-charset="utf-8">
				<label for="firstname">First Name:</label><input type="text" name="firstname" value="" id="firstname">
				<br><label for="lastname">Last Name:</label><input type="text" name="lastname" value="" id="lastname">
				<br><label for="username">Username:</label><input type="text" name="username" value="" id="username">
				<br><label for="email">Email:</label><input type="text" name="email" value="" id="email">
				<br><label for="administrator">Administrator?</label><input type="checkbox" name="administrator" value="1" id="administrator">
				<p><input type="submit" value="Create &rarr;"></p>
			</form>
		</fieldset>
	</div>
	<?php $page->showUsers() ?>
</body>
</html>
