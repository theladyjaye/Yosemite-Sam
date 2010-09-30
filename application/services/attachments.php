<?php
require '../system/YSSEnvironmentServices.php';

require YSSApplication::basePath().'/application/libs/axismundi/data/AMQuery.php';
require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMPatternValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMMatchValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';

require YSSApplication::basePath().'/application/data/YSSCompany.php';
require YSSApplication::basePath().'/application/data/YSSUser.php';
require YSSApplication::basePath().'/application/data/YSSDomain.php';
require YSSApplication::basePath().'/application/data/YSSProject.php';
require YSSApplication::basePath().'/application/data/YSSView.php';
require YSSApplication::basePath().'/application/data/YSSAnnotation.php';
require YSSApplication::basePath().'/application/data/YSSTask.php';
require YSSApplication::basePath().'/application/system/YSSService.php';
require YSSApplication::basePath().'/application/data/YSSAttachment.php';

require 'Zend/Service/Amazon/S3.php';


class YSSServiceAttachments extends YSSService
{
	protected $requiresAuthorization = true;
	
	public function registerServiceEndpoints($method)
	{
		/*
			TODO need to add a way to download attachments.  Content-Disposition etc...
		*/
		switch($method)
		{
			case "GET":
				$this->addEndpoint("GET",    "/api/attachments/{id}",   "getAttachment");
				break;
		}
	}
	
	public function getAttachment($id)
	{
		// may want to do some caching here so we are not hitting S3 ALL the time, if enabled.
		
		$session    = YSSSession::sharedSession();
		$attachment = YSSAttachment::attachmentWithRemoteFileInDomain($id, $session->currentUser->domain);
		header('Content-Type:'.$attachment->content_type);
		header('Content-Length:'.$attachment->content_length);
		$attachment->contents();
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceAttachments());
$manager->start();
?>