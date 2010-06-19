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
 *    @package display
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2010 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/
 
class AMMustache 
{
	protected    $source;
	protected    $uri;
	
	/*public static function search()
	{
		return array("\n", "\r", "\t");
	}
	
	public static function replace()
	{
		return array('\\n', '\\r', '\\t');
	}*/
	
	public static function find_replace()
	{
		return array("\n" => '\\n', 
		             "\r" => '\\r', 
		             "\t" => '\\t',
		             "\"" => '\"',
		             "'"  => "\'");
	}
	
	public static function template($uri)
	{
		echo AMMustache::initWithUri($uri);
	}
	
	public static function initWithUri($uri)
	{
		$instance = new AMMustache();
		$instance->setUri($uri);
		
		return $instance;
	}
	
	public static function initWithString($string)
	{
		$instance = new AMMustache();
		$instance->source = $string;
		return $instance;
	}
	
	public function setUri($uri)
	{
		if(strpos($uri, 'http://') === 0)
		{
			$this->source = $this->getRemoteFile($uri);
		}
		else
		{
			$this->uri = $uri;
		}
	}
	
	private function getRemoteFile($uri)
	{
		return file_get_contents($uri, FILE_TEXT);
	}
	
	public static function renderDisplayObjectWithUri($uri, &$dictionary=null)
	{
		if(!in_array("displayObjectRenderer_mustache", stream_get_filters()))
			stream_filter_register("displayObjectRenderer_mustache", "AMMustacheRenderer");
			
		if(file_exists($uri))
		{
			$pointer = fopen($uri, "r");
			stream_filter_append($pointer, "displayObjectRenderer_mustache", STREAM_FILTER_READ);
		
			return stream_get_contents($pointer);
		}
		else
		{
			trigger_error('AMMustache unable to open file '.$uri, E_USER_ERROR);
		}
	}
	
	public function render()
	{
		if($this->source)
		{
			$output = $this->source;
			
			$this->processSource($output);
			return $output;
		}
		else
		{
			return AMMustache::renderDisplayObjectWithUri($this->uri);
		}
	}
	
	protected function processSource(&$component)
	{
		//$search    = AMMustache::search();
		//$replace   = AMMustache::replace();
		//$component = str_replace($search, $replace, $component);
		
		$replacements = AMMustache::find_replace();
		$component = strtr($component, $replacements);
	}

	
	public function __toString()
	{
		return $this->render();
	}
}

class AMMustacheRenderer extends php_user_filter 
{
	public function filter($in, $out, &$consumed, $closing)
	{
		//$search  = AMMustache::search();
		//$replace = AMMustache::replace();
		
		$replacements = AMMustache::find_replace();
		while ($bucket = stream_bucket_make_writeable($in)) 
		{
			// for some reason I could not get this happening with str_replace
			// $bucket->data = str_replace($serach, $replace, $bucket->data);
			
			$bucket->data = strtr($bucket->data, $replacements);
			$consumed += $bucket->datalen;
			stream_bucket_append($out, $bucket);
		}
		return PSFS_PASS_ON;
	}
	
	public function onCreate()
	{
		return true;
	}
}

 
?>