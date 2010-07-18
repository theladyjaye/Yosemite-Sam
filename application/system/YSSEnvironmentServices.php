<?php
require "YSSApplication.php";
require "YSSConfiguration.php";
require "YSSDatabase.php";
require "YSSSession.php";
require "YSSUtils.php";

require YSSApplication::basePath().'/application/data/YSSCurrentUser.php';
require YSSApplication::basePath().'/application/data/YSSCouchObject.php';
require YSSApplication::basePath().'/application/data/YSSUserLevel.php';

date_default_timezone_set('America/Los_Angeles');

new YSSApplication();
YSSApplication::startSession();

header('Content-Type: application/json');

?>