<?php
/**
 *    CouchDB_PHP
 * 
 *    Copyright (C) 2009 Adam Venturella
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
 *    @package CouchDB_PHP
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2009 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 **/

/**
 * Includes
 */
require_once 'CouchDBCommand.php';

/**
 * Get Document Command
 *
 * @package Commands
 * @author Adam Venturella
 */
class CDBRequest implements CouchDBCommand 
{
	private $database;
	private $options;
	
	/**
	 * undocumented function
	 *
	 * @param string $database 
	 * @param string $id 
	 * @author Adam Venturella
	 */
	public function __construct($database, $options)
	{
		$this->database = $database;
		$this->options  = $options;
	}
	
	public function request()
	{
		$method         = $this->options['method'];
		$path           = $this->options['path'];
		$query          = $this->options['query'] ? '?'.http_build_query($this->options['query']) : null;
		$data           = $this->options['data'] ? $this->options['data'] : null;
		$content_length = 'Content-Length:';
		
		$path = $path[0] == '/' ? $path : '/'.$path;  
		
		if($data)
			$content_length = $content_length.strlen($data);
		else
			$content_length = $content_length.'0';
		
		return <<<REQUEST
$method $path$query HTTP/1.0
Host: {host}
Connection: Close
$content_length
{authorization}

$data
REQUEST;
	}
	
	public function __toString()
	{
		return 'Request';
	}
}
?>