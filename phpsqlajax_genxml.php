<?php

	require_once('getfiltercontenthelper.php');


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
SELECT data_location.latitude,data_location.longitude,data_location.parish,data_location.city,data_location.current_county,data_eventtype.event_type,Eventtype.eventtype_id,
data_eventassertion.start_date,data_eventassertion.short_desc,data_eventassertion.in_source_id,data_eventassertion.id,data_source.short_title,data_eventtype.event_type_class
FROM data_eventassertion,data_location,data_eventtype,Eventtype,data_source
WHERE (data_eventassertion.location_id = data_location.id)
AND (data_eventassertion.in_source_id = data_source.id)
AND (data_eventassertion.id = Eventtype.eventassertion_id)
AND (data_eventtype.id = Eventtype.eventtype_id)
AND ($filteredEventwhereclause)
ORDER BY  `data_eventassertion`.`id` ASC
			";

$resultForSearch = mysql_query($query);


	require_once('generatemap.php');
?>
