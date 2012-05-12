<?php

	include "/home/weme-dev/public_html/connectdb.php";
	include "throwingbones.php";

	$resultArray = array();


	$i = 0;
	$j = 0;

	$sizeOfDeck = 0;
	$width = 500;
	$height = 500;
	$cardWidth = 400;
	$cardHeight = 515;

	$hmargin = 80;
	$vmargin = 50;

	while ($rowItemsInDBALL = mysql_fetch_object($resultForSearchALL)){

		$j = $rowItemsInDBALL->id;
		$decRotationPossitionArray[$j] = array();
		echo "item $j \n";

		$sizeOfDeck = generateDeckObject($j, $resultArray, "E");	

		$image = imagecreatetruecolor($width,$height);
		imagealphablending( $image, true );
		imagesavealpha( $image, true );
		$color = ImageColorAllocate($image,255,255,255);
		ImageFilledRectangle($image,0,0,$width, $height,$color);

		for ($i = $sizeOfDeck-1; $i >= 0; $i--){

			$rotationDegree = rand(0 ,90) - 45;
			$xposition = rand(0-$vmargin ,$width-$cardWidth+$vmargin);
			$yposition = rand(0-$hmargin ,$height-$cardHeight+$hmargin);

			$queryToEmptyPositionTable	 = "INSERT INTO cardrotationposition VALUES ('$j', '$i', '$rotationDegree', '$xposition', '$yposition')";

			mysql_query($queryToEmptyPositionTable);

			$value = $resultArray[$i]["File"];
			$src = imagecreatefrompng("newcards/$value.png");

			imagealphablending( $src, true );
			imagesavealpha( $src, true );
			$rotatedSrc = imageRotateBicubic($src, $rotationDegree);
			imagecopy($image, $rotatedSrc, $xposition, $yposition, 0, 0, $cardWidth, $cardHeight);

			$imageResized = imagecreatetruecolor(150, 150);
    			imagecopyresampled($imageResized, $image, 0, 0, 0, 0, 150, 150, $width, $height);
			imagedestroy($src);
		}

		ImagePNG($imageResized, "Decks-a20/$j.png");
		imagedestroy($image);

	}

?>
