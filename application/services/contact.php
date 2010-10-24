<?php
require '../system/YSSEnvironmentServices.php';

require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMInputValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';


require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';
require YSSApplication::basePath().'/application/mail/YSSMessageContactGeneral.php';



require YSSApplication::basePath().'/application/system/YSSService.php';

class YSSServiceContact extends YSSService
{
	protected $requiresAuthorization = false;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "POST":
				$this->addEndpoint("POST",    "/api/contact/general",  "general");
		}
	}
	
	public function general()
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
		
	
		$input->addValidator(new AMInputValidator('name', AMValidator::kRequired, 2, null, "Invalid name.  Expecting minimum 2 characters."));
		$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, "Invalid email address."));
		$input->addValidator(new AMInputValidator('comments', AMValidator::kRequired, 5, null, "Invalid comment.  Expecting minimum 5 characters."));
		
		if($input->isValid)
		{
			$response->ok = true;
			
			// include timestamp? YSSApplication::timestamp_now();
			$mail = new YSSMessageContactGeneral($input->name, $input->email, $input->comments);
			$mail->send();
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceContact());
$manager->start();
?>