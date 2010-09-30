<?php

define("MAX_UPLOAD_SIZE", 1024000);

class YSSApplication
{
	private static $application;
	
	public static function sharedApplication()
	{
		return YSSApplication::$application;
	}
	
	public static function basePath()
	{
		static $path;
		
		if($path == null)
		{
			$path = realpath(__DIR__.'/../../');
		}
		
		return $path;
	}
	
	public static function remove_storage_for_domain($domain)
	{
		$storage_path = YSSUtils::storage_path_for_domain($domain);
		
		if(AWS_S3_ENABLED)
		{
			$s3           = YSSDatabase::connection(YSSDatabase::kS3);
			$s3->cleanBucket($storage_path);
			$s3->removeBucket($storage_path);
		}
		else
		{
			$location = YSSApplication::basePath().'/resources/attachments/'.$storage_path;

			if(is_dir($location))
			{
				$dir = dir($location);
				while(false !== ($entry = $dir->read()))
				{
					if($entry != '.' && $entry != '..')
					{
						if(is_file($location.'/'.$entry))
						{
							unlink($location.'/'.$entry);
						}
					}
				}

				$dir->close();

				rmdir($location);
			}
		}
	}
	
	public static function create_storage_for_domain($domain)
	{
		$storage_path = YSSUtils::storage_path_for_domain($domain);
		
		if(AWS_S3_ENABLED)
		{
			$s3           = YSSDatabase::connection(YSSDatabase::kS3);
			$s3->createBucket($storage_path);
		}
		else
		{
			$location = YSSApplication::basePath().'/resources/attachments/'.$storage_path;
			if(!is_dir($location))
			{
				mkdir($location, 0777, true);
			}
		}
	}
	
	public static function current_language()
	{
		return 'en-US';
	}
	
	public static function timestamp_now()
	{
		$date = new DateTime("now", new DateTimeZone("UTC"));
		return $date->format(DateTime::ISO8601);
	}
	
	public function startSession()
	{
		session_set_cookie_params(0, '/', '.'.YSSConfiguration::applicationDomain(), false);
		if (session_id() == "") session_start();
		
		
		$configuration = YSSConfiguration::standardConfiguration();
		if($configuration['currentUser'])
		{
			$session = YSSSession::sharedSession();
			$session->currentUser = $configuration['currentUser'];
		}
	}
	
	public function __construct()
	{
		new YSSConfiguration('config.ini');
		YSSApplication::$application = $this;
	}
	
	
}
?>