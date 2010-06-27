<?php
class FormManageAccount
{
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function __toString()
	{
		$source  = YSSApplication::basePath().'/application/templates/FormManageAccount.html';
		return AMDisplayObject::renderDisplayObjectWithURLAndDictionary($source, $this->data);
	}
}
?>