<?php

if 		(isset($_GET['myx'])) 	$thisX = $_GET['myx'];
elseif 	(isset($_POST['myx'])) 	$thisX = $_POST['myx'];
else 	$thisX = "1" ;

if 		(isset($_GET['myy'])) 	$thisY = $_GET['myy'];
elseif 	(isset($_POST['myy'])) 	$thisY = $_POST['myy'];
else 	$thisY = "1" ;


$thisX -= 30.5; // reduce this number move it to left
$thisY -= 19.1; // reduce this number move it to up


$filename = 'england.png';

$percent = 0.65*2;


list($widthOrig, $heightOrig) = getimagesize($filename);

$width = $widthOrig * $percent;
$height = $heightOrig * $percent;



$imageResized = imagecreatetruecolor($width, $height);
$imageTmp     = imagecreatefrompng ($filename);
imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);






// Content type
header('Content-type: image/jpeg');

$image_p = imagecreatetruecolor(250, 250);

imagecopy($image_p, $imageResized, 0, 0, 250*$thisX+70, 250*$thisY-155, 250, 250);

//imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Output
imagejpeg($image_p, null, 100);


?>
