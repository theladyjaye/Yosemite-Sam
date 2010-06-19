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
class AMServiceEndpoint
{
	public $method;
	public $action;
	public $parameter_count;
	public $parts;
	public $hash;
	
	private $key;
	
	public function __construct($method, $uri, $action)
	{
		$this->method          = strtoupper($method);
		$this->action          = $action;
		$this->parts           = array();
		$this->key             = "";
		$this->parameter_count = 0;
		
		$this->processUri($uri);
	}
	
	private function processUri($uri)
	{
		$hasBodyParams        = false;
		$queryString          = null;
		$queryStringHasParams = false;
		
		// get rid of any leading /
		if($uri[0] == '/') $uri = substr($uri, 1);
		
		// do we have any query string params?
		$hasQueryString = strripos($uri, '?');
		
		if($hasQueryString !== false)
		{
			$queryString          = substr($uri, $hasQueryString+1);
			$queryStringHasParams = (strpos($queryString, '{') === false) ? false : true;
			$queryString          = explode('&', $queryString);
			$uri                  = substr($uri, 0, $hasQueryString);
			
			$this->key = '?';
		}
		
		// cleanup and prepare:
		// do we end with a / 
		$uri           = (strripos($uri, '/') == (strlen($uri) -1)) ? substr($uri, 0, -1) : $uri;
		$hasBodyParams = (strpos($uri, '{') === false) ? false : true;
		
		
		$segments  = explode('/', $uri);
		
		// parse the main URI body
		if($hasBodyParams)
		{
			$this->processArray($segments);
		}
		else
		{
			$this->parts  = $segments;
			$this->key   .= implode("", $segments);
		}
		
		
		if(is_array($queryString))
		{
			$this->processArray($queryString, true);
		}
		
		$this->hash = AMServiceManager::generateKey($this->key, $this->parameter_count);
	}
	
	private function processArray($array, $isQueryString=false)
	{
		foreach($array as $segment)
		{
			if(strpos($segment, '{') === false)
			{
				if($isQueryString)
				{
					list($key, $value) = explode('=', $segment);
					
					$this->key    .= $key.$value;
					$this->parts[] = $key;
					$this->parts[] = $value;
				}
				else
				{
					$this->key .= $segment;
					$this->parts[] = $segment;
				}
			}
			else
			{
				if($isQueryString)
				{
					$count = 2; // key+value
					list($key, $value) = explode('=', $segment);
					
					if(strpos($key, '{') === false)
					{
						$this->key .= $key;
						$this->parts[] = $key;
						$count = $count - 1;
					}
					
					if(strpos($value, '{') === false)
					{
						$this->key .= $value;
						$this->parts[] = $value;
						$count = $count - 1;
					}
					
					$this->parameter_count = $this->parameter_count + $count;
				}
				else
				{
					$this->parameter_count = $this->parameter_count + 1;
				}
			}
		}
	}
}
?>