<?php
	include "/home/weme-dev/public_html/connectdb.php";

	$queryForSearch	 = "SELECT data_eventassertion.* FROM data_eventassertion WHERE (data_eventassertion.id <>  '')";


	$eventtime = array();
	$i = 0;
	for($i = 1500; $i < 1701; $i++)
		$eventtime[$i] = 0;

	$resultForSearch = mysql_query($queryForSearch);
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		if(isset($eventtime[$rowItemsInDB->start_date]))
			$eventtime[$rowItemsInDB->start_date]++;
		else
			$eventtime[$rowItemsInDB->start_date]=1;

	$w = 1110;
	$h = 40;
	$img = imagecreatetruecolor($w, $h);

	$BG = imagecolorallocate($img, 222, 226, 228);
	$white = imagecolorallocate($img, 255,   255,   255);
	$grey = imagecolorallocate($img, 128, 128, 128);
	$darkGreen = imagecolorallocate($img, 190, 190, 190);

	ImageFilledRectangle($img,0,0,$w,$h,$BG);

	$sign = 1;
	$ycenter = 30;

	$maxEvents = max($eventtime) * 0.5;

	for($i = 1530; $i < 1690; $i++){

		if ($eventtime[$i] != 0){

			$arcWidth = max(2 , $h*($eventtime[$i] / $maxEvents) / 1.5);
			$arcHeigth = max (2, $h*($eventtime[$i] / $maxEvents) * 2);

			imagefilledarc($img, (1110 / (1690 - 1530)) * ($i- 1530) , ($h / 2) , $arcWidth, $arcHeigth,  0, 360, $white, IMG_ARC_PIE);
		}

	}

	ImageFilledRectangle($img,0,0,$w,1,$darkGreen);
	ImageFilledRectangle($img,0,$h,$w,$h-2,$darkGreen);
	ImageFilledRectangle($img,0,0,1,$h,$darkGreen);
	ImageFilledRectangle($img,$w,0,$w-2,$h,$darkGreen);

	imagepng($img, 'horizontal.png');

	imagedestroy($img);

?>
