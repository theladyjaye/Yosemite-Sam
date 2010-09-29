<?php


// Combines api calls for each page


$service = $_REQUEST['service'];
$host = "http://".$_SERVER['HTTP_HOST'];
$server_name = explode(".", $_SERVER['SERVER_NAME']);
$domain = $server_name[0];
$response = array("ok" => true);


$domain_info = get_domain_info($host, $domain);

if($domain_info->ok)
{
	switch($service)
	{
		case "project":
			$response["result"] = array("projects" => json_decode(file_get_contents("$host/api/projects")),
										"account" => $domain_info->company);
			break;
		
		case "view":
			$response["result"] = array("project" => get_project($host, $_REQUEST['project']),
										"views" => json_decode(file_get_contents("$host/api/project/" . $_REQUEST['project'] . "/views")));
			break;
		
		case "state":
			$response["result"] = array("project" => get_project($host, $_REQUEST['project']),
										"view" => get_view($host, $_REQUEST['project'], $_REQUEST['view']),
										"states" => json_decode(file_get_contents("$host/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/states")),
										"annotations" => json_decode(file_get_contents("$host/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/" . $_REQUEST['state'] . "/annotations")));
			break;
		
		case "annotate":
			$response["result"] = array("state" => get_state($host, $_REQUEST['project'], $_REQUEST['view'], $_REQUEST['state']),
										"annotations" => json_decode(file_get_contents("$host/api/project/" . $_REQUEST['project'] . "/" . $_REQUEST['view'] . "/" . $_REQUEST['state'] . "/annotations")),
										"task_groups" => json_decode(file_get_contents("$host/api/project/" . $_REQUEST['project'] . "/group/task")));
			break;
		
		case "settings":
			$response["result"] = array("projects" => json_decode(file_get_contents("$host/api/projects")),
										"users" => json_decode(file_get_contents("$host/api/account/$domain/users"))->users,
										"account" => $domain_info->company);
			break;
	}
}
else
{
	$response["ok"] = false;
}

//print_r($response);
echo json_encode($response);



// ==================================

function get_domain_info($host, $domain)
{
	return json_decode(file_get_contents("$host/api/account/$domain"));
}

function get_project($host, $project_name)
{
	$projects = json_decode(file_get_contents("$host/api/projects"));
	return find_item($projects, "project/$project_name");	
}

function get_view($host, $project_name, $view_name)
{
	$views = json_decode(file_get_contents("$host/api/project/$project_name/views"));
	return find_item($views, "project/$project_name/$view_name");
}

function get_state($host, $project_name, $view_name, $state_name)
{
	$states = json_decode(file_get_contents("$host/api/project/$project_name/$view_name/states"));
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

?>