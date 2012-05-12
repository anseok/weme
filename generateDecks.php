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

	$queryForSearchALL	 = "SELECT * FROM data_eventassertion ORDER BY id ASC";
	$resultForSearchALL = mysql_query($queryForSearchALL);

	while ($rowItemsInDBALL = mysql_fetch_object($resultForSearchALL)){

		$j = $rowItemsInDBALL->id;
		$decRotationPossitionArray[$j] = array();


		$sizeOfDeck = generateDeckObject($j, $resultArray, "E");	

		$myvar = var_export( $resultArray, TRUE);

		if (file_exists("../throwing-bones-contents/Decks-Hash/$j") && $myvar == file_get_contents("../throwing-bones-contents/Decks-Hash/$j"))
			continue;

		echo "$j \t";

		$myFile = "../throwing-bones-contents/Decks-Hash/$j";
		$fh = fopen($myFile, 'w');
		fwrite($fh, $myvar);
		fclose($fh);

		$queryToEmptyPositionTable	 = "DELETE FROM cardrotationposition WHERE assertionid=$j";
		mysql_query($queryToEmptyPositionTable);


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
			$src = imagecreatefrompng("../throwing-bones-contents/newcards/$value.png");

			imagealphablending( $src, true );
			imagesavealpha( $src, true );
			$rotatedSrc = imageRotateBicubic($src, $rotationDegree);
			imagecopy($image, $rotatedSrc, $xposition, $yposition, 0, 0, $cardWidth, $cardHeight);

			$imageResized = imagecreatetruecolor(150, 150);
    			imagecopyresampled($imageResized, $image, 0, 0, 0, 0, 150, 150, $width, $height);
			imagedestroy($src);
		}

		imagejpeg($imageResized, "../throwing-bones-contents/Decks-a20/$j.jpg");
		imagedestroy($image);

	}

?>
