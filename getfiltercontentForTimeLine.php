<?php

	require_once('getfiltercontenthelper.php');

	$whereClause = "";
	for($i = 0; $i < count($assertionResultArrayUnique); $i++){
		if ($assertionResultArrayUnique[$i] != ""){
			if ($whereClause != "") $whereClause .= " OR ";
			$whereClause .= "(id = $assertionResultArrayUnique[$i])";
		}
	}

	if ($whereClause == "") exit(0);

	$query = "SELECT * FROM data_eventassertion WHERE $whereClause ORDER BY data_eventassertion.start_date ASC";

	$resultForSearch = mysql_query($query);

	$bgcolor = "";
	$fontcolor = "";
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thisEventDate = $rowItemsInDB->start_date ; 
		$thisEventOrigText = $rowItemsInDB->orig_text ; 
		$thisEventShortDesc = $rowItemsInDB->short_desc ; 
		$thisEventID = $rowItemsInDB->id ; 

		$bgcolor= ( $bgcolor =="")? "bgcolor=#7F7F7F": "";

		$fontcolor = ( $fontcolor =="")? "COLOR=#fff": "";

		$shortText = truncatetext($rowItemsInDB->short_desc, 110);
		$origText = truncatetext($rowItemsInDB->orig_text, 200);
		$thisEventDate = ($thisEventDate == "")? "": "Date:" . $thisEventDate . "<BR><BR>";

$HTMLBody .= <<<MSGASSERTION
<tr><td $bgcolor><div style=" width: 130px;">
<table border=0 padding=4><tr><td><FONT $fontcolor><B>$thisEventDate</B> <i>$shortText <BR><A HREF="javascript:openDeck($rowItemsInDB->id)">read more...</A></i></FONT></td></tr></table>
</div></td><td $bgcolor>
<table border=0 padding=4><tr><td><div style="width: 130px; overflow: auto"><FONT $fontcolor>$origText</FONT></td></tr></table>
</div></td></tr>
MSGASSERTION;

	}

$HTMLBody = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" . $HTMLBody . "</table>"; 

 echo $HTMLBody;


?>
