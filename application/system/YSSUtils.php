<?php
class YSSUtils
{
	public static function transform_to_id($value)
	{
		$result = strtolower($value);
		$result = strtr($result, array(" "=>"-", "_"=>"-"));
		return $result;
	}
}
?>