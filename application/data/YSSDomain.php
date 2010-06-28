<?php
class YSSDomain
{
	public static function create($domain)
	{
		$path          = YSSApplication::basePath().'/application/data/domain';
		
		$obj           = new stdClass();
		$obj->language = "javascript";
		
		self::parse($path, $obj);
		$database = YSSDatabase::connection(YSSDatabase::kCouchDB, $domain);
		$database->create_database();
		$database->put($obj, '_design/project');
	}
	
	private static function parse($path, &$context)
	{
		$directory = dir($path);
		while (false !== ($entry = $directory->read())) 
		{
			if($entry != '.' && $entry != '..')
			{
				if(is_dir($path.'/'.$entry))
				{
					self::parse($path.'/'.$entry, $context->{$entry});
				}
				else if (is_file($path.'/'.$entry))
				{
					$key             = substr($entry, 0, -3);
					$contents        = file_get_contents($path.'/'.$entry);
					$contents        = preg_replace('/\n|\r|\t/', '', $contents);
					$context->{$key} = $contents;
				}
			}
		}
	}
}
?>