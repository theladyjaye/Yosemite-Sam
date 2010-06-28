<?php
require "YSSApplication.php";
require "YSSConfiguration.php";
require "YSSDatabase.php";
require "YSSPage.php";
require "YSSController.php";
require "YSSSession.php";

require YSSApplication::basePath().'/application/data/YSSCurrentUser.php';
require YSSApplication::basePath().'/application/data/YSSUserLevel.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';

new YSSApplication();
//YSSDomain::create('yss_test');

date_default_timezone_set('America/Los_Angeles');
session_set_cookie_params(0, '/', '.'.YSSConfiguration::applicationDomain(), false);
if (session_id() == "") session_start();

?>