<?php
require 'application/system/YSSEnvironment.php';
require 'application/system/YSSSecurity.php';
require 'application/data/YSSDomain.php';
require 'application/data/YSSCouchObject.php';
require 'application/data/YSSProject.php';
require 'application/data/YSSView.php';
require 'application/data/YSSState.php';
require 'application/data/YSSTask.php';


$session  = YSSSession::sharedSession();
$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $session->currentUser->domain);
$database->delete_database();

YSSDomain::create($session->currentUser->domain);

// Projects
$p1 = new YSSProject();
$p1->label = "Lucy";
$p1->description = "Project Lucy";
$p1->_id = "project/lucy";                                                                                                                                                                                                                                                 
                                                                                                                                                                                                                                                                           
$p2 = new YSSProject();                                                                                                                                                                                                                                                    
$p2->label = "Ollie";                                                                                                                                                                                                                                                       
$p2->description = "Project Ollie";
$p2->_id = "project/ollie";

$p3 = new YSSProject();                                                                                                                                                                                                                                                    
$p3->label = "YSS";                                                                                                                                                                                                                                                       
$p3->description = "Project YSS";
$p3->_id = "project/yss";

// Views
$v1 = new YSSView();
$v1->label       = "login";
$v1->description = "The login view";
$v1->_id         = "login";

$v2 = new YSSView();
$v2->label       = "logout";
$v2->description = "The logout view";
$v2->_id         = "logout";

$v3 = new YSSView();
$v3->label       = "Homepage";
$v3->description = "The homepage";
$v3->_id         = "homepage";

// States - we make new objects, because once one is saved, it will get an _id and a _rev and we don't want to reuse
$s1 = new YSSState();
$s1->label       = "default";
$s1->description = "default state";
$s1->_id         = "default";

$s2 = new YSSState();
$s2->label       = "default";
$s2->description = "default state";
$s2->_id         = "default";

$s3 = new YSSState();
$s3->label       = "default";
$s3->description = "default state";
$s3->_id         = "default";


// Tasks
$t1 = new YSSTask();
$t1->label = "Go for a walk";
$t1->description = "lorem ipsum dolor sit amet";
$t1->complete = false;

$t2 = new YSSTask();
$t2->label = "Go for a ride";
$t2->description = "lorem ipsum dolor sit amet";
$t2->complete = false;

$t3 = new YSSTask();
$t3->label = "Bark";
$t3->description = "lorem ipsum dolor sit amet";
$t3->complete = true;

$t4 = new YSSTask();
$t4->label = "Stare at birds";
$t4->description = "lorem ipsum dolor sit amet";
$t4->complete = false;


$p1->addView($v1);
$p1->addView($v2);
$p2->addView($v3);

$v1->addState($s1);
$v2->addState($s2);
$v3->addState($s3);

$s1->addAttachment(array("name"=>"view", "path" => YSSApplication::basePath().'/resources/img/fpo-comp.jpg'));
$s2->addAttachment(array("name"=>"view", "path" => YSSApplication::basePath().'/resources/img/fpo-comp.jpg'));
$s3->addAttachment(array("name"=>"view", "path" => YSSApplication::basePath().'/resources/img/fpo-comp.jpg'));

$s1->addTask($t1);
$s1->addTask($t2);
$s1->addTask($t3);
$s3->addTask($t4);

$p3->save();
?>