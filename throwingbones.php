<?php

include "weme-general.php";

function generatePDFs($PDFArray, $arraySize){

	$i = 0;
	$j = -1;
	$resultArray = array();
	$HTMLBody = "<div id=\"PDFcontainer\"> <table border=0><tr>";

	for($i = 0; $i < $arraySize; $i++){
		$j++;
		if ($j == 4) {
			$j = 0;
			$HTMLBody .= "</tr><tr>";		
		}
	$AuthorTitle = $PDFArray[$i]["AuthorTitle"];
	$PDFText = $PDFArray[$i]["Text"];
	$PDFfilename = substr($PDFArray[$i]["File"], 0, strlen($PDFArray[$i]["File"])-3) . "pdf";
	$JPGfilename = substr($PDFArray[$i]["File"], 0, strlen($PDFArray[$i]["File"])-3) . "jpg";
$HTMLBody .= <<<MSGPDFDECK
		<td>
			<a href="http://weme-dev.uszkalo.com/pdfswithsources/$PDFfilename" >
				<img src="http://weme-dev.uszkalo.com/pdf2jpg/$JPGfilename"/>	
			</a>
		</td>
		<td width=150>
			$AuthorTitle <i>$PDFText</i>
		</td>
MSGPDFDECK;

	}

	$HTMLBody .= "</tr></table></div>";
	echo $HTMLBody;

}


function generateDecks($assertionArray, $arraySize){

	$itemCounter = -1;
	$i = 0;
	$j = -1;
	$resultArray = array();
	$HTMLBody = "";

	for($i = 0; $i < $arraySize; $i++){
		if ($assertionArray[$i]>0){
			$j++;
			$itemCounter++;
			generateDeckObject($assertionArray[$i], $resultArray, "E");

			$queryPosition = "SELECT * FROM cardrotationposition WHERE assertionid =$assertionArray[$i] ORDER BY cardid ASC";
			$resultForPosition = mysql_query($queryPosition);
				
			$mapArea = "";
			$l = 0;
			while ($rowPositionInDB = mysql_fetch_object($resultForPosition)){
				
				$rotation = - $rowPositionInDB->degree;
				$xpos = $rowPositionInDB->xpos;
				$ypos = $rowPositionInDB->ypos;
				$cardid = $rowPositionInDB->cardid;
				$assertionid = $rowPositionInDB->assertionid;

				$Ax = ceil (((-113) * cos(deg2rad($rotation)) - (132) * sin(deg2rad($rotation)))) + 200 + $xpos;
				$Ay = - ceil (((-113) * sin(deg2rad($rotation)) + (132) * cos(deg2rad($rotation)))) + 257 + $ypos;

				$Bx = ceil (((100) * cos(deg2rad($rotation)) - (132) * sin(deg2rad($rotation)))) + 200 + $xpos;
				$By = - ceil (((100) * sin(deg2rad($rotation)) + (132) * cos(deg2rad($rotation))))+ 257 + $ypos;

				$Cx = ceil (((100) * cos(deg2rad($rotation)) - (-143) * sin(deg2rad($rotation)))) + 200 + $xpos;
				$Cy = - ceil (((100) * sin(deg2rad($rotation)) + (-143) * cos(deg2rad($rotation))))+ 257 + $ypos;

				$Dx = ceil (((-113) * cos(deg2rad($rotation)) - (-143) * sin(deg2rad($rotation)))) + 200 + $xpos;
				$Dy = - ceil (((-113) * sin(deg2rad($rotation)) + (-143) * cos(deg2rad($rotation))))+ 257 + $ypos;

				$Ax *= 150/500;
				$Ay *= 150/500;
				$Bx *= 150/500;
				$By *= 150/500;
				$Cx *= 150/500;
				$Cy *= 150/500;
				$Dx *= 150/500;
				$Dy *= 150/500;

				$myToolTipY = 430 + (floor($itemCounter / 5))*150;
				$myToolTipX = 150 + (floor($j % 5))*150;

				$altText = str_replace('"', "&quot;", $resultArray[$l]["HTMLText"]) ;
				$fileToBeDisplayed = $resultArray[$l]['File'] . "_" . $resultArray[$l]['File2']. "_" . $resultArray[$l]['File3'];
				$fileToBeDisplayed .= '_' . $resultArray[$l]['cardType'] . "_" . $resultArray[$l]['cardType2']. "_" . $resultArray[$l]['cardType3'];


$mapArea .= <<<MSGMAPAREA
<area id="mapArea$assertionid-$fileToBeDisplayed"  class="jTip" alt="$altText" onclick="openDeck($assertionArray[$i])" shape="poly" coords="$Ax,$Ay,$Bx,$By,$Cx,$Cy,$Dx,$Dy"/>
MSGMAPAREA;

				$l++;
			}


			if ($j == 5) {
				$j = 0;
				$HTMLBody .= "</tr><tr>";
			}
//onmouseout="document.onmousemove = f_sliderMouseMove;"
$HTMLBody .= <<<MSGDECK
	<td>
		<map name="navmap$i" id="navmap$i">
			$mapArea
		</map>
		<div id="mapArea$assertionid">
			<a href="javascript:openDeck($assertionArray[$i]);" >
				<img id="mapImage$assertionid" usemap="#navmap$i" textToBeDisplayed="$htmlText"  src="../throwing-bones-contents/Decks-a20/$assertionArray[$i].jpg" alt="Assertion Deck" class="deck"/>
			</a>
		</div>
	</td>
MSGDECK;
		}
	}
	$itemCounterWithPadding = str_pad($itemCounter, 10);
	$HTMLBody = "$itemCounterWithPadding<div id=\"container\"> <table border=0><tr>" . $HTMLBody . "</table></div>";
	echo $HTMLBody;

}

