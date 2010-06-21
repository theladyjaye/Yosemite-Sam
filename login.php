<?php require 'application/system/YSSEnvironment.php' ?>
<?php YSSPage::Controller('LoginController.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>login</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Adam Venturella">
	<!-- Date: 2010-06-21 -->
</head>
<body>
<form action="./login.php" method="post" accept-charset="utf-8">
	
	<fieldset id="login" class="">
		<legend>Login</legend>
		<label for="domain">Domain</label><input type="text" name="domain" value="" id="domain">
		<br><label for="username">Username or Email</label><input type="text" name="username" value="" id="username">
		<br><label for="password">Password</label><input type="password" name="password" value="" id="password">
	</fieldset>
	<p><input type="submit" value="Login &rarr;"></p>
</form>
</body>
</html>
