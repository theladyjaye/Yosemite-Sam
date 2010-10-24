<?php
class YSSMessageContactGeneral extends YSSMail
{
	protected $subject = 'General Message';
	protected $text    = '/application/mail/messages/contactGeneral.txt';
	protected $html    = '/application/mail/messages/contactGeneral.html';
	
	private $name;
	private $email;
	private $comments;
	
	public function __construct($name, $email, $comments)
	{
		$this->recipients = 'humans@peeqservice.com';
		$this->name       = $name;
		$this->email      = $email;
		$this->comments   = $comments;
	}
	
	protected function dictionary()
	{
		return array('name'=>$this->name, 'email' => $this->email, 'comments' => $this->comments);
	}
	
}
?>