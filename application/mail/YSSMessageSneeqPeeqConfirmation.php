<?php
// This message is sent when a domain is first created
class YSSMessageSneeqPeeqConfirmation extends YSSMail
{
	protected $subject = 'Thanks for your interest in Peeq';
	protected $text    = '/application/mail/messages/sneeqPeeqConfirmation.txt';
	protected $html    = '/application/mail/messages/sneeqPeeqConfirmation.html';
	
	private $key;
	private $domain;
	
	public function __construct($recipients)
	{
		$this->recipients = $recipients;
	}
	
	protected function dictionary()
	{
		return array();
	}
	
}
?>