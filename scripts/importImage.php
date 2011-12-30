<?php
function implodeMulti($glueA, $glueB, $array){
	$output = "";
	for ($r=0; $r<count($array)-1; $r++){
		/*for ($j=0; $j<count($array[0]); $j++){
			$output+=$array[i][j].$glue;
		}*/
		$output.=implode($glueA,$array[$r]).$glueB;
	}
	$output.=implode($glueA,$array[$r]);
	return $output;
}

function explodeMulti($glueA, $glueB, $array){
	$output = array();
	$r=0;
	$c=0;
	foreach (explode($glueB,$array) as $row){
		foreach (explode($glueA, $row) as $item){
			$output[$r][$c] = $item;
			$c++;
		}
		$c=0;
		$r++;
	}
	return $output;
}
function helper42 ($data){
//var_dump($data);
	return implode("~",array_values($data));
}
function getAverages($image, $size, $startX=0,$startY=0){
	$average = array(0,0,0);
	//$pixels = array();
	for ($x=$startX; $x<$startX+$size; $x++){
		for ($y=$startY; $y<$startY+$size; $y++){
			$pixel = imagecolorat($image, $x, $y);
			$colors = imagecolorsforindex($image, $pixel);
			//$pixels[] = $colors;
			$average[0] += $colors["red"];
			$average[1] += $colors ["green"];
			$average[2] += $colors["blue"];
		}
	}
	//$pixels= array_map("helper42", $pixels); 
	$average[0] /= ($size*$size);
	$average[1] /= ($size*$size);
	$average[2] /= ($size*$size);
	//return array(0=>implode(",",$pixels), 1=>implode(",",$average));
	return implode(",",$average);
}
function importImageAsCannon($src_image, $db, $blockSize=15){
	// Get dimensions of existing image
    $image = getimagesize($src_image);
 
    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ){
		echo "invalid dimensions";
		return false;
	}
    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
 
    // Import image
    switch( $image['format'] ) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
        break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
        break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
        break;
        default:
			echo "Unsupported format";
            // Unsupported format
            return false;
        break;
    }
 
    // Verify import
    if( $image_data == false ){
		echo "file error";
		return false;
	}
	
	$cannonData = array();
	for($x=0; $x<((int)($image[0]/$blockSize))*($blockSize); $x+=$blockSize){
		for($y=0; $y<((int)($image[1]/$blockSize))*($blockSize); $y+=$blockSize){
			//echo $x." ".$y."\n";
			//$cannonData[] = getAverages ($image_data, $blockSize, $x, $y);
			//$cannonData[(int)$x/$blockSize][(int)$y/$blockSize] = implode(";", getAverages ($image_data, $blockSize, $x, $y));
			$cannonData[(int)$x/$blockSize][(int)$y/$blockSize] = getAverages ($image_data, $blockSize, $x, $y);
		}
	}
	return ((implodeMulti("#","@",$cannonData)));
	//var_dump ($cannonData);
	//return $cannonData;
}

