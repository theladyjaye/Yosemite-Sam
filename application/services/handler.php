<?php
/*
require 'application/system/YSSApplication.php';
require 'application/libs/axismundi/display/AMDisplayObject.php';
require 'application/templates/Projects.php';
*/

// Combines api calls for each page
define("API_HOST", "yss.com");

$service     = $_REQUEST['service'];
//$host        = $_SERVER['HTTP_HOST'];
$server_name = explode(".", $_SERVER['SERVER_NAME']);
$domain      = $server_name[0];
$response    = array("ok" => true);


$domain_info = get_domain_info($domain);

if($domain_info->ok)
{
	switch($service)
	{
		case "project":
			$response["result"] = array("projects"    => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/projects"))),
										"account"     => $domain_info->company);
			break;
		
		case "view":
			$response["result"] = array("project"     => get_project($_REQUEST['project']),
										"views"       => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/project/" . $_REQUEST['project'] . "/views"))),
										"account"     => $domain_info->company);
			break;
		
		case "state":
			$response["result"] = array("project"     => get_project($_REQUEST['project']),
										"view"        => get_view($_REQUEST['project'], $_REQUEST['view']),
										"states"      => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/states"))),
										"annotations" => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/" . $_REQUEST['state'] . "/annotations"))),
										"account"     => $domain_info->company);
			break;
		
		case "annotate":
			$response["result"] = array("state"       => get_state($_REQUEST['project'], $_REQUEST['view'], $_REQUEST['state']),
										"annotations" => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/" . $_REQUEST['state'] . "/annotations"))),
										"task_groups" => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/project/" . $_REQUEST['project'] . "/group/task"))),
										"account"     => $domain_info->company,
										"users"		  => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/account/$domain/users")))->users);
			break;
		
		case "settings":
			$response["result"] = array("projects"    => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/projects"))),
										"users"       => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/account/$domain/users/active")))->users,
										"inactive"       => json_decode(peeq_api_request(array('method' => 'GET', 'path' => "/api/account/$domain/users/inactive")))->users,
										"account"     => $domain_info->company);
			break;
	}
}

else
{
	$response["ok"] = false;
}

echo json_encode($response);



// ==================================

function get_domain_info($domain)
{
	$json = peeq_api_request(array('method' => 'GET',
	                               'path'   => "/api/account/$domain"));
	return json_decode($json);
}

function get_project($project_name)
{
	//$projects = json_decode(file_get_contents("$host/api/projects"));
	
	$projects = null;
	$json = peeq_api_request(array('method' => 'GET',
	                               'path'   => "/api/projects"));
	$projects = json_decode($json);
	return find_item($projects, "project/$project_name");
}

function get_view($project_name, $view_name)
{
	//$views = json_decode(file_get_contents("$host/api/project/$project_name/views"));
	$views = null;
	$json = peeq_api_request(array('method' => 'GET',
	                               'path'   => "/api/project/$project_name/views"));
	$views = json_decode($json);
	return find_item($views, "project/$project_name/$view_name");
}

function get_state($project_name, $view_name, $state_name)
{
	//$states = json_decode(file_get_contents("$host/api/project/$project_name/$view_name/states"));
	$states = null;
	$json = peeq_api_request(array('method' => 'GET',
	                               'path'   => "/api/project/$project_name/$view_name/states"));
	$states = json_decode($json);
	return find_item($states, "project/$project_name/$view_name/$state_name");
}

function find_item($items, $needle)
{
	foreach($items as $item)
	{
		if($item->_id == $needle)
		{
			$found_item = $item;
			break;
		}
	}
	
	return $found_item;
}

function peeq_api_request($options)
{
	// since we don't include any of the environment 
	// stuffs we need to manually start the session.
	// if you uncomment the lines up top, you will need 
	// to see if you need this anymore.
	// also it appears to be critical that you end the 
	// session prior to sending the PHP session over cURL
	// or over a socket.
	
	session_start();
	$session_cookie = session_name().'='.session_id();
	session_write_close();
	
	extract($options);

	$request = array("$method $path HTTP/1.0",
	                 "Host: ".API_HOST,
                     "Cookie: $session_cookie",
	                 "Connection: Close");
	
	$errno    = null;
	$errstr   = null;
	$response = null;
	$timeout  = 30;
	
	$stream = stream_socket_client("tcp://".API_HOST.":80", $errno, $errstr, $timeout);
	
	if(!$stream)
	{
		throw new Exception('Unable to connect to host '.$socket.' : '.$errno.', '.$errstr);
		return;
	}
	else
	{
		fwrite($stream, implode("\r\n", $request)."\r\n\r\n");
		$response = stream_get_contents($stream);
		fclose($stream);

		list($headers, $body) = explode("\r\n\r\n", $response);
		return $body;
	}

	fclose($stream);
}

?>