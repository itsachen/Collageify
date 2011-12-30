<?php

require_once 'fbsdk/src/facebook.php';
require_once '../scripts/importImage.php';

if (isset($_GET["id"])){
	$id= $_GET["id"];
	
	switch ($id){
		case "thumb":
		mysql_connect("localhost","root","password");
		mysql_select_db("collageify");
		$query= "SELECT id FROM collages WHERE name=\"" . $_GET["name"]."\"";
		$result= mysql_result(mysql_query($query),0);
		echo $result;
		//echo $result["id"];
		mysql_close();
		break;
		
		case "getalbum":
		$uid= $_GET["uid"];
		showAlbums($uid);
		break;
		
		case "getcontrib":
		$uid= $_GET["uid"];
		showContrib($uid);
		break;
		
		case "uploadalbum":
		$aid= $_GET["aid"];
		echo $aid;
		$uid= $_GET["uid"];
		$cid= $_GET["cid"];
		uploadAlbum($aid,$uid,$cid);
		break;
	}
	
	
}

function showContrib($uid){
	mysql_connect("localhost","root","password");
	mysql_select_db("collageify");
	$query= "SELECT collagelist FROM users WHERE fbid=" . $uid;
	$result= mysql_fetch_array(mysql_query($query));
	$listcsv= $result["collagelist"];
	$listarray= explode(",",$listcsv);
	
	echo "Select an album to continue working on </br>";
	echo "<table>";
	foreach ($listarray as $item){
		echo "<tr>";
		//echo "<td><img src=\"" . $album["url"] . "\" /></td>";
		echo "<td><a href=\"\">" . $item . "</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}

function showAlbums($uid){
	
	$facebook = new Facebook(array(
	  'appId'  => '294222680618585',
	  'secret' => 'b4fcd6688d92d74d9bd2cfd615477510',
	));

	$albumfinal= array();
	
	try {
		$fql= "SELECT name, cover_pid, aid FROM album WHERE owner=$uid";
		$ret_obj = $facebook->api(array('method' => 'fql.query',
		                                'query' => $fql,
		                                 ));
		$i= 0;
		foreach ($ret_obj as $album){
			//Photos to load
			if ($i == 7) break;
			$temp= array();
			$temp["name"]= $album["name"];
			$temp["aid"]= $album["aid"];
			$pid= $album["cover_pid"];
			
			$fql= "SELECT src_small FROM photo where pid=$pid";
			$ret_obj = $facebook->api(array('method' => 'fql.query',
			                                'query' => $fql,
			                                 ));
			$url=$ret_obj[0]["src_small"];
			$temp["url"]= $url;
			$albumfinal[$i]= $temp;
			$i++;
		}
		echo "<div id=\"tablediv\">";
		echo "Select an album to add </br>";
		echo "<table>";
		foreach ($albumfinal as $album){
			echo "<tr>";
			echo "<td><img src=\"" . $album["url"] . "\" /></td>";
			$stringaid= "'" . strval($album["aid"]) . "'";

			$stringuid= strval($uid);
			echo "<td><a href=\"javascript: uploadAlbum(". $stringaid .",".$stringuid.",1)\">" . $album["name"] . "</a></td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
		
	 } catch (FacebookApiException $e) {
		error_log($e);
	    $user = null;
	}
}

function uploadAlbum($aid,$uid,$cid){
	
	$facebook = new Facebook(array(
	  'appId'  => '294222680618585',
	  'secret' => 'b4fcd6688d92d74d9bd2cfd615477510',
	));
	try {
		$fql= "SELECT src_big FROM photo WHERE aid=$aid";
		$pictures = $facebook->api(array('method' => 'fql.query',
		                                'query' => $fql,
		                                 ));
		echo var_dump($aid);
		$mysql= mysql_connect("localhost","root","password");
	foreach ($pictures as $pic){
		$path= "imgdata/users/". $uid . "/" . "temp.jpg";
		file_put_contents($path, file_get_contents($pic["src_big"]));
		$picid= importImageAsFodder($path, $mysql, $uid);
		
		//add picid
		mysql_select_db("collageify") or die( "Unable to select database");
		$query="SELECT imagelist FROM collages WHERE id=$cid";
		$result= mysql_query($query);
		
		$old= $result["imagelist"];
		$new= $old + ",$picid";
		
		$updatequery= "UPDATE collages SET imagelist = $new WHERE id = $cid";
		mysql_query($updatequery);
		
		rename ("imgdata/users/". $uid . "/" . "temp.jpg","imgdata/users/". $uid . "/" . $picid .".jpg");
		
	}
	mysql_close();
	} catch (FacebookApiException $e) {
			error_log($e);
		    $user = null;
	}
}

function uploadDirectory($dirpath){
	
	$dirarray= scandir($dirpath);
	$foo= mysql_connect("localhost","root","password");
	$i= 0;
	foreach ($dirarray as $k => $v){
		
		if ($i == 1000) break;
		
		if ($i > 8){
		$path= "imgdata/users/". "1337" . "/" . "temp.jpg";
		file_put_contents($path, file_get_contents("ocean/" . $v));
		$picid= importImageAsFodder($path, $foo, 1337);
		
		//add picid
		mysql_select_db("collageify") or die( "Unable to select database");
		$query="SELECT imagelist FROM collages WHERE id=2";
		$result= mysql_query($query);
		
		$old= $result["imagelist"];
		$new= $old + ",$picid";
		
		$updatequery= "UPDATE collages SET imagelist = $new WHERE id = 2";
		mysql_query($updatequery);
		
		rename ("imgdata/users/". 1337 . "/" . "temp.jpg","imgdata/users/". 1337 . "/" . $picid .".jpg");
		}
		$i++;
		
	}
	mysql_close();
	
}

/*
$id= $_GET["id"];

mysql_connect("localhost","root","password");
@mysql_select_db("collageify") or die( "Unable to select database");

$query="SELECT 
$result= mysql_query($query);
*/
?>
