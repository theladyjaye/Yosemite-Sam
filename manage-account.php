<?php require 'application/system/YSSEnvironment.php' ?>
<?php YSSPage::Controller('ManageAccountController.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>manage-account</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Adam Venturella">
	<link rel="stylesheet" href="/resources/css/forms.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<!-- Date: 2010-06-26 -->
</head>
<body>
	<ul>
		<li><a href="/dashboard">dashboard</a></li>
		<li><a href="/manage/users">manage users</a></li>
		<li><a href="/manage/account">manage account</a></li>
		<li><a href="/logout">logout</a></li>
	</ul>
	<?php $page->displayForm() ?>
</body>
</html>
