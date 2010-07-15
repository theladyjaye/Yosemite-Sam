<?php
class YSSUtils
{
	public static function transform_to_id($value)
	{
		$result = strtolower($value);
		$result = strtr($result, array(" "=>"-"));
		return $result;
	}
}
?>