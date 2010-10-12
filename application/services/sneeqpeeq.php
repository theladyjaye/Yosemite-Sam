<?php
require '../system/YSSEnvironmentServices.php';

require YSSApplication::basePath().'/application/libs/axismundi/display/AMDisplayObject.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/AMForm.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMEmailValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/forms/validators/AMErrorValidator.php';
require YSSApplication::basePath().'/application/libs/axismundi/services/AMServiceManager.php';


require YSSApplication::basePath().'/application/system/YSSSecurity.php';
require YSSApplication::basePath().'/application/mail/YSSMail.php';
require YSSApplication::basePath().'/application/mail/YSSMessageSneeqPeeqConfirmation.php';



require YSSApplication::basePath().'/application/system/YSSService.php';

class YSSServiceSneeqPeeq extends YSSService
{
	protected $requiresAuthorization = false;
	
	public function registerServiceEndpoints($method)
	{
		switch($method)
		{
			case "POST":
				$this->addEndpoint("POST",    "/api/sneeqpeeq/register",                   "register");
		}
	}
	
	public function register()
	{
		$response     = new stdClass();
		$response->ok = false;
		
		$context = array(AMForm::kDataKey=>$_POST);
		$input   = AMForm::formWithContext($context);
	
		$input->addValidator(new AMEmailValidator('email', AMValidator::kRequired, "Invalid email address."));
		if($input->isValid)
		{
			$database = YSSDatabase::connection(YSSDatabase::kCouchDB, 'sneeqpeeq');

			$registration             = new stdClass();
			$registration->type       = "registration";
			$registration->created_on = YSSApplication::timestamp_now();
			
			$status = $database->put($registration, $input->email);

			if(isset($status['ok']))
			{
				$response->ok = true;
				
				$mail = new YSSMessageSneeqPeeqConfirmation($input->email);
				$mail->send();
			}
			else
			{
				$input->addValidator(new AMErrorValidator('error', "unknown_error"));
				$this->hydrateErrors($input, $response);
			}
		}
		else
		{
			$this->hydrateErrors($input, $response);
		}
		
		echo json_encode($response);
	}
}

$manager  = new AMServiceManager();
$manager->bindContract(new YSSServiceSneeqPeeq());
$manager->start();
?>