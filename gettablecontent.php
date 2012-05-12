<?php

	if 	(isset($_GET['id'])) 		$startYear = $_GET['id'];
	elseif 	(isset($_POST['id'])) 		$startYear = $_POST['id'];
	else 	$startYear = "1" ;

	if 	(isset($_GET['endYear'])) 		$endYear = $_GET['endYear'];
	elseif 	(isset($_POST['endYear'])) 		$endYear = $_POST['endYear'];
	else 	$endYear = "1" ;


	include "/home/weme-dev/public_html/connectdb.php";
	include "throwingbones.php";

	$assertionArray = array();

	$HTMLBody = "";
	$echoMe = 0;
	$i = 0;

	$query = "SELECT data_eventassertion.* FROM data_eventassertion
		WHERE ((data_eventassertion.start_date >=  '$startYear') AND 
		(data_eventassertion.start_date <=  '$endYear') AND (data_eventassertion.id <>  ''))
		ORDER BY data_eventassertion.start_date ASC";


	$resultForSearch = mysql_query($query);
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$assertionArray[$i++] = $rowItemsInDB->id ; 

 	generateDecks($assertionArray, $i);

?>
