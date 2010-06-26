<?php
class YSSMessageVerifyAccountComplete extends YSSMail
{
	protected $subject = 'YSS Account Verification Complete - Thanks!';
	protected $text    = '/application/mail/messages/accountVerified.txt';
	protected $html    = '/application/mail/messages/accountVerified.html';
	
	public $password;
	public $domain;
	
	public function __construct($recipients)
	{
		$this->recipients = $recipients;
	}
	
	protected function dictionary()
	{
		return array('password'=>$this->password, 'domain' => $this->domain);
	}
	
}
?>