<?php
require YSSApplication::basePath().'/application/libs/phpmailer/class.phpmailer.php';
abstract class YSSMail
{
	protected $fromName    = 'YSS Agent';
	protected $fromAddress = 'agent@yss.com';
	protected $subject;
	protected $text;
	protected $html;
	protected $recipients;
	
	public function send()
	{
		$message = $this->prepareMessage();
		
		$mail             = new PHPMailer();
		$mail->SetFrom($this->fromAddress, $this->fromName);
		$mail->Subject    = $this->subject;

		$mail->AltBody    = $message['text'];
		$mail->MsgHTML($message['html']);


		if(is_array($this->recipients))
		{
			foreach($this->recipients as $address)
			{
				$mail->AddAddress($address);
			}
		}
		else
		{
			$mail->AddAddress($this->recipients);
		}

		$mail->Send();
	}
	
	protected function prepareMessage()
	{
		
		$dictionary = $this->dictionary();
		
		$text = AMDisplayObject::initWithURLAndDictionary(YSSApplication::basePath().'/'.$this->text, $dictionary);
		$html = AMDisplayObject::initWithURLAndDictionary(YSSApplication::basePath().'/'.$this->html, $dictionary);
		
		return array('text' => $text->__toString(), 'html'=> $html->__toString());
	}
	
	protected abstract function dictionary();
	
	
	
}
?>