//Adapted from http://abeautifulsite.net/blog/2009/08/cropping-an-image-to-make-square-thumbnails-in-php/
function importImageAsFodder($src_image, $database, $userID, $thumb_size = 15, $jpg_quality = 100) {
	if (!$database) {
		echo('No database!'.mysql_error());
		return false;
	}
	if (!mysql_select_db('collageify',$database)){
		echo("Can't select db".mysql_error());
		return false;
	}
    // Get dimensions of existing image
    $image = getimagesize($src_image);
 
    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ){
		echo "invalid dimensions";
		return false;
	}
    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
 
    // Import image
    switch( $image['format'] ) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
        break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
        break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
        break;
        default:
			echo "Unsupported format";
            // Unsupported format
            return false;
        break;
    }
 
    // Verify import
    if( $image_data == false ){
		echo "file error";
		return false;
	}
    // Calculate measurements
    if( $image[0] > $image[1] ) {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }
 
    // Resize and crop
    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    if( imagecopyresampled(
        $canvas,
        $image_data,
        0,
        0,
        $x_offset,
        $y_offset,
        $thumb_size,
        $thumb_size,
        $square_size,
        $square_size
    )) {
        // Create thumbnail
        /*switch( strtolower(preg_replace('/^.*\./', '', $dest_image)) ) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
            break;
            case 'png':
                return imagepng($canvas, $dest_image);
            break;
            case 'gif':
                return imagegif($canvas, $dest_image);
            break;
            default:
                // Unsupported format
                return false;
            break;
        }*/
		//$results = getAverages ($canvas, $thumb_size);
		//$pixels = $results[0];
		$average = array_map("unpackHelper3",explode(",",getAverages ($canvas, $thumb_size)));
		if (!mysql_query("INSERT INTO thumbs VALUES ('','$userID','',".$average[0].",".$average[1].",".$average[2].")", $database)){
			echo "Bad query: .".mysql_error();
			return false;
		}else {
			imagejpeg($canvas, mysql_insert_id($database).".jpg");
			return mysql_insert_id($database);
		}
    } else {
		echo "resize error";
        return false;
    } 
}
function unpackHelper0($data){
	return SplFixedArray::fromArray(array_map("unpackHelper2", $data));
}
function unpackHelper1($data)
{
    return SplFixedArray::fromArray (array_map("unpackHelper2", explode (";", $data)));
}
function unpackHelper2($data)
{
//var_dump ($data);
	return SplFixedArray::fromArray(array_map("unpackHelper3",explode (",", $data)));
}
function unpackHelper3($data){
	return intval ($data);
}
function unpackFramework ($data){
	return SplFixedArray::fromArray(array_map("unpackHelper0", explodeMulti("#","@", ($data))));
}
function colorDistance ($trgb, $r,$g,$b){
	//echo sqrt(pow($trgb[0]-$r,2)+pow($trgb[1]-$g,2)+pow($trgb[2]-$b,2))." ";
	return sqrt(pow($trgb[0]-$r,2)+pow($trgb[1]-$g,2)+pow($trgb[2]-$b,2));
}
function colorDistance2 ($tr,$tg,$tb, $r,$g,$b){
	return sqrt(pow($tr-$r,2)+pow($tg-$g)+pow($tb-$b));
}
function shade ($rgb){
	return $rgb[0]+$rgb[1]+$rgb[2];
}
function shade2 ($r,$g,$b){
	return $r+$g+$b;
}

function createCollage ($collageId, $database, $reuse = true, $reduceSteps = 1){
	$randomizeSteps = 1000;
	$tolerance1 = 1;
	$tolerance2 = 25;
	$outputMatrix = array();
	if (!$database) {
		echo('No database!'.mysql_error());
		return false;
	}
	if (!mysql_select_db('collageify',$database)){
		echo("Can't select db".mysql_error());
		return false;
	}
	
	$result=mysql_query("SELECT * FROM collages WHERE id='$collageId'");
	$row=mysql_fetch_array($result);
	$fr = fopen('cannon'.$collageId.'.txt', "r");
	$fData = fread($fr, filesize('cannon'.$collageId.'.txt'));
	fclose($fr);
	$framework = unpackFramework ($fData);
	$shadeList = array();
	for ($r=0; $r<count($framework); $r++){
		for ($c=0; $c<count($framework[0]); $c++){
			$shadeList[$r.",".$c]=shade($framework[$r][$c][1]);
		}
	}
	asort ($shadeList);
	//echo count($shadeList);
	$shadeList = array_keys ($shadeList);
	$fodderList = explode(",",$row['imagelist']);
	if(count($shadeList)>$fodderList){
		$reuse = true;
	}
	$fodderDatas = array();
	foreach($fodderList as $fodderId){
		//echo $fodderId;
		//var_dump (mysql_fetch_array(mysql_query("SELECT * FROM thumbs WHERE id='$fodderId'")));
		$fodderDatas[] = mysql_fetch_array(mysql_query("SELECT * FROM thumbs WHERE id='$fodderId'"));
	}
	//var_dump($fodderDatas);
	//$fodderDatas = array_reverse($fodderDatas);
	$counter = count($shadeList);
	$counter2 = 0;
	//while (true){
	while(($current = array_shift($shadeList))!=NULL){
		$found = false;
		$pos = array_map("unpackHelper3", explode(",", $current));
		//var_dump($pos);
		$outputMatrix[$pos[0]][$pos[1]] = array();
		while (!$found){
			for($i=0; $i<count($fodderDatas); $i++){
				//while (true){
				//var_dump($framework[$pos[0]][$pos[1]]);
				if(colorDistance($framework[$pos[0]][$pos[1]], $fodderDatas[$i]['avgRed'], $fodderDatas[$i]['avgGreen'],$fodderDatas[$i]['avgBlue'])<$tolerance1){
				//echo colorDistance($framework[$pos[0]][$pos[1]], $fodderDatas[$i]['avgRed'], $fodderDatas[$i]['avgGreen'],$fodderDatas[$i]['avgBlue'])." ";
				//echo $tolerance1;
					if(array_push($outputMatrix[$pos[0]][$pos[1]], $fodderDatas[$i])>=$reduceSteps){
						$found = true;
						array_shift($fodderDatas);
						break;
					}
					/*if(!$reuse){
						unset($fodderDatas[$i]);
					}*/
				}else{
					//echo 'still going';
				}
				//$counter2++;
				$tolerance1+=50;
				//}
			}
		}
		$tolerance1 = 50;
		//echo "nothing!";*/
		//array_push($outputMatrix[$pos[0]][$pos[1]], array_shift($fodderDatas));
	}
	//echo $counter2;
	//}
	//var_dump($outputMatrix[12][0][0]["twos"]);
	//echo "left".count($fodderDatas);
	//echo "x".count($framework)."y".count($framework[0])." ";
	//choose best
	for ($r=0; $r<count($framework); $r++){
		for ($c=0; $c<count($framework[0]); $c++){
			//sort here
			//var_dump($outputMatrix[$r][$c]);
			$outputMatrix[$r][$c] = $outputMatrix[$r][$c][0]['id'];
		}
	}
	
	return $outputMatrix;
}

