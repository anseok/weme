<?php

	include "/home/weme-dev/public_html/connectdb.php";
	include "setting.php";

	if 	(isset($_GET['startYear'])) 		$startYear = $_GET['startYear'];
	elseif 	(isset($_POST['startYear'])) 		$startYear = $_POST['startYear'];
	else 	$startYear = "1550" ;


	if 	(isset($_GET['endYear'])) 		$endYear = $_GET['endYear'];
	elseif 	(isset($_POST['endYear'])) 		$endYear = $_POST['endYear'];
	else 	$endYear = "1570" ;


	$query = "
			SELECT data_eventassertion.id,data_eventassertion.short_desc, Eventtype.eventtype_id FROM data_eventassertion,Eventtype			
			WHERE ((data_eventassertion.start_date >=  '$startYear') AND (data_eventassertion.start_date <=  '$endYear') 
			AND (data_eventassertion.id <>  '')AND(data_eventassertion.id=Eventtype.eventassertion_id))
			ORDER BY data_eventassertion.id ASC
			";


	$resultevents = mysql_query($query);
	$i = 0;
	$previousID = -1;
	while ($rowItemsInDB = mysql_fetch_object($resultevents)){
		if ($previousID != $rowItemsInDB->id){
			$eventObject[$i] = array( 'File' => $rowItemsInDB->eventtype_id, 'ID' => $rowItemsInDB->id, 'Title' => $rowItemsInDB->short_desc, 'xPosi' => 0, 'yPosi'=> 0);
			$eventObjectIndex[$rowItemsInDB->id] = $i;
			$i++;
			$previousID = $rowItemsInDB->id;
		}
	}


	include "getgraphhelper.php";


?>
