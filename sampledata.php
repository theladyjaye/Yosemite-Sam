<?php
require 'application/system/YSSEnvironment.php';
require 'application/data/YSSDomain.php';
require 'application/data/YSSCouchObject.php';
require 'application/data/YSSProject.php';
require 'application/data/YSSTask.php';

$session  = YSSSession::sharedSession();
$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
$database->delete_database();

YSSDomain::create($session->currentUser->domain);

$p1 = new YSSProject();
$p1->label = "Lucy";
$p1->description = "Project Lucy Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
$p1->_id = "project/lucy";                                                                                                                                                                                                                                                 
                                                                                                                                                                                                                                                                           
$p2 = new YSSProject();                                                                                                                                                                                                                                                    
$p2->label = "Ollie";                                                                                                                                                                                                                                                       
$p2->description = "Project Ollie Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
$p2->_id = "project/ollie";

$p3 = new YSSProject();                                                                                                                                                                                                                                                    
$p3->label = "YSS";                                                                                                                                                                                                                                                       
$p3->description = "Project YSS Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute ir";
$p3->_id = "project/yss";

$t1 = new YSSTask();
$t1->label = "Go for a walk";
$t1->description = "lorem ipsum dolor sit amet";
$t1->project = $p1->_id;
$t1->complete = false;

$t2 = new YSSTask();
$t2->label = "Go for a ride";
$t2->description = "lorem ipsum dolor sit amet";
$t2->project = $p1->_id;
$t2->complete = false;

$t3 = new YSSTask();
$t3->label = "Bark";
$t3->description = "lorem ipsum dolor sit amet";
$t3->project = $p1->_id;
$t3->complete = true;

$t4 = new YSSTask();
$t4->label = "Stare at birds";
$t4->description = "lorem ipsum dolor sit amet";
$t4->project = $p2->_id;
$t4->complete = false;

$p1->save();
$t1->save();
$t2->save();
$t3->save();

$p2->save();
$t4->save();

$p3->save();
?>