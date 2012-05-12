<?php

	if 	(isset($_GET['id'])) 		$startYear = $_GET['id'];
	elseif 	(isset($_POST['id'])) 		$startYear = $_POST['id'];
	else 	$startYear = "1560" ;

	if 	(isset($_GET['endYear'])) 		$endYear = $_GET['endYear'];
	elseif 	(isset($_POST['endYear'])) 		$endYear = $_POST['endYear'];
	else 	$endYear = "1570" ;

	include "/home/weme-dev/public_html/connectdb.php";
	include_once "throwingbones.php";

	$HTMLBody = "";
	$echoMe = 0;

	$bgcolor ="";
	$fontcolor = "";

	$query = "SELECT data_eventassertion.start_date,data_eventassertion.orig_text,data_eventassertion.id, data_eventassertion.short_desc FROM data_eventassertion
		WHERE ((data_eventassertion.start_date >=  '$startYear') AND 
		(data_eventassertion.start_date <=  '$endYear') AND (data_eventassertion.id <>  ''))
		ORDER BY data_eventassertion.start_date ASC";

	$resultForSearch = mysql_query($query);
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thisEventDate = $rowItemsInDB->start_date ; 
		$thisEventOrigText = $rowItemsInDB->orig_text ; 
		$thisEventShortDesc = $rowItemsInDB->short_desc ; 
		$thisEventID = $rowItemsInDB->id ; 

		$bgcolor= ( $bgcolor =="")? "bgcolor=#7F7F7F": "";

		$fontcolor = ( $fontcolor =="")? "COLOR=#fff": "";

		$shortText = truncatetext($rowItemsInDB->short_desc, 110);
		$origText = truncatetext($rowItemsInDB->orig_text, 200);
		$thisEventDate = ($thisEventDate == "")? "": "Date: " . $thisEventDate . "<BR><BR>";

$HTMLBody .= <<<MSGASSERTION
<tr><td $bgcolor><div style=" width: 130px;"><table border=0 padding=4><tr><td><FONT $fontcolor><B>$thisEventDate</B><i>$shortText <BR><A HREF="javascript:openDeck($rowItemsInDB->id)">read more...</A></i></FONT></td></tr>
</table></div></td><td $bgcolor><table border=0 padding=4><tr><td><div style="width: 130px; overflow: auto"><FONT $fontcolor>$origText</FONT></td></tr></table></div></td></tr>
MSGASSERTION;

	}

$HTMLBody = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" . $HTMLBody . "</table>"; 

echo $HTMLBody;


?>
