<?php
	/*$source_image = null;
	if (is_file($source)){
		$source_image = imagecreatefromstring(file_get_contents($source));
	}else{
		$source_image = imagecreatefromstring(file_get_contents("./projects/noImage.png"));
	}
	$source_imagex = imagesx($source_image);
	$source_imagey = imagesy($source_image);

	$dest_imagex = $x;
	$dest_imagey = $y;
	$dest_image = imagecreatetruecolor($dest_imagex, $dest_imagey);

	imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $dest_imagex, 
				$dest_imagey, $source_imagex, $source_imagey);

	header("Content-Type: image/jpeg");
	imagejpeg($dest_image,NULL,80);*/
//function image_resize($src, $dst, $width, $height, $crop=0){
//echo $source;
$width = $x;
$height = $y;
$src = $source;
$crop = 0;
if (file_exists($src))
  $type = strtolower(substr(strrchr($src,"."),1));
else $type='';

  if(!list($w, $h) = getimagesize($src)){ //Unsupported picture type!
	$w = 125;
	$h = 125;
	//$type = "png";
  }
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : $img = imagecreatefromstring(file_get_contents("./projects/noImage.png")); break;
  }

  // resize
  if($crop){
   // if($w < $width or $h < $height) echo "Picture is too small!";
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    //if($w < $width and $h < $height) echo "Picture is too small!";
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
  header("Content-Type: image/jpeg");
  imagejpeg($new,$dst,80);
/*
  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;*/
//}
?>