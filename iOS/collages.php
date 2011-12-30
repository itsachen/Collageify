<?php

$user_id = $_REQUEST['user_id'];

$tempArray = array();

$conn = mysql_connect("localhost","root","password");
if(!$conn){
	$tempArray["success"]="0";
	$tempArray["error"]="Could not connect to database";
	die(json_encode($tempArray));
}

@mysql_select_db('collageify');
$query = "SELECT * FROM users WHERE fbid=$user_id";
$result = mysql_fetch_row(mysql_query($query));

$recentArray = explode(",",$result[3]);
$allArray = explode(",",$result[1]);

$recentIds = array();
foreach($recentArray as $a){
	$query = mysql_query("SELECT id FROM collages WHERE name=$a");
	if($query!==false){
		array_push($recentIds, mysql_result($query,0));
	}
}
$allIds = array();
foreach($allArray as $b){
	$query = mysql_query("SELECT id FROM collages WHERE name=$b");
	if($query!==false){
		array_push($allIds, mysql_result($query,0));
	}
	
}

$tempArray["success"]="1";
$tempArray["error"]="";
$tempArray["recent"] = $recentArray;
$tempArray["all"] = $allArray;

echo json_encode($tempArray);

?>