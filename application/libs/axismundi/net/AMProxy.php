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
 *    @package net
 *    @author Adam Venturella <aventurella@gmail.com>
 *    @copyright Copyright (C) 2009 Adam Venturella
 *    @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 *   OVERVIEW: 
 *
 *   Updated version based on my CouchDBProxy Class
 * 
 *   Where would you like the requests to go?
 *   if($allowed)
 *   {
 *       $proxy = new AMProxy();
 *       $proxy->setHost($host);
 *       $proxy->setPort($port);
 *       $proxy->setFollowRedirects(true);
 *       $proxy->setCompression('gzip');
 *       $proxy->setHeaders($request_headers); 
 *       $proxy->proxy($url);
 *   }
 *
 *   Sample Apache Virtual Host Setup
 *   NOTE: Please note the use of the AllowEncodedSlashes On directive
 *   Prior to allowing this, replication would fail.  If you don't need
 *   replication, don't worry about it, though you are missing out =)
 *
 *   <VirtualHost *:80>
 *      ServerAdmin aventurella@gmail.com
 *      AllowEncodedSlashes On
 *      DocumentRoot "/path/to/http/root"
 *      ServerName proxy.example.com
 *      ErrorLog "couchdb_proxy-error_log"
 *      CustomLog "couchdb_proxy-access_log" common
 *      <Directory "/path/to/http/root">
 *          Options FollowSymLinks
 *          AllowOverride All
 *          Order allow,deny
 *          Allow from all
 *     </Directory>
 *  </VirtualHost>
 *
 *  Sample Rewrite Rules:
 *  RewriteCond %{REQUEST_METHOD} ^(GET|HEAD|POST|PUT|DELETE) [NC]
 *  RewriteRule .* proxy.php [QSA,L]
 *
 **/
class AMProxyResponse
{
	public $headers;
	public $body;
	
	public function __construct()
	{
		$this->headers = array();
	}
}

class AMProxy
{
	public $timeout = 10;
	public $response;
	public $request_info;
	public $request_headers;
	
	private $url;
	private $host;
	private $port = 80;
	private $headers;
	private $compression;
	private $followRedirects = false;
	
	/**
	 * Initialize the proxy service
	 *
	 * @param string $host the host where the requests should be forwarded
	 * @param string $port the port on the host to use
	 * @author Adam Venturella
	 */
	public function __construct(){}
	
	
	public function setPort($port=80)
	{
		$this->port = $port;
	}
	
	public function setHost($host)
	{
		$this->host = $host;
	}
	
	public function setCompression($compression)
	{
		$this->compression = $compression;
	}
	
	public function setFollowRedirects($value)
	{
		$this->followRedirects = $value;
	}
	
	public function setHeaders($array)
	{
		$this->headers = $array;
	}
	
	/**
	 * Begin proxying
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	
	public function proxy($url=null)
	{
		$verb      = strtolower($_SERVER['REQUEST_METHOD']);
		$command   = null;
		$this->url = $url ? $url : $_SERVER['REQUEST_URI'];
		
		switch($verb)
		{
			case 'get':
				$command = $this->proxy_get();
				break;
			
			case 'post':
				$command = $this->proxy_post();
				break;
				
			case 'put':
				$command = $this->proxy_put();
				break;
			
			case 'delete':
				$command = $this->proxy_delete();
				break;
			
			case 'head':
				$command = $this->proxy_head();
				break;
		}
		
		if($command)
		{
			$this->response = new AMProxyResponse();
			curl_exec($command);
			$this->request_info = curl_getinfo($command);
			curl_close($command);
			
		}
	}
	
	/**
	 * Handle GET requests
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function proxy_get()
	{
		return $this->request();
	}
	
	/**
	 * Handle HEAD requests
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function proxy_head()
	{
		$command = $this->request();
		curl_setopt( $command, CURLOPT_NOBODY, true);
		return $command;
	}
	
	/**
	 * Handle POST requests
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function proxy_post()
	{
		$command = $this->request();
		$data    = file_get_contents("php://input");
		curl_setopt($command, CURLOPT_POST, true);
		curl_setopt($command, CURLOPT_POSTFIELDS, $data);
		
		return $command;
	}
	
	/**
	 * Handle DELETE Requests
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function proxy_delete()
	{
		$command = $this->request();
		curl_setopt($command, CURLOPT_CUSTOMREQUEST, 'DELETE');  
		return $command;
	}
	
	/**
	 * Handle PUT requests
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function proxy_put()
	{
		$command = $this->request();
		
		$data     = file_get_contents("php://input");
		curl_setopt($command, CURLOPT_CUSTOMREQUEST, 'PUT');  
		curl_setopt($command, CURLOPT_POSTFIELDS, $data);
		
		return $command;
	}
	
	/**
	 * Build the basic request
	 *
	 * @return void
	 * @author Adam Venturella
	 */
	private function request()
	{
		$action    = $_SERVER['REQUEST_METHOD'];
		$url       = $this->url;
		
		$params    = null;
		
		$context   = array();
		
		$context[] = 'Host: '.$this->host.':'.$this->port;

		foreach($this->headers as $key=>$value)
		{
			if(strtolower($key) != 'host')
			{
				$context[] = $key.': '.$value;
			}
		}
		
		$command = curl_init();
		curl_setopt( $command, CURLOPT_HTTPHEADER, $context);
		
		// we could enable this, but we have a write function specified below, so
		// it's pretty much the same thing.
		//curl_setopt( $command, CURLOPT_RETURNTRANSFER, true);

		curl_setopt( $command, CURLOPT_URL, "http://".$this->host.':'.$this->port.$this->url);
		curl_setopt( $command, CURLOPT_BINARYTRANSFER, true );
		curl_setopt( $command, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $command, CURLOPT_HEADERFUNCTION, array($this,'processResponseHeaders'));
		curl_setopt( $command, CURLOPT_WRITEFUNCTION, array($this,'processResponseBody'));
		
		// we want to know what the request headers were
		curl_setopt( $command, CURLINFO_HEADER_OUT, true);
		
		// follow redirect
		if($this->followRedirects)
		{
			curl_setopt( $command, CURLOPT_FOLLOWLOCATION, true);
			//curl_setopt( $command, CURLOPT_AUTOREFERER, true);
		}
		
		if($this->compression)
		{
			curl_setopt( $command, CURLOPT_ENCODING , $this->compression);
		}
		
		return $command;
	}
	
	/**
	 * Process the response body
	 *
	 * @param cURL $command reference to the curl command used to generate this response
	 * @param string $data the response body
	 * @return void
	 * @author Adam Venturella
	 */
	private function processResponseBody(&$command, $data)
	{
		$bytes = strlen($data);
		$this->response->body .= $data;
		return $bytes;
	}
	
	/**
	 * Process the response headers
	 *
	 * @param cURL $command reference to the curl command used to generate this response
	 * @param string $header current header in the response
	 * @return void
	 * @author Adam Venturella
	 */
	private function processResponseHeaders(&$command, $header)
	{
		$bytes = strlen($header);
		
		// cURL handles chunked decoding for us, so a response from 
		// this proxy will never be chunked
		
		if ($header !== "\r\n" && strpos($header, 'chunked') === false)
		{
			$header = rtrim($header);
			$this->response->headers[] = $header;
		}
		
		return $bytes;
	}
}
?>