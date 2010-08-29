<?php
require '../application/system/YSSEnvironment.php';
require '../application/system/YSSSecurity.php';
require '../application/data/YSSDomain.php';
require '../application/data/YSSCouchObject.php';
require '../application/data/YSSProject.php';
require '../application/data/YSSView.php';
require '../application/data/YSSState.php';
require '../application/data/YSSAnnotation.php';
require '../application/data/YSSTask.php';
require '../application/data/YSSNote.php';
require '../application/data/YSSAttachment.php';

if(AWS_S3_ENABLED) require 'Zend/Service/Amazon/S3.php';

$session  = YSSSession::sharedSession();
YSSDomain::delete($session->currentUser->domain);
YSSDomain::create($session->currentUser->domain);

// Projects
$p1 = new YSSProject();
$p1->label = "Lucy The Dog";
$p1->description = "Project Lucy";
$p1->_id = "project/lucy-the-dog";                                                                                                                                                                                                                                                 
                                                                                                                                                                                                                                                                           
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

// States - we make new objects, because once one is saved, it will get an _id and a _rev and we don't want to reuse those dudes
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

$s4 = new YSSState();
$s4->label       = "recover password";
$s4->description = "recover password view augmentation";
$s4->_id         = "recover-password";


// Tasks
$t1 = new YSSTask();
$t1->label = "Implement API endpoints";
$t1->description = "lorem ipsum dolor sit amet";
$t1->context = "server";
$t1->status = YSSTask::kStatusIncomplete;
$t1->x = 300;
$t1->y = 434;

$t2 = new YSSTask();
$t2->label = "Handle filesystem IO for attachments";
$t2->description = "lorem ipsum dolor sit amet";
$t2->context = "server";
$t2->status = YSSTask::kStatusIncomplete;

$t3 = new YSSTask();
$t3->label = "Bark!";
$t3->description = "lorem ipsum dolor sit amet";
$t3->context = "css";
$t3->status = YSSTask::kStatusComplete;

$t4 = new YSSTask();
$t4->label = "Image Gallery";
$t4->description = "lorem ipsum dolor sit amet";
$t4->context = "html";
$t4->status = YSSTask::kStatusComplete;

$t5 = new YSSTask();
$t5->label = "Notification headers";
$t5->description = "Lorem ipsum dolor sit amet.";
$t5->context = "html";
$t5->status = YSSTask::kStatusComplete;

$t6 = new YSSTask();
$t6->label = "Javascript scroll action";
$t6->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$t6->context = "javascript";
$t6->status = YSSTask::kStatusIncomplete;

$t7 = new YSSTask();
$t7->label = "Dynamic Filtering";
$t7->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$t7->context = "javascript";
$t7->status = YSSTask::kStatusComplete;

$t8 = new YSSTask();
$t8->label = "Attempt to get anyone to use it";
$t8->description = "Lorem ipsum dolor sit amet.";
$t8->context = "silverlight";
$t8->status = YSSTask::kStatusIncomplete;

$t9 = new YSSTask();
$t9->label = "Sidebar Promos";
$t9->description = "Lorem ipsum dolor sit amet.";
$t9->context = "html";
$t9->status = YSSTask::kStatusIncomplete;

$t10 = new YSSTask();
$t10->label = "Landing page markup";
$t10->description = "Lorem ipsum dolor sit amet.";
$t10->context = "html";
$t10->status = YSSTask::kStatusIncomplete;

$t11 = new YSSTask();
$t11->label = "Ajax calls to API";
$t11->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
$t11->context = "javascript";
$t11->status = YSSTask::kStatusIncomplete;

$t12 = new YSSTask();
$t12->label = "Make someone care about it";
$t12->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t12->context = "silverlight";
$t12->status = YSSTask::kStatusIncomplete;

$t13 = new YSSTask();
$t13->label = "Lightbox modals";
$t13->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t13->context = "javascript";
$t13->status = YSSTask::kStatusComplete;

$t14 = new YSSTask();
$t14->label = "News markup";
$t14->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t14->context = "html";
$t14->status = YSSTask::kStatusIncomplete;

$t15 = new YSSTask();
$t15->label = "Features markup";
$t15->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t15->context = "html";
$t15->status = YSSTask::kStatusIncomplete;

$t16 = new YSSTask();
$t16->label = "Audio and Video players";
$t16->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t16->context = "html";
$t16->status = YSSTask::kStatusIncomplete;

$t17 = new YSSTask();
$t17->label = "Implement hero carousel";
$t17->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t17->context = "flash";
$t17->status = YSSTask::kStatusComplete;

$t18 = new YSSTask();
$t18->label = "Implement deeplinking";
$t18->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t18->context = "flash";
$t18->status = YSSTask::kStatusIncomplete;

$t19 = new YSSTask();
$t19->label = "Implement tracking";
$t19->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t19->context = "flash";
$t19->status = YSSTask::kStatusComplete;

$t20 = new YSSTask();
$t20->label = "Complete carousel";
$t20->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t20->context = "flash";
$t20->status = YSSTask::kStatusIncomplete;


// Notes
$n1 = new YSSNote();
$n1->label = "Needs UX Review";
$n1->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n1->context = "general";

$n2 = new YSSNote();
$n2->label = "Requires Technology Approval";
$n2->description = "lorem ipsum dolor sit amet";
$n3->context = "html";

$n3 = new YSSNote();
$n3->label = "Bark!";
$n3->description = "lorem ipsum dolor sit amet";
$n3->context = "general";

