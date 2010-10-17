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
}
?>