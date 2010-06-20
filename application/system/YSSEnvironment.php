<?php
require "YSSApplication.php";
require "YSSConfiguration.php";
require "YSSDatabase.php";
require "YSSPage.php";
require "YSSController.php";

date_default_timezone_set('America/Los_Angeles');
if (session_id() == "") session_start();

new YSSApplication();
?>