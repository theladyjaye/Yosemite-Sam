<?php
abstract class YSSAnnotation extends YSSCouchObject
{
	public $label;
	public $description;
	public $context; // context = flash | html | silverlight | javascript | etc
	public $x;
	public $y;
	public $width;
	public $height;
	
	public $type = "annotation";
}
?>