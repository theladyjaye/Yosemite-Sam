<?php
class YSSUtils
{
	public static function transform_to_id($value)
	{
		$result = strtolower($value);
		$result = strtr($result, array(" "=>"-", "_"=>"-"));
		return $result;
	}
	
	public static function calc_percentage($tasks_completed, $tasks_total)
	{
		return ($tasks_total > 0) ? round(($tasks_completed / $tasks_total) * 100) : 0;
	}
}
?>