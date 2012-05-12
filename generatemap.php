<?php


$previousID = -1;

$arrayOfSearch 	= array();
$temporaryArrayForThisItem = array();

while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

	if ($rowItemsInDB->id != $previousID){
		if ($previousID != -1)
			array_push($arrayOfSearch, $temporaryArrayForThisItem);
		$temporaryArrayForThisItem["id"] 			= $rowItemsInDB->id;
		$temporaryArrayForThisItem["latitude"] 			= $rowItemsInDB->latitude;
		$temporaryArrayForThisItem["longitude"] 		= $rowItemsInDB->longitude;
		$temporaryArrayForThisItem["parish"] 			= $rowItemsInDB->parish;
		$temporaryArrayForThisItem["city"] 			= $rowItemsInDB->city;
		$temporaryArrayForThisItem["current_country"] 		= $rowItemsInDB->current_county;
		$temporaryArrayForThisItem["event_type"] 		= $rowItemsInDB->eventtype_id;
		$temporaryArrayForThisItem["start_date"] 		= $rowItemsInDB->start_date;
		$temporaryArrayForThisItem["short_title"] 		= $rowItemsInDB->short_title;
		$temporaryArrayForThisItem["short_desc"] 		= $rowItemsInDB->short_desc;
		$temporaryArrayForThisItem["eventtype"] 		= $rowItemsInDB->event_type;
		$temporaryArrayForThisItem["eventtypeorig"] 		= $rowItemsInDB->eventtype_id;
		$temporaryArrayForThisItem["type"] 			= $rowItemsInDB->event_type_class;
	}else{
		$temporaryArrayForThisItem["eventtype"] 		.= "_" . $rowItemsInDB->event_type;
		$temporaryArrayForThisItem["eventtypeorig"] 		.= "_" . $rowItemsInDB->eventtype_id;
	}

	$previousID = $rowItemsInDB->id;

}

if ($previousID != -1)
	array_push($arrayOfSearch, $temporaryArrayForThisItem);



echo '<markers>';



foreach($arrayOfSearch as $arrayOfThisItem){

	if ($arrayOfThisItem["latitude"] != "" && $arrayOfThisItem["longitude"] != "")
		if (!isset($enteredLocations[$arrayOfThisItem["latitude"] . "-" . $arrayOfThisItem["longitude"]]))
		{
		
			$enteredLocations[$arrayOfThisItem["latitude"] . "-" . $arrayOfThisItem["longitude"]] = 1;
		
			$locationName = ($arrayOfThisItem["parish"] != "")? $arrayOfThisItem["parish"]. ", ": "";
			$locationName .= ($arrayOfThisItem["city"] != "")? $arrayOfThisItem["city"]. ", ": "";
			$locationName .= ($arrayOfThisItem["current_country"] != "")? $arrayOfThisItem["current_country"]. ", ": "";
			$locationName = ($locationName != "")?  "Location: $locationName  ": "";
			$evenDate = ($arrayOfThisItem["start_date"] != "")?  "Event Date: " . $arrayOfThisItem["start_date"]  : "";

			echo '<marker ';
			echo 'name="' . parseToXML($locationName) . '" ';
			echo 'desc="' . parseToXML($arrayOfThisItem["short_desc"])  .  '" ';
			echo 'eventtype="' . parseToXML($arrayOfThisItem["eventtype"]) .  '" ';
			echo 'eventtypeorig="' . parseToXML($arrayOfThisItem["eventtypeorig"]) .  '" ';
			echo 'eventdate="' . parseToXML($evenDate) .  '" ';
			echo 'lat="' . $arrayOfThisItem["latitude"] . '" ';
			echo 'lng="' . $arrayOfThisItem["longitude"] . '" ';
			echo 'type="' . parseToXML($arrayOfThisItem["type"]) . '" ';
			echo 'sourcetitle="' . parseToXML($arrayOfThisItem["short_title"]) . '" ';
			echo '/>';

		}
}	


echo '</markers>';


function parseToXML($htmlStr) 
{ 
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&#39;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 

	$xmlStr=str_replace("\x94",'&quot;',$xmlStr); 
	$xmlStr=str_replace("\x93",'&quot;',$xmlStr); 
	$xmlStr=str_replace("\x92",'',$xmlStr); 

	return $xmlStr; 
} 


?>
