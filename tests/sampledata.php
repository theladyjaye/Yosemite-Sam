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

$v4 = new YSSView();
$v4->label       = "No Attachment Page";
$v4->description = "No attachment";
$v4->_id         = "no-attachment-page";


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

$s5 = new YSSState();
$s5->label       = "no attachment";
$s5->description = "this state has no attachment";
$s5->_id         = "no-attachment";

// Tasks
$t1 = new YSSTask();
$t1->label = "Implement API endpoints";
$t1->description = "lorem ipsum dolor sit amet";
$t1->context = "server";
$t1->status = YSSTask::kStatusIncomplete;
$t1->x = 0;
$t1->y = 0;

$t2 = new YSSTask();
$t2->label = "Handle filesystem IO for attachments";
$t2->description = "lorem ipsum dolor sit amet";
$t2->context = "server";
$t2->status = YSSTask::kStatusIncomplete;
$t2->x = 20;
$t2->y = 0;

$t3 = new YSSTask();
$t3->label = "Bark!";
$t3->description = "lorem ipsum dolor sit amet";
$t3->context = "css";
$t3->status = YSSTask::kStatusComplete;
$t3->x = 40;
$t3->y = 0;

$t4 = new YSSTask();
$t4->label = "Image Gallery";
$t4->description = "lorem ipsum dolor sit amet";
$t4->context = "html";
$t4->status = YSSTask::kStatusComplete;
$t4->x = 60;
$t4->y = 0;

$t5 = new YSSTask();
$t5->label = "Notification headers";
$t5->description = "Lorem ipsum dolor sit amet.";
$t5->context = "html";
$t5->status = YSSTask::kStatusComplete;
$t5->x = 80;
$t5->y = 0;

$t6 = new YSSTask();
$t6->label = "Javascript scroll action";
$t6->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$t6->context = "javascript";
$t6->status = YSSTask::kStatusIncomplete;
$t6->x = 100;
$t6->y = 0;

$t7 = new YSSTask();
$t7->label = "Dynamic Filtering";
$t7->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$t7->context = "javascript";
$t7->status = YSSTask::kStatusComplete;
$t7->x = 120;
$t7->y = 0;

$t8 = new YSSTask();
$t8->label = "Attempt to get anyone to use it";
$t8->description = "Lorem ipsum dolor sit amet.";
$t8->context = "silverlight";
$t8->status = YSSTask::kStatusIncomplete;
$t8->x = 140;
$t8->y = 0;

$t9 = new YSSTask();
$t9->label = "Sidebar Promos";
$t9->description = "Lorem ipsum dolor sit amet.";
$t9->context = "html";
$t9->status = YSSTask::kStatusIncomplete;
$t9->x = 160;
$t9->y = 0;

$t10 = new YSSTask();
$t10->label = "Landing page markup";
$t10->description = "Lorem ipsum dolor sit amet.";
$t10->context = "html";
$t10->status = YSSTask::kStatusIncomplete;
$t10->x = 180;
$t10->y = 0;

$t11 = new YSSTask();
$t11->label = "Ajax calls to API";
$t11->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
$t11->context = "javascript";
$t11->status = YSSTask::kStatusIncomplete;
$t11->x = 200;
$t11->y = 0;

$t12 = new YSSTask();
$t12->label = "Make someone care about it";
$t12->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t12->context = "silverlight";
$t12->status = YSSTask::kStatusIncomplete;
$t12->x = 0;
$t12->y = 20;

$t13 = new YSSTask();
$t13->label = "Lightbox modals";
$t13->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t13->context = "javascript";
$t13->status = YSSTask::kStatusComplete;
$t13->x = 20;
$t13->y = 20;

$t14 = new YSSTask();
$t14->label = "News markup";
$t14->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t14->context = "html";
$t14->status = YSSTask::kStatusIncomplete;
$t14->x = 40;
$t14->y = 20;

$t15 = new YSSTask();
$t15->label = "Features markup";
$t15->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t15->context = "html";
$t15->status = YSSTask::kStatusIncomplete;
$t15->x = 60;
$t15->y = 20;

$t16 = new YSSTask();
$t16->label = "Audio and Video players";
$t16->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t16->context = "html";
$t16->status = YSSTask::kStatusIncomplete;
$t16->x = 80;
$t16->y = 20;

$t17 = new YSSTask();
$t17->label = "Implement hero carousel";
$t17->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t17->context = "flash";
$t17->status = YSSTask::kStatusComplete;
$t17->x = 100;
$t17->y = 20;

$t18 = new YSSTask();
$t18->label = "Implement deeplinking";
$t18->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t18->context = "flash";
$t18->status = YSSTask::kStatusIncomplete;
$t18->x = 120;
$t18->y = 20;

$t19 = new YSSTask();
$t19->label = "Implement tracking";
$t19->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t19->context = "flash";
$t19->status = YSSTask::kStatusComplete;
$t19->x = 140;
$t19->y = 20;

$t20 = new YSSTask();
$t20->label = "Complete carousel";
$t20->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.";
$t20->context = "flash";
$t20->status = YSSTask::kStatusIncomplete;
$t20->x = 160;
$t20->y = 20;

