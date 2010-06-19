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
 
class AMDisplayObject
{
	public       $dictionary;
	protected    $source;
	protected    $url;
	protected    $dynamicProperties;
	
	/* Convenience Methods */
	public static function initWithURLAndDictionary($url, $dictionary)
	{
		$instance             = new AMDisplayObject();
		$instance->url        = $url;
		$instance->dictionary = $dictionary;
		
		return $instance;
	}
	public static function initWithURL($url)
	{
		$instance = new AMDisplayObject();
		$instance->url = $url;
		return $instance;
	}
	
	public static function initWithString($string)
	{
		$instance = new AMDisplayObject();
		$instance->source = $string;
		return $instance;
	}
	
	public static function initWithDictionary(array $dictionary)
	{
		$instance = new AMDisplayObject();
		$instance->setDataProvider($dictionary);
		
		return $instance;
	}
	
	public static function renderDisplayObjectWithURLAndDictionary($url, &$dictionary=null)
	{
		static $suffix;// = 1;
		
		if(!in_array("displayObjectRenderer_$suffix", stream_get_filters()))
			stream_filter_register("displayObjectRenderer_$suffix", "AMDisplayObjectRenderer");
			
		$pointer = fopen($url, "r");
		
		if($dictionary)
			stream_filter_append($pointer, "displayObjectRenderer_$suffix", STREAM_FILTER_READ, $dictionary);
		else
			stream_filter_append($pointer, "displayObjectRenderer_$suffix", STREAM_FILTER_READ);
		
		//$suffix++;
		return stream_get_contents($pointer);
	}
	
	public function render()
	{
		if($this->source)
		{
			$output = $this->source;
			
			foreach($this->dictionary as $key => $value){
				$this->applyPropertyToView($key, $value, $output);
			}
			
			return $output;
		}
		else
		{
			return AMDisplayObject::renderDisplayObjectWithURLAndDictionary($this->url, $this->dictionary);
		}
	}
	
	protected function applyPropertyToView($name, $value, &$component)
	{
		$component = str_replace("{".$name."}", $value, $component);
	}
	
	public function setDataProvider($dictionary)
	{
		foreach($dictionary as $key=>$value)
			$this->$key = $value;
	}
	
	public function __toString()
	{
		return $this->render();
	}
}

class AMDisplayObjectRenderer extends php_user_filter 
{
	const kTempStreamMemoryLimit = 10240; // 10 * 1024 : 10k
	
	public function filter($in, $out, &$consumed, $closing)
	{
		while ($bucket = stream_bucket_make_writeable($in)) 
		{
			if(count($this->params))
			{
				foreach($this->params as $key => $value)
				{
					//echo 'key is: ', $key, '<br>';
					if(is_object($value))
					{
						$outValue = $value->__toString();
					}
					else if(!empty($value) && is_array($value))
					{
						/*
							We open a temp stream here, because if the array that comes in maps to a template that is 
							HUGE then we will quickily run out of system resources by $outValue .= $item->__toString()
							as $outValue will be storing the output of another DisplayObject of arbitrary size.
						*/
						
						
						$stream_temp  = fopen("php://temp/maxmemory:".AMDisplayObjectRenderer::kTempStreamMemoryLimit, 'r+');
						
						foreach($value as $item)
						{
							if(is_object($item))
							{
								fwrite($stream_temp, $item->__toString());
							}
						}
					}
					else
					{
						$outValue = $value;
					}
					
					if(isset($outValue))
					{
						if(strpos($bucket->data, '{'.$key.'}') !== false){
							$bucket->data = str_replace('{'.$key.'}', $outValue, $bucket->data);
						}
						
						$outValue = null;
					}
					if(isset($stream_temp) && ftell($stream_temp))
					{
						rewind($stream_temp);
						
						if(strpos($bucket->data, '{'.$key.'}') !== false){
							$bucket->data = str_replace('{'.$key.'}', stream_get_contents($stream_temp), $bucket->data);
						}
						
						fclose($stream_temp);
					}
				}
			}
			
			$this->cleanup($bucket->data);
			
			$consumed += $bucket->datalen;
			stream_bucket_append($out, $bucket);
		}
		return PSFS_PASS_ON;
	}
	
	private function cleanup(&$data)
	{
		$data = preg_replace('/\{[\d\w]+\}/', '', $data);
	}
	
	public function onCreate()
	{
		return true;
	}
}

 
?>