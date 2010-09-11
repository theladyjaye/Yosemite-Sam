<?php
// test file for ajax validation for jquery.validation.js

$username = $_POST["username"];

if($username == "lily")
{
	//echo "success";
	echo json_encode(array("ok" => true));	
	exit;
}

//echo "error";
echo json_encode(array("ok" => false));
?>