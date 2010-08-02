<?php
class YSSAttachment
{
	public $label;
	public $path;
	public $content_type;
	
	public static function attachmentWithLabelAndPath($label, $path)
	{
		return new YSSAttachment($label, $path);
	}
	
	public function __construct($label, $path, $content_type=null)
	{
		$this->label       = $label;
		$this->path        = $path;
		
		if($content_type == null)
		{
			$fileinfo             = finfo_open(FILEINFO_MIME_TYPE);
			$this->content_type   = finfo_file($fileinfo, $this->path);
			finfo_close($fileinfo);
		}
	}
	
	// the couchdb lib expects "name", but YSS uses "label", so we will just help it along
	// if an attachments needs to be added to couchdb via the lib.
	// __get and __isset overloads needed for this to work
	public function __get($key)
	{
		if($key == 'name')
			return $this->label;
	}
	
	public function __isset($key) 
	{
		if($key == 'name')
			return isset($this->label);
	}

}
?>