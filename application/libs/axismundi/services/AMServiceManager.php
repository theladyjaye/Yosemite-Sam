<?php
/**
 *    AxisMundi
 * 
 *    Copyright (C) 2010 Adam Venturella
 *
 *    LICENSE:
 *
 *    Licensed under the Apache License, Version 2.0 (the "License"); you may not
 *    use this file except in compliance with the License.  You may obtain a copy
 *    of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 *    This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 *    without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR 
 *    PURPOSE. See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 *    Author: Adam Venturella - aventurella@gmail.com
 *
 *    @package services
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2010 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/
require 'AMServiceContract.php';
require 'AMServiceEndpoint.php';
class AMServiceManager
{
	private $contract;
	
	public static function not_found()
	{
		header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
		exit;
	}
	
	public static function forbidden()
	{
		header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
		exit;
	}
	
	public static function unauthorized()
	{
		header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
		exit;
	}
	
	public static function generateKey($string, $arg_count)
	{
		return hash_hmac('md5', $string, $arg_count);
	}
	
	public function bindContract(AMServiceContract $contract)
	{
		$this->contract = $contract;
		$this->contract->registerServiceEndpoints();
	}
	
	public function start()
	{
		$endpoints   = null;
		$method      = strtoupper($_SERVER['REQUEST_METHOD']);
		$termination = strripos($_SERVER['REQUEST_URI'], '?');
		$path        = substr($_SERVER['REQUEST_URI'], 0, ($termination === false ? strlen($_SERVER['REQUEST_URI']) : $termination));

		if($path[0] == '/'){
			$path = substr($path, 1);
		}
		
		if($path[strlen($path)-1] == '/'){
			$path = substr($path, 0, strlen($path)-1);
		}
		
		$segments       = explode("/", $path);
		
		if($_SERVER['QUERY_STRING'])
		{
			$queryString =  explode('&', $_SERVER['QUERY_STRING']);
			foreach($queryString as $part)
			{
				list($argName, $argValue) = explode('=', $part);
				$segments[] = $argName;
				$segments[] = $argValue;
			}
		}
		
		$segmentsLength = count($segments);
		
		$branch = $this->contract->endpoints[$method];
		
		$endpoints = array();
		foreach($branch as $service)
		{
			$match  = array_intersect($segments, $service->parts);
			$params = $segmentsLength - count($match);
			
			
			if($params == $service->parameter_count &&
				count(array_diff($service->parts, $match)) == 0)
			{
				$input = strlen($_SERVER['QUERY_STRING']) ? '?'.implode("", $match) : implode("", $match);
				$key = AMServiceManager::generateKey($input, $params);
				
				if(array_key_exists($key, $branch))
				{
					$args = array_diff($segments, $service->parts);
					
					// we apply some weighting.  matching path arguments hold more importance that parameter arguments
					// so if we have an endnpoint defined as /{arg} vs /users
					// /users will be given a higher priority than /{arg}
					$score       = $params+(count($match)*2);
					$endpoints[] = array('score'=> $score, 'arguments'=>$args, 'service'=> $service);
				}
			}
		}
		
		$choices = count($endpoints);
		
		if($choices)
		{
			if($choices > 1)
			{
				$this->execute($endpoints[0]['service'], $endpoints[0]['arguments']);
			}
			else
			{
				usort($endpoints, array($this, "sort_endpoints_score"));
				$selection = array_pop($endpoints);
				$this->execute($selection['service'], $selection['arguments']);
			}
		}
		else
		{
			AMServiceManager::not_found();
		}
		
	}
	
	private function sort_endpoints_score($a, $b)
	{
		if ($a['score'] == $b['score']) 
		{
			return 0;
		}
		
		return ($a['score'] < $b['score']) ? -1 : 1;
	}
	
	private function execute($service, $arguments=null)
	{
		
		if(count($arguments))
		{
			$arguments = array_map("rawurldecode", $arguments);
			call_user_func_array(array($this->contract, $service->action), $arguments);
			
		}
		else
		{
			call_user_func(array($this->contract, $service->action));
		}
	}
}
?>