function drawCollage($imageMatrix){
	//var_dump($imageMatrix);
	//echo "x".count($imageMatrix);
	//echo "y".count($imageMatrix[0]);
	$blockSize = 15;
	//$blockSize = 2;
	//echo " :".$blockSize;
	$im = imagecreatetruecolor(count($imageMatrix)*$blockSize,count($imageMatrix[0])*$blockSize);
	imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
	for ($r=0; $r<count($imageMatrix); $r++){
		for ($c=0; $c<count($imageMatrix[0]); $c++){
						//echo $r." ".$c.";";
			$id = $imageMatrix[$r][$c];
			if ($id=="") continue;
			//$src_image = "..\\collagify\\imgdata\\users\\1337\\".$id.".jpg";
			$src_image = "..\\collagify\\".$id.".jpg";
			// Get dimensions of existing image
    $image = getimagesize($src_image);
 
    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ){
		echo "invalid dimensions";
		return false;
	}
    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
 
    // Import image
    switch( $image['format'] ) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
        break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
        break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
        break;
        default:
			echo "Unsupported format";
            // Unsupported format
            return false;
        break;
    }
 
    // Verify import
    if( $image_data == false ){
		echo "file error";
		return false;
	}
    // Calculate measurements
    if( $image[0] > $image[1] ) {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }
 
    // Resize and crop
    //$canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    if( imagecopy(
        $im,
        $image_data,
        $r*$blockSize,
        $c*$blockSize,
        $x_offset,
        $y_offset,
        $blockSize,
        $blockSize
    )) {}else{}
			
			
			//$x=0;
			//$y=0;
			
			//echo count($pixels);
			//var_dump ($pixels);
			/*for($i=0; $i<count($pixels); $i++){
				$rgb = explode("~",$pixels[$i]);
				//echo $rgb." ";
				//var_dump ($pixels[$i]);
				if (count($rgb)<3){
					$red = 0;
					$green = 0;
					$blue = 0;
				}else{
					$red = $rgb[0];
					$green = $rgb[1];
					$blue = $rgb[2];
				}

				//echo "r:".$red."b:".$blue."g:".$green." ";
				//$color = imagecolorallocate($im, $red,$green,$blue);
				//imageline ($im, $r*$blockSize+$x,$c*$blockSize+$y,$r*$blockSize+$x,$c*$blockSize+$y,$color);
				$y++;
				if ($x=$blockSize){
					$y = 0;
					$x++;
				}
			}*/
		}
	}
	return $im;
}
 
?>