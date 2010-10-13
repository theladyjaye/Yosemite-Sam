<?php
// Combines api calls for each page
//define("API_HOST", "yss.com");

class YSSUtils
{	
	public static function transform_to_id($value)
	{
		$result = strtolower($value);
		$result = strtr($result, array(" " => "-",
		                               "_" => "-",
		                               "/" => "-"));
		return $result;
	}
	
	public static function transform_to_attachment_id($value)
	{
		$result = strtolower($value);
		$result = strtr($result, array(" " => "-",
		                               "_" => "-",
		                               "/" => "."));
		return $result;
	}
	
	public static function storage_path_for_domain($domain)
	{
		$configuration = YSSConfiguration::standardConfiguration();
		$namespace     = $configuration['s3']['namespace'];
		return $namespace.'.'.$domain;
	}
	
	public static function calc_percentage($tasks_completed, $tasks_total)
	{
		return ($tasks_total > 0) ? round(($tasks_completed / $tasks_total) * 100) : 0;
	}
	
	/*
	public static function peeq_api_request($options)
	{
		// since we don't include any of the environment 
		// stuffs we need to manually start the session.
		// if you uncomment the lines up top, you will need 
		// to see if you need this anymore.
		// also it appears to be critical that you end the 
		// session prior to sending the PHP session over cURL
		// or over a socket.

		session_start();
		$session_cookie = session_name().'='.session_id();
		session_write_close();

		extract($options);

		$request = array("$method $path HTTP/1.0",
		                 "Host: ".API_HOST,
	                     "Cookie: $session_cookie",
		                 "Connection: Close");

		$errno    = null;
		$errstr   = null;
		$response = null;
		$timeout  = 30;

		$stream = stream_socket_client("tcp://".API_HOST.":80", $errno, $errstr, $timeout);

		if(!$stream)
		{
			throw new Exception('Unable to connect to host '.$socket.' : '.$errno.', '.$errstr);
			return;
		}
		else
		{
			fwrite($stream, implode("\r\n", $request)."\r\n\r\n");
			$response = stream_get_contents($stream);
			fclose($stream);

			list($headers, $body) = explode("\r\n\r\n", $response);
			return $body;
		}

		fclose($stream);
	}
	*/
}
?>