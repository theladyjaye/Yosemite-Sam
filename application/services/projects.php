<?php
require '../system/YSSEnvironmentServices.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';
//require 'Bookmarks.php';

$session = YSSSession::sharedSession();
print_r($session->currentUser->domain);


//$manager  = new AMServiceManager();
//$manager->bindContract(new Bookmarks());
//$manager->start();
?>