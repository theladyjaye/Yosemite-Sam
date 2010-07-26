<?php
class YSSDomain
{
	public static function create($domain)
	{
		if(!in_array("YSSDomainMacroJson", stream_get_filters()))
			stream_filter_register("YSSDomainMacroJson", "YSSDomainMacroJson");
			
		$base_path     = YSSApplication::basePath().'/application/data/domain';
		
		$obj           = new stdClass();
		$obj->language = "javascript";
		
		self::parse($base_path, $obj, $base_path);
		
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $domain);
		$database->create_database();
		
		print_r($database->put($obj, '_design/project'));
	}
	
	private static function parse($path, &$context, $base_path)
	{
		
		$directory = dir($path);
		while (false !== ($entry = $directory->read()))
		{
			if($entry != '.' && $entry != '..')
			{
				if(is_dir($path.'/'.$entry))
				{
					self::parse($path.'/'.$entry, $context->{$entry}, $base_path);
				}
				else if (is_file($path.'/'.$entry))
				{
					$startExtension = strrpos($entry, '.');
					
					if($startExtension !== false)
						$key = substr($entry, 0, $startExtension);
					else
						$key = $entry;

					$file = fopen($path.'/'.$entry, "r");
					stream_filter_append($file, "YSSDomainMacroJson", STREAM_FILTER_READ, $base_path);

					$contents        = stream_get_contents($file);
					
					//$contents        = file_get_contents($path.'/'.$entry);
					//$contents        = preg_replace('/\t/', '', $contents);
					//$contents        = preg_replace('/\n|\r|\t/', '', $contents);
					echo 'key:', $key,"<br>contents:",$contents,"<hr>";
					$context->{$key} = $contents;
				}
			}
		}
	}
}

class YSSDomainMacroJson extends php_user_filter 
{
	private $bucket;
	private $data;
	private $macro_collection;
	public function filter($in, $out, &$consumed, $closing)
	{
		$macro    = '// !json';
		$position = false;
		
		while ($bucket = stream_bucket_make_writeable($in)) 
		{
			$position = strpos($bucket->data, $macro);
			
			if($position !== false)
			{
				while ($position = strpos($bucket->data, $macro))
				{
					// get rid of the macro part:
					$segment1 = substr($bucket->data, 0, $position);
					$segment2 = substr($bucket->data, $position+strlen($macro));
					
					// $segment 2 will contain the path:
					$path     = trim(substr($segment2, 0, strpos($segment2, "\n")));
					$segment2 = substr($segment2, strlen($path) + 1);
					
					$parts  = explode('.', $path);
					$key    = array_shift($parts);
					$length = count($parts);
					
					if(!isset($this->macro_collection[$key]))
						$this->macro_collection[$key] = new stdClass();
					
					$object =& $this->macro_collection[$key];
					
					while($length > 0)
					{
						$next = array_shift($parts);
						
						if(!isset($object->{$next}))
							$object->{$next} = new stdClass();
						
						$length = $length - 1;
						
						if($length == 0)
						{
							$filename = strtr($path, ".", "/");
							$filename .= ".json";
							
							$object->{$next} = json_decode(file_get_contents($this->params.'/'.$filename));
						}
						else
						{
							$object = $object->{$next};
						}
					}
					
					$bucket->data  = $segment1;
					$bucket->data .= $segment2;
				}
			}
			
			
			//$consumed += $bucket->datalen;
			$consumed     = 0;
			$this->data  .= $bucket->data;
			$this->bucket = $bucket;
			//stream_bucket_append($out, $bucket);
		}
		
		if($closing)
		{
			if(count($this->macro_collection))
			{
				$insert = "";
				foreach($this->macro_collection as $key=>$value)
					$insert .= "\nvar ".$key.'='.json_encode($this->macro_collection[$key]).";\n";
				
				$segment1 = substr($this->bucket->data, 0, strpos($this->bucket->data, '{') + 1);
				$segment2 = substr($this->bucket->data, strpos($this->bucket->data, '{') + 1);
				
				$this->bucket->data = $segment1.$insert.$segment2;
			}
			else
			{
				$this->bucket->data  = $this->data;
			}
			
			$consumed += strlen($this->bucket->data);
			$this->bucket->datalen = $consumed;
			stream_bucket_append($out, $this->bucket);
			
			return PSFS_PASS_ON;
		}
		
		return PSFS_FEED_ME;
	}
	
	public function onClose()
	{
		$this->macro_collection = null;
		$this->data             = null;
		$this->bucket           = null;
	}
	
	public function onCreate()
	{
		$this->macro_collection = array();
		$this->data             = null;
		$this->bucket           = null;
		return true;
	}
}
?>