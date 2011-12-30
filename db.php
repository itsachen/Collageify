<?php
if (isset($_REQUEST["name"])){
	$newname= $_REQUEST["name"];
	$userid= $_REQUEST["uid"];
	mysql_connect("localhost","root","password");
	mysql_select_db("collageify");
	
	$query= "INSERT INTO `collages` (
	`id` ,
	`name` ,
	`userlist` ,
	`imagelist` ,
	`structure`,
	`numusers`
	)
	VALUES (
	NULL ,  \"" . $newname . "\",". $userid. ", NULL ,  '',1
	)";
	echo $query;
	$result= mysql_query($query);
	mysql_close();
	
}
function isFirstTimeUser($userid){
	mysql_connect("localhost","root","password");
	@mysql_select_db("collageify") or die( "Unable to select database");
	
	$query="SELECT * FROM users WHERE fbid= $userid";
	$result= mysql_query($query);
	
	if (!$result){
		return true;
	}
	else {
		return false;
	}
	
	mysql_close();
}



?>