$n4 = new YSSNote();
$n4->label = "Needs UX Review";
$n4->description = "lorem ipsum dolor sit amet";
$n4->context = "general";

$n5 = new YSSNote();
$n5->label = "Impractical implementation";
$n5->description = "lorem ipsum dolor sit amet";
$n5->context = "general";

$n6 = new YSSNote();
$n6->label = "Requires client review";
$n6->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n6->context = "html";

$n7 = new YSSNote();
$n7->label = "Meow!";
$n7->description = "lorem ipsum dolor sit amet";
$n7->context = "html";

$n8 = new YSSNote();
$n8->label = "This has been approved";
$n8->description = "lorem ipsum dolor sit amet";
$n8->context = "server";

$n9 = new YSSNote();
$n9->label = "This has been approved";
$n9->description = "lorem ipsum dolor sit amet";
$n9->context = "server";

$n10 = new YSSNote();
$n10->label = "Technology has questions";
$n10->description = "lorem ipsum dolor sit amet";
$n10->context = "server";

$n11 = new YSSNote();
$n11->label = "Technology has questions";
$n11->description = "lorem ipsum dolor sit amet";
$n11->context = "general";

$n12 = new YSSNote();
$n12->label = "This has been approved";
$n12->description = "lorem ipsum dolor sit amet";
$n12->context = "general";

$n13 = new YSSNote();
$n13->label = "This has been approved";
$n13->description = "lorem ipsum dolor sit amet";
$n13->context = "general";

$n14 = new YSSNote();
$n14->label = "Requires client approval";
$n14->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n14->context = "general";

$n15 = new YSSNote();
$n15->label = "Requires client approval";
$n15->description = "lorem ipsum dolor sit amet";
$n15->context = "general";

$n16 = new YSSNote();
$n16->label = "Zoo-We-Mama!";
$n16->description = "lorem ipsum dolor sit amet";
$n16->context = "general";

$n17 = new YSSNote();
$n17->label = "Technology has questions";
$n17->description = "lorem ipsum dolor sit amet";
$n17->context = "javascript";

$n18 = new YSSNote();
$n18->label = "Technology has questions";
$n18->description = "lorem ipsum dolor sit amet";
$n18->context = "general";

$n19 = new YSSNote();
$n19->label = "Technology has questions";
$n19->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n19->context = "general";

$n20 = new YSSNote();
$n20->label = "Technology has questions";
$n20->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n20->context = "general";


$p1->addView($v1);
$p1->addView($v2);
$p2->addView($v3);

$v1->addState($s1);
$v1->addState($s4);
$v2->addState($s2);
$v3->addState($s3);

// Attachments

$a1          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x1200_brown.png', $session->currentUser->domain);
$a1->label   = "Lorem ipsum dolor sit amet";
$a1->_id     = $s1->_id.'/attachment/representation';

$a2          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x600_blue.png', $session->currentUser->domain);
$a2->label   = "Lorem ipsum dolor sit amet";
$a2->_id     = $s2->_id.'/attachment/representation';

$a3          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x1200_brown.png', $session->currentUser->domain);
$a3->label   = "Lorem ipsum dolor sit amet";
$a3->_id     = $s3->_id.'/attachment/representation';

$a4          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/documents/technicalSpec.pdf', $session->currentUser->domain);
$a4->label   = "Lorem ipsum dolor sit amet";
$a4->_id     = $p1->_id.'/attachment/technical-spec';

$a5          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/documents/functional spec.pdf', $session->currentUser->domain);
$a5->label   = "Lorem ipsum dolor sit amet";
$a5->_id     = $p1->_id.'/attachment/functional-spec';

$a6          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x600_blue.png', $session->currentUser->domain);
$a6->label   = "Lorem ipsum dolor sit amet";
$a6->_id     = $s4->_id.'/attachment/representation';


$s1->addAttachment($a1);
$s2->addAttachment($a2);
$s3->addAttachment($a3);
$s4->addAttachment($a6);

$p1->addAttachment($a4);
$p1->addAttachment($a5);


// Add Tasks
$s1->addAnnotation($t1);
$s1->addAnnotation($t2);
$s1->addAnnotation($t3);
$s1->addAnnotation($t4);
$s1->addAnnotation($t5);
$s1->addAnnotation($t6);
$s1->addAnnotation($t7);
$s1->addAnnotation($t8);
$s1->addAnnotation($t9);
$s1->addAnnotation($t10);
$s1->addAnnotation($t11);
$s1->addAnnotation($t12);

$s3->addAnnotation($t13);
$s3->addAnnotation($t14);
$s3->addAnnotation($t15);
$s3->addAnnotation($t16);
$s3->addAnnotation($t17);
$s3->addAnnotation($t18);
$s3->addAnnotation($t19);
$s3->addAnnotation($t20);

// Add Notes
$s1->addAnnotation($n1);
$s1->addAnnotation($n2);
$s1->addAnnotation($n3);
$s1->addAnnotation($n4);
$s1->addAnnotation($n5);
$s1->addAnnotation($n6);
$s1->addAnnotation($n7);
$s1->addAnnotation($n8);
$s1->addAnnotation($n9);
$s1->addAnnotation($n10);
$s1->addAnnotation($n11);
$s1->addAnnotation($n12);

$s3->addAnnotation($n13);
$s3->addAnnotation($n14);
$s3->addAnnotation($n15);
$s3->addAnnotation($n16);
$s3->addAnnotation($n17);
$s3->addAnnotation($n18);
$s3->addAnnotation($n19);
$s3->addAnnotation($n20);


$p3->save();
?>