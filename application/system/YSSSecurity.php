<?php
class YSSSecurity
{
	public static function generate_token($salt=null)
	{

		$key    = null;
		$data   = null; 
		
		if(function_exists('openssl_random_pseudo_bytes'))
		{
			$strong = false;

			while(!$strong)
			{
				$data = openssl_random_pseudo_bytes(512, $strong);
			}
		}
		else
		{
			$stream = fopen('/dev/urandom', 'rb');
			//$stream = fopen('/dev/random', 'rb');

			if($stream)
			{
				$data   = fread($stream, 64);
				fclose($stream);
			}
		}

		$key =  hash('md5', base64_encode($data).$salt.uniqid(mt_rand(), true));
		return $key;
	}
}
?>