// Notes
$n1 = new YSSNote();
$n1->label = "Needs UX Review";
$n1->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n1->context = "general";
$n1->x = 0;
$n1->y = 40;

$n2 = new YSSNote();
$n2->label = "Requires Technology Approval";
$n2->description = "lorem ipsum dolor sit amet";
$n2->context = "html";
$n2->x = 20;
$n2->y = 40;

$n3 = new YSSNote();
$n3->label = "Bark!";
$n3->description = "lorem ipsum dolor sit amet";
$n3->context = "general";
$n3->x = 40;
$n3->y = 40;

$n4 = new YSSNote();
$n4->label = "Needs UX Review";
$n4->description = "lorem ipsum dolor sit amet";
$n4->context = "general";
$n4->x = 60;
$n4->y = 40;

$n5 = new YSSNote();
$n5->label = "Impractical implementation";
$n5->description = "lorem ipsum dolor sit amet";
$n5->context = "general";
$n5->x = 80;
$n5->y = 40;

$n6 = new YSSNote();
$n6->label = "Requires client review";
$n6->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n6->context = "html";
$n6->x = 100;
$n6->y = 40;

$n7 = new YSSNote();
$n7->label = "Meow!";
$n7->description = "lorem ipsum dolor sit amet";
$n7->context = "html";
$n7->x = 120;
$n7->y = 40;

$n8 = new YSSNote();
$n8->label = "This has been approved";
$n8->description = "lorem ipsum dolor sit amet";
$n8->context = "server";
$n8->x = 140;
$n8->y = 40;

$n9 = new YSSNote();
$n9->label = "This has been approved";
$n9->description = "lorem ipsum dolor sit amet";
$n9->context = "server";
$n9->x = 160;
$n9->y = 40;

$n10 = new YSSNote();
$n10->label = "Technology has questions";
$n10->description = "lorem ipsum dolor sit amet";
$n10->context = "server";
$n10->x = 180;
$n10->y = 40;

$n11 = new YSSNote();
$n11->label = "Technology has questions";
$n11->description = "lorem ipsum dolor sit amet";
$n11->context = "general";
$n11->x = 200;
$n11->y = 40;

$n12 = new YSSNote();
$n12->label = "This has been approved";
$n12->description = "lorem ipsum dolor sit amet";
$n12->context = "general";
$n12->x = 0;
$n12->y = 60;

$n13 = new YSSNote();
$n13->label = "This has been approved";
$n13->description = "lorem ipsum dolor sit amet";
$n13->context = "general";
$n13->x = 20;
$n13->y = 60;

$n14 = new YSSNote();
$n14->label = "Requires client approval";
$n14->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n14->context = "general";
$n14->x = 40;
$n14->y = 60;

$n15 = new YSSNote();
$n15->label = "Requires client approval";
$n15->description = "lorem ipsum dolor sit amet";
$n15->context = "general";
$n15->x = 60;
$n15->y = 60;

$n16 = new YSSNote();
$n16->label = "Zoo-We-Mama!";
$n16->description = "lorem ipsum dolor sit amet";
$n16->context = "general";
$n16->x = 80;
$n16->y = 60;

$n17 = new YSSNote();
$n17->label = "Technology has questions";
$n17->description = "lorem ipsum dolor sit amet";
$n17->context = "javascript";
$n17->x = 100;
$n17->y = 60;

$n18 = new YSSNote();
$n18->label = "Technology has questions";
$n18->description = "lorem ipsum dolor sit amet";
$n18->context = "general";
$n18->x = 120;
$n18->y = 60;

$n19 = new YSSNote();
$n19->label = "Technology has questions";
$n19->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n19->context = "general";
$n19->x = 140;
$n19->y = 60;

$n20 = new YSSNote();
$n20->label = "Technology has questions";
$n20->description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
$n20->context = "general";
$n20->x = 160;
$n20->y = 60;



$p1->addView($v1);
$p1->addView($v2);
$p1->addView($v4);
$p2->addView($v3);

$v1->addState($s1);
$v1->addState($s4);
$v4->addState($s5);
$v2->addState($s2);
$v3->addState($s3);

// Attachments

$a1          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x1200_brown.png', $session->currentUser->domain);
$a1->label   = "Brown Represenation a1";
$a1->_id     = $s1->_id.'/attachment/representation';

$a2          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x600_blue.png', $session->currentUser->domain);
$a2->label   = "Blue Representation a2";
$a2->_id     = $s2->_id.'/attachment/representation';

$a3          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x1200_brown.png', $session->currentUser->domain);
$a3->label   = "Brown Representation a3";
$a3->_id     = $s3->_id.'/attachment/representation';

$a4          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/documents/technicalSpec.pdf', $session->currentUser->domain);
$a4->label   = "Technical Spec";
$a4->_id     = $p1->_id.'/attachment/technical-spec';

$a5          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/documents/functional spec.pdf', $session->currentUser->domain);
$a5->label   = "Functional Spec";
$a5->_id     = $p1->_id.'/attachment/functional-spec';

$a6          = YSSAttachment::attachmentWithLocalFileInDomain(YSSApplication::basePath().'/tests/resources/img/980x600_blue.png', $session->currentUser->domain);
$a6->label   = "Blue Representation a6";
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