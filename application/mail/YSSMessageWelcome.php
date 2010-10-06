<?php
// This message is sent when a domain is first created
class YSSMessageWelcome extends YSSMail
{
	protected $subject = 'Welcome To YSS!';
	protected $text    = '/application/mail/messages/accountWelcome.txt';
	protected $html    = '/application/mail/messages/accountWelcome.html';
	
	private $key;
	private $domain;
	
	public function __construct($recipients, $domain, $key)
	{
		$this->recipients = $recipients;
		$this->key        = $key;
		$this->domain     = $domain;
	}
	
	protected function dictionary()
	{
		return array('key'=>$this->key, 'domain' => $this->domain);
	}
	
}
?>