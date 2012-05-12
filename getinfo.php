<?php


	include "/home/weme-dev/public_html/connectdb.php";
	include "weme-general.php";

	if 	(isset($_GET['id'])) 		$myidlist = $_GET['id'];
	elseif 	(isset($_POST['id'])) 		$myidlist = $_POST['id'];
	else 	$myidlist = "e1" ;

	$myidArray = split("_" ,$myidlist);
	$finalHTMLToBeEchoed = "";
	foreach($myidArray as $myid){
		$thistype = substr($myid, 0, 1);
		$thisid = substr($myid, 1);

		$finalHTML = "testing_testing";

		if ($thistype == 'e'){
			$query = "
					SELECT data_eventassertion.short_desc, data_eventtype.event_type FROM data_eventassertion,Eventtype,data_eventtype		
					WHERE ((data_eventassertion.id=$thisid)AND(data_eventassertion.id=Eventtype.eventassertion_id)AND(Eventtype.eventtype_id=data_eventtype.id))
					ORDER BY Eventtype.id ASC
					";

			$resultevents = mysql_query($query);
			$thisEventTypes = "";
			while($rowItemsInDB = mysql_fetch_object($resultevents)){
				$thisEventTypes .= ($thisEventTypes == "")? $rowItemsInDB->event_type : ", " . $rowItemsInDB->event_type;
				$thisEventDesc = $rowItemsInDB->short_desc;
			}
			$finalHTML = 	"Event_<B>Event Type</B>: " . $thisEventTypes .
						"<BR><B>Event Description</B>: " . $thisEventDesc;
		}

		elseif ($thistype == 'd'){
			$query = "SELECT * FROM data_magicalbeing WHERE id=$thisid";

			$resultevents = mysql_query($query);
			$rowItemsInDB = mysql_fetch_object($resultevents);

			$finalHTML = 	"Preternatural_<B>Name</B>: " . $rowItemsInDB->name;
		}

		elseif ($thistype == 'p'){
			$query = "SELECT * FROM data_person WHERE id=$thisid";

			$resultevents = mysql_query($query);
			$rowItemsInDB = mysql_fetch_object($resultevents);

			$finalHTML = 	"Person_<B>Name</B>: " . $rowItemsInDB->preferred_name;
		}
		$finalHTMLToBeEchoed .=  $myid . "_" . $finalHTML . "__";
	}

	echo $finalHTMLToBeEchoed;

?>
