<?php

	require_once('getfiltercontenthelper.php');
	include "setting.php";


	$i = 0;
	$filteredEventwhereclause = "";
	for ($i = 0; $i < count($assertionResultArrayUnique); $i++){
		$thisID = $assertionResultArrayUnique[$i];
		if ($thisID != ""){
			if ($filteredEventwhereclause != "") $filteredEventwhereclause .= "OR";
			$filteredEventwhereclause .= "(data_eventassertion.id=". $thisID .")" ;
		}
	}

	$query = "
			SELECT data_eventassertion.id,data_eventassertion.short_desc, Eventtype.eventtype_id FROM data_eventassertion,Eventtype			
			WHERE (($filteredEventwhereclause) 
			AND (data_eventassertion.id <>  '')AND(data_eventassertion.id=Eventtype.eventassertion_id))
			ORDER BY data_eventassertion.id ASC
			";



	$resultevents = mysql_query($query);
	$i = 0;
	$previousID = -1;
	while ($rowItemsInDB = mysql_fetch_object($resultevents)){
		if ($previousID != $rowItemsInDB->id){
			$eventObject[$i] = array( File => $rowItemsInDB->eventtype_id, ID => $rowItemsInDB->id, Title => $rowItemsInDB->short_desc, xPosi => 0, yPosi=> 0);
			$eventObjectIndex[$rowItemsInDB->id] = $i;
			$i++;
			$previousID = $rowItemsInDB->id;
		}
	}

	include "getgraphhelper.php";
?>
