<?php
class YSSSession
{
	const kSessionKey = 'YSS';
	
	private static $session = null;
	
	public static function sharedSession()
	{
		if(!self::$session)
		{
			self::$session = new YSSSession();
		}
		
		return self::$session;
	}
	
	public function destroy()
	{
		$_SESSION[YSSSession::kSessionKey] = array();
		
		if (isset($_COOKIE[session_name()])) 
		    setcookie(session_name(), '', time()-42000, '/');
		
		session_destroy();
	}
	
	public function __get($key)
	{
		if(isset($_SESSION[YSSSession::kSessionKey][$key]))
			return $_SESSION[YSSSession::kSessionKey][$key];
	}

	public function __set($key, $value)
	{
		if(isset($_SESSION[YSSSession::kSessionKey][$key]) && $_SESSION[YSSSession::kSessionKey][$key] != $value)
		{
			$_SESSION[YSSSession::kSessionKey][$key] = $value;
		}
		else
		{
			$_SESSION[YSSSession::kSessionKey][$key] = $value;
		}
	}
}
?>
