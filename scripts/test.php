<?php
include 'importImage.php';
$db = mysql_connect("localhost","root","password");
/*if (importImageAsFodder ('Jellyfish.jpg',$db)){
	echo 'YES!';
}else{
	echo 'NYOOOOOOO!';
}*/
$bla = importImageAsCannon ('picpic.jpg',$db);
$list = array();
/*for($i=1000; $i>950; $i--){
	$list[]=$i;
}*/
for ($i=0; $i<950;$i++){
	$list[]=$i;
}
if (!mysql_select_db('collageify',$db )){
	echo("Can't select db".mysql_error());
}
if (!mysql_query("INSERT INTO collages VALUES ('','testA','1','".implode(",", $list)."','',0)", $db )){
	echo "Bad query: .".mysql_error();
}
$fp = fopen('cannon'.mysql_insert_id($db).'.txt', 'w');
fwrite($fp, $bla);
fclose($fp);
$matrix = createCollage(mysql_insert_id($db),$db);
$im = drawCollage($matrix);
header('Content-Type: image/png');
imagepng($im);
//importImageAsFodder('loginPic.jpg',$db,123);
//var_dump ($bla);
echo "done";
//$yay = unpackFramework($bla);
//var_dump($yay);
?>