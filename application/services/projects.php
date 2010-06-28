<?php
require '../system/YSSEnvironmentServices.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';
require 'YSSServiceProjects.php';



$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceProjects());
$manager->start();
?>