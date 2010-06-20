<?php
class FormSignup
{
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function __toString()
	{
		$source  = YSSApplication::basePath().'/application/templates/FormSignup.html';
		return AMDisplayObject::renderDisplayObjectWithURLAndDictionary($source, $this->data);
	}
}
?>