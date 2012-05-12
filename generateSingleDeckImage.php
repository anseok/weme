<?php

	if 		(isset($_GET['id'])) 		$thisID = $_GET['id'];
	elseif 	(isset($_POST['id'])) 		$thisID = $_POST['id'];
	else 	$thisID = "-1" ;

	include "/home/weme-dev/public_html/connectdb.php";
	include "throwingbones.php";

	$resultArray = array();
	generateDeckArray($thisID, $resultArray);


	$width = 500;
	$height = 500;
	$cardWidth = 284;
	$cardHeight = 370;
	$image = imagecreatetruecolor($width,$height);

	imagealphablending( $image, true );
	imagesavealpha( $image, true );
	$color = ImageColorAllocate($image,255,255,255);
	ImageFilledRectangle($image,0,0,$width, $height,$color);


	foreach($resultArray as $value){
		$src = imagecreatefrompng("newcards/$value.png");
		imagealphablending( $src, true );
		imagesavealpha( $src, true );
		$rotatedSrc = imageRotateBicubic($src, rand(0 ,40) - 20);
		imagecopy($image, $rotatedSrc, rand(0 ,$width-$cardWidth), rand(0 ,$height-$cardHeight), 0, 0, $cardWidth, $cardHeight);
		imagedestroy($src);
	}

	header("content-type: image/png");
	ImagePNG($image);
	imagedestroy($image);

?>