function generateDeckObject($thisid, &$resultObject, $thisCatType){ 
	$i = 0;
	if (strtolower($thisCatType) == "e"){
		$queryForSearch	 = "SELECT * FROM data_eventassertion WHERE `id` =". $thisid;
		$resultForSearch = mysql_query($queryForSearch);

		if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

			$resultObject[$i] = array('Type' => "events", 'ID' => $thisid, 'HTMLText' => "<B>" . $rowItemsInDB->short_desc . "</B>");

			$queryForEventTypeSearch	= "SELECT data_eventtype.id,data_eventtype.event_type FROM Eventtype,data_eventtype WHERE (data_eventtype.id=Eventtype.eventtype_id)AND(Eventtype.eventassertion_id =$thisid)";
			$resultForEventTypeSearch 	= mysql_query($queryForEventTypeSearch);
			$eventTypeCounter		= 1;
			while($rowItemsEventTypeInDB 	= mysql_fetch_object($resultForEventTypeSearch)){
				$resultObject[$i]['File' . (($eventTypeCounter==1)?"":$eventTypeCounter)] = $rowItemsEventTypeInDB->id;
				$resultObject[$i]['cardType' . (($eventTypeCounter==1)?"":$eventTypeCounter)] = "Event Type $eventTypeCounter<BR/><B>" . $rowItemsEventTypeInDB->event_type. "</B>";
				$eventTypeCounter++;
			}
			$i++;

			$thisLawid = $rowItemsInDB->law_id;
			$querySource = "SELECT data_law.shorttitle FROM data_law WHERE data_law.id =". $thisLawid;
			$resultForSource = mysql_query($querySource);
			if ($rowSearchInDB = mysql_fetch_object($resultForSource)){
				$resultObject[$i++] = array('File' => "l" . $rowItemsInDB->law_id, 'Type' => "laws", 'ID' => $rowItemsInDB->law_id, 'HTMLText' => "<i>Law</i>: " . $rowSearchInDB->shorttitle );
			}

			if ($rowItemsInDB->location_id != ""){
				$queryForSearchLocation	 = "SELECT data_location.id,data_location.preferred_name FROM data_location WHERE `id` =". $rowItemsInDB->location_id;
				$resultForLocationSearch = mysql_query($queryForSearchLocation);
				$rowLocationItemsInDB = mysql_fetch_object($resultForLocationSearch);
				$resultObject[$i++] = array( 'File' => "LB", 'Type' => "locations", 'ID' => $rowItemsInDB->location_id , 'HTMLText' => "<i>Location</i>: " .  $rowLocationItemsInDB->preferred_name);
			}

			$thisAuthorid = $rowItemsInDB->in_source_id;
			$querySource = "SELECT data_source.id,data_source.short_title FROM data_source LEFT JOIN data_author ON data_source.author_id = data_author.id WHERE data_source.id =". $thisAuthorid;
			$resultForSource = mysql_query($querySource);

			if ($rowSearchInDB = mysql_fetch_object($resultForSource)){

				$resultObject[$i++] = array( 'File' => "SC", 'Type' => "text", 'ID' => $rowSearchInDB->id , 'HTMLText' => "<i>Source</i>: " .  $rowSearchInDB->short_title);
			}
		}

		$queryForMagicalSearch	 =  "	SELECT data_magicalbeing.id,data_magicalbeing.name, data_magicalbeingtype.magicalbeing_type,Magicalbeingtype.magicalbeingtype_id
						FROM data_magicalbeing,Intervenes,Magicalbeingtype,data_magicalbeingtype
						WHERE((Intervenes.eventassertion_id = $thisid)AND(data_magicalbeing.id = Intervenes.magicalbeing_id) AND(Magicalbeingtype.magicalbeing_id=data_magicalbeing.id)
						AND (Magicalbeingtype.magicalbeingtype_id=data_magicalbeingtype.id))
						ORDER BY data_magicalbeing.id ASC
						";

		$previousMagicalBeingID = -1;
		$resultForMagicalSearch = mysql_query($queryForMagicalSearch);
		if (mysql_num_rows($resultForMagicalSearch) != 0){
			while ($rowMagicItemsInDB = mysql_fetch_object($resultForMagicalSearch)){
				if ($previousMagicalBeingID !=  $rowMagicItemsInDB->id){
					$resultObject[$i++] = array( 'File' => "d" . $rowMagicItemsInDB->magicalbeingtype_id
						, 'Type' => "magical", 'ID' => $rowMagicItemsInDB->id 
						, 'cardType' => "Preternatural Type 1<BR/><B>" . $rowMagicItemsInDB->magicalbeing_type . "</B>"
						, 'HTMLText' => "<i>Preternatural</i>: " .  $rowMagicItemsInDB->name);
					$previousMagicalBeingID = $rowMagicItemsInDB->id;
					$magBeingTypeCounter = 1;
				}else{
					$magBeingTypeCounter++;
					$resultObject[$i-1]['File'. $magBeingTypeCounter] 	= "d" . $rowMagicItemsInDB->magicalbeingtype_id;
					$resultObject[$i-1]['cardType'. $magBeingTypeCounter] 	= "Preternatural Type $magBeingTypeCounter<BR/><B>" . $rowMagicItemsInDB->magicalbeingtype_id. "</B>";
				}
			}
		}

		$queryForPeopleSearch	 =  "	SELECT data_person.id, data_person.preferred_name, data_persontype.person_type, Persontype.persontype_id
						FROM data_person, Participates, data_personassertion, Persontype, data_persontype WHERE ((Participates.eventassertion_id =$thisid)AND (data_person.id = Participates.person_id)
						AND (data_personassertion.refers_to_id = data_person.id)AND (data_personassertion.id = Persontype.personassertion_id)AND (Persontype.persontype_id = data_persontype.id))
						ORDER BY data_person.id ASC
						";

		$resultForPeopleSearch = mysql_query($queryForPeopleSearch);
		$previousPersonID = -1;
		if (mysql_num_rows($resultForPeopleSearch) != 0){
			while ($rowPeopleItemsInDB = mysql_fetch_object($resultForPeopleSearch)){
				if ($previousPersonID !=  $rowPeopleItemsInDB->id){
					$resultObject[$i++] = array('Type' => "people", 'ID' => $rowPeopleItemsInDB->id 
						, 'File' => "p" . $rowPeopleItemsInDB->persontype_id
						, 'cardType' => "Classification 1<BR/><B>" . $rowPeopleItemsInDB->person_type . "</B>"
						, 'HTMLText' => "<i>Person</i>: " .  $rowPeopleItemsInDB->preferred_name);

					$previousPersonID = $rowPeopleItemsInDB->id;
					$personTypeCounter = 1;
				}else{
					$personTypeCounter++;
					$resultObject[$i-1]['File'. $personTypeCounter] 	= "p" . $rowPeopleItemsInDB->persontype_id;
					$resultObject[$i-1]['cardType'. $personTypeCounter] 	= "Classification $personTypeCounter<BR/><B>" . $rowPeopleItemsInDB->person_type . "</B>";
				}
			}
		}else{
			$queryForPeopleSearch	 =  "	SELECT data_person.id, data_person.preferred_name
							FROM data_person, Participates WHERE ((Participates.eventassertion_id =$thisid)AND (data_person.id = Participates.person_id))
							ORDER BY data_person.id ASC
							";
			$resultForPeopleSearch 	= mysql_query($queryForPeopleSearch);
			$rowPeopleItemsInDB 	= mysql_fetch_object($resultForPeopleSearch);
			$resultObject[$i++] 	= array( 'File' => "p4", 'Type' => "people", 'ID' => $rowPeopleItemsInDB->id , 'HTMLText' => "<i>Person</i>: " .  $rowPeopleItemsInDB->preferred_name);
		}
	}else{

		if($thisCatType == "p"){
			$queryForSearch	 =  "	SELECT data_eventassertion.* FROM data_eventassertion RIGHT JOIN Participates ON 
						data_eventassertion.id = Participates.eventassertion_id WHERE (Participates.person_id = $thisid) 
						ORDER BY \"data_eventassertion.start_date\" ASC";
		}else{
			$queryForSearch	 =  "SELECT data_eventassertion.* FROM data_eventassertion LEFT JOIN Intervenes 
						ON data_eventassertion.id = Intervenes.eventassertion_id WHERE (Intervenes.magicalbeing_id = $thisid) 
						ORDER BY \"data_eventassertion.start_date\" ASC";
		}

		$locationArray	 = array();
		$sourceArray	 = array();

		$resultForSearch = mysql_query($queryForSearch);
	
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

			$resultObject[$i] 		= array( 'Type' => "events", 'ID' => $rowItemsInDB->id, 'HTMLText' => "<B>" . $rowItemsInDB->short_desc . "</B>");

			$queryForEventTypeSearch	= "SELECT data_eventtype.id,data_eventtype.event_type FROM Eventtype,data_eventtype WHERE (data_eventtype.id=Eventtype.eventtype_id)AND(Eventtype.eventassertion_id =$rowItemsInDB->id)";
			$resultForEventTypeSearch 	= mysql_query($queryForEventTypeSearch);
			$eventTypeCounter		= 1;
			while($rowItemsEventTypeInDB 	= mysql_fetch_object($resultForEventTypeSearch)){
				$resultObject[$i]['File' . (($eventTypeCounter==1)?"":$eventTypeCounter)] = $rowItemsEventTypeInDB->id;
				$resultObject[$i]['cardType' . (($eventTypeCounter==1)?"":$eventTypeCounter)] = "Event Type $eventTypeCounter<BR/><B>" . $rowItemsEventTypeInDB->event_type. "</B>";
			}

			$i++;

			if ($rowItemsInDB->location_id != ""){
				if (!isset($locationArray[$rowItemsInDB->location_id])){
					$queryForSearchLocation	 = "SELECT * FROM data_location WHERE `id` =". $rowItemsInDB->location_id;
					$resultForLocationSearch = mysql_query($queryForSearchLocation);
					$rowLocationItemsInDB = mysql_fetch_object($resultForLocationSearch);
					$resultObject[$i++] = array( 'File' => "LB", 'Type' => "locations", 'ID' => $rowItemsInDB->location_id , 'HTMLText' => "<i>Location</i>: " .  $rowLocationItemsInDB->preferred_name);
					$locationArray[$rowItemsInDB->location_id] = 1;
				}
			}

			if (!isset($locationArray[$rowItemsInDB->in_source_id])){
				$thisAuthorid = $rowItemsInDB->in_source_id;
				$querySource = "SELECT data_source . * , data_author.first_name, data_author.last_name FROM data_source
									LEFT JOIN data_author ON data_source.author_id = data_author.id WHERE data_source.id =". $thisAuthorid;
				$resultForSource = mysql_query($querySource);
				if ($rowSearchInDB = mysql_fetch_object($resultForSource)){
					$resultObject[$i++] = array( 'File' => "SC", 'Type' => "text", 'ID' => $rowSearchInDB->id , 'HTMLText' => "<i>Source</i>: " .  $rowSearchInDB->short_title);
				}
				$locationArray[$rowItemsInDB->in_source_id] = 1;
			}
		}
	}
	return $i;
}

