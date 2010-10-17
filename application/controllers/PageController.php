<?php
class PageController extends YSSController
{
	protected function initialize()
	{ 		
		if($this->is_unsupported_browser())
		{
			header('Location: /not-supported');
		}

		if($this->is_mobile())
		{
			header('Location: /not-supported?mobile=true');
		}
	}
	
	protected function is_unsupported_browser()
	{
		$msie = (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false));
		return $msie;
	}

	protected function is_mobile()
	{
		$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
		$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

		return $iphone || $ipad || $android || $palmpre || $ipod;
	}
	
}
?>