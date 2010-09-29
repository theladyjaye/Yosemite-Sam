<?php
class YSSMessagePasswordReset extends YSSMail
{
	protected $subject = 'YSS Your Password has been reset';
	protected $text    = '/application/mail/messages/accountPasswordReset.txt';
	protected $html    = '/application/mail/messages/accountPasswordReset.html';
	
	public $password;
	public $domain;
	
	public function __construct($recipients)
	{
		$this->recipients = $recipients;
	}
	
	protected function dictionary()
	{
		return array('password' => $this->password, 'domain' => $this->domain);
	}
	
}
?>