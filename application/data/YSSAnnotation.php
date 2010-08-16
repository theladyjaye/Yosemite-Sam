<?php
abstract class YSSAnnotation extends YSSCouchObject
{
	public $label;
	public $description;
	public $context;
	public $x;
	public $y;
	
	protected $type = "annotation";
}
?>