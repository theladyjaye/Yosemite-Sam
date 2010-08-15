<?php
define("AWS_S3_ENABLED", false);

require "YSSApplication.php";
require "YSSConfiguration.php";
require "YSSDatabase.php";
require "YSSPage.php";
require "YSSController.php";
require "YSSSession.php";
require "YSSUtils.php";

require YSSApplication::basePath().'/application/data/YSSCurrentUser.php';
require YSSApplication::basePath().'/application/data/YSSUserLevel.php';

require YSSApplication::basePath().'/application/templates/ui/YSSUI.php';

/*
	TODO Before production needs to set the Zend Framework path in the ini manually so we don't need this call:
*/

// will probably handle this in the php.ini (Zend Framework Path) on the server once it comes time.. 
// for now just dynamically setting it up for the Zend Framework.
// Using Zend Framework for Amazon S3
set_include_path(get_include_path() . PATH_SEPARATOR . YSSApplication::basePath().'/application/libs');

date_default_timezone_set('America/Los_Angeles');

new YSSApplication();
YSSApplication::startSession();
?>