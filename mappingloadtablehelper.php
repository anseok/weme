<?php

	$arrayOfSearch 	= array();
	$temporaryArrayForThisItem = array();

	$HTMLBody = "";
	$previousID = -1;
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

		if ($rowItemsInDB->id != $previousID){
			if ($previousID != -1)
				array_push($arrayOfSearch, $temporaryArrayForThisItem);
			$temporaryArrayForThisItem["id"] 			= $rowItemsInDB->id;
			$temporaryArrayForThisItem["start_date"] 		= $rowItemsInDB->start_date;
			$temporaryArrayForThisItem["parish"] 			= $rowItemsInDB->parish;
			$temporaryArrayForThisItem["city"] 			= $rowItemsInDB->city;
			$temporaryArrayForThisItem["old_county"] 		= $rowItemsInDB->old_county;
			$temporaryArrayForThisItem["current_county"] 		= $rowItemsInDB->current_county;
			$temporaryArrayForThisItem["nation"] 			= $rowItemsInDB->nation;
			$temporaryArrayForThisItem["shortdesc"] 		= $rowItemsInDB->short_desc;
			$temporaryArrayForThisItem["firsteventtype"] 		= $rowItemsInDB->eventid;
			$temporaryArrayForThisItem["eventtype"] 		= $rowItemsInDB->event_type;
		}else
			$temporaryArrayForThisItem["eventtype"] 		.= ", " . $rowItemsInDB->event_type;

		$previousID = $rowItemsInDB->id;

	}
	array_push($arrayOfSearch, $temporaryArrayForThisItem);


	foreach($arrayOfSearch as $temporaryArrayOfThisItem){
		$thisID 			= $temporaryArrayOfThisItem["id"];
		$thisparish			= $temporaryArrayOfThisItem["parish"];
		$thisstartdate			= $temporaryArrayOfThisItem["start_date"];
		$thiscity			= $temporaryArrayOfThisItem["city"];
		$thisoldcounty			= $temporaryArrayOfThisItem["old_county"];
		$thisnation			= $temporaryArrayOfThisItem["nation"];
		$thiseventcard			= $temporaryArrayOfThisItem["firsteventtype"];
		$thiseventtype 			= $temporaryArrayOfThisItem["eventtype"];
		$thisshortdesc 			= $temporaryArrayOfThisItem["shortdesc"];

		$echoMe = 1;
$HTMLBody .= <<<MSGBegEventsBody
<tr>
<td><img src="thumbsroot/level10/$thiseventcard.png"/></td>
<td>$thiseventtype</td>
<td><A href="javascript:loadMappingContent('myevent',$thisID)"><font color="#84362B">$thisshortdesc</font></A></td>
<td>$thisstartdate</td>
<td>$thiscity</td>
<td>$thisparish</td>
<td>$thisoldcounty</td>
<td>$thisnation</td>
</tr>
MSGBegEventsBody;





}	


$finalHTMLBody = <<<MSGBegEventsHeader

<div class="demo">
<div id="accordion">
<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active">

<table width="100%" border="1" id="myTable" class="tablesorter">
<thead>
  <tr>
  <th>Card</th>
  <th>Event Type</th>
  <th>Short Description</th>
  <th>Date</th>
  <th>City</th>
  <th>Parish</th>
  <th>Old county</th>
  <th>Nation</th>

</tr>
</thead>
<tbody>

$HTMLBody

</table>
</div>

</div>
</div>

MSGBegEventsHeader;


if ($echoMe == 1)
	echo $finalHTMLBody;



?>