function imageRotateBicubic($src_img, $angle, $bicubic=false) {
   
	// convert degrees to radians
	$angle = $angle + 180;
	$angle = deg2rad($angle);
   
	$src_x = imagesx($src_img);
	$src_y = imagesy($src_img);
   
	$center_x = floor($src_x/2);
	$center_y = floor($src_y/2);
   
	$rotate = imagecreatetruecolor($src_x, $src_y);
	imagealphablending($rotate, false);
	imagesavealpha($rotate, true);

	$cosangle = cos($angle);
	$sinangle = sin($angle);
   
	for ($y = 0; $y < $src_y; $y++) {
	  for ($x = 0; $x < $src_x; $x++) {
	// rotate...
	$old_x = (($center_x-$x) * $cosangle + ($center_y-$y) * $sinangle)
	  + $center_x;
	$old_y = (($center_y-$y) * $cosangle - ($center_x-$x) * $sinangle)
	  + $center_y;
   
	if ( $old_x >= 0 && $old_x < $src_x
		 && $old_y >= 0 && $old_y < $src_y ) {
	  if ($bicubic == true) {
		$sY  = $old_y + 1;
		$siY  = $old_y;
		$siY2 = $old_y - 1;
		$sX  = $old_x + 1;
		$siX  = $old_x;
		$siX2 = $old_x - 1;
	   
		$c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
		$c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
		$c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
		$c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));
	   
		$r = ($c1['red']  + $c2['red']  + $c3['red']  + $c4['red']  ) << 14;
		$g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) << 6;
		$b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >> 2;
		$a = ($c1['alpha']  + $c2['alpha']  + $c3['alpha']  + $c4['alpha'] ) >> 2;
		$color = imagecolorallocatealpha($src_img, $r,$g,$b,$a);
	  } else {
		$color = imagecolorat($src_img, $old_x, $old_y);
	  }
	} else {
		  // this line sets the background colour
	  $color = imagecolorallocatealpha($src_img, 255, 255, 255, 127);
	}
	imagesetpixel($rotate, $x, $y, $color);
	  }
	}
	return $rotate;
}

?>
