<?php

include "../../scripts/importImage.php";

$collage_id = $_REQUEST['collage_id'];
$user_id = $_REQUEST['user_id'];
/*
$directory1 = 'imgs/'.$user_id.'/';
if(!is_dir($directory1)){
	@mkdir($directory1);
}*/

$uploaddir = 'imgs/'.$user_id.'/';
if(!is_dir($uploaddir)){
	@mkdir($uploaddir);
}

$filename = time().".jpg";
$uploadPath = $uploaddir . $filename;

$outputArray = array("status"=>0,"error"=>"","filesize"=>0,"location"=>"");

if (is_uploaded_file($_FILES['userfile']['tmp_name']))
{
	
} else {
	$outputArray["status"] = 0;
	$outputArray["error"] = "Upload Failed! (Error code 1)";
	exit(json_encode($outputArray));
}

$filesize = $_FILES['userfile']['size'];
$outputArray["filesize"] = $filesize;
if ($filesize > 30000000)     //Limiting image at 30MB
{
	$outputArray["status"] = 0;
	$outputArray["error"] = "Upload Failed! File too large! (".$filesize.")";
	exit(json_encode($outputArray));
}

if ($_FILES['userfile']['type'] != "image/jpeg")
{
	$outputArray["status"] = 0;
	$outputArray["error"] = "Upload Failed! Wrong file type! (".$_FILES['userfile']['type'].")";
	exit(json_encode($outputArray));
}

if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadPath)) {
        $postsize = ini_get('post_max_size');   
        $canupload = ini_get('file_uploads');    
        $tempdir = ini_get('upload_tmp_dir');   
        $maxsize = ini_get('upload_max_filesize');
        
		$conn = mysql_connect("localhost",'root','password');
		if(!$conn){
			$outputArray["status"] = 0;
			$outputArray["error"] = "Error Connecting to Database";
			$outputArray["location"] = "";
			die(json_encode($outputArray));
		}
		$newId = importImageAsFodder($uploadPath, $conn, $user_id);
		if($newId===false){ 
			$outputArray["status"] = 0;
			$outputArray["error"] = "DETIAN'S FAULT";
			$outputArray["location"] = "";
			die(json_encode($outputArray));
		}
		@mysql_select_db('collageify');
		$query1="(SELECT imagelist FROM collages WHERE id=$collage_id)";
		$intermediate = mysql_result(mysql_query($query1),0);
		$query = "UPDATE collages SET imagelist='".$intermediate.",".$newId."' WHERE id=$collage_id";
		mysql_query($query);
		mysql_close($conn);
		
		
        $outputArray["status"] = 1;
        $outputArray["error"] = "";
        $outputArray["location"] = "http://192.168.137.1/iOS/".$uploadPath;
        
        echo json_encode($outputArray);
}else{
	$outputArray["status"] = 0;
	$outputArray["error"] = "Upload Failed! (Error code 2)";
	echo json_encode($outputArray);
}

?>