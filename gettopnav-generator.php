<?php

	include "/home/weme-dev/public_html/connectdb.php";

	$numColumnsForEventType = 3;
	$numColumnsForClassification = 3;
	$numColumnsForPreternaturalType = 3;
	$numColumnsForLocation = 3;
	$numColumnsForPerson = 3;
	$numColumnsForPreternatural = 3;
	$numColumnsForPrimaryAuthor = 3;
	$numColumnsForPrimarySource = 1;
	$numColumnsForSecondaryAuthor = 3;
	$numColumnsForSecondarySource = 1;
	$numColumnsForStateRecordsAuthor = 2;
	$numColumnsForStateRecords = 1;


	$queryForSearch	 = "SELECT data_author.* FROM data_author join data_source ON data_author.id = data_source.author_id
				WHERE (data_source.source_item_type = 'p' AND ((last_name<>'')OR(first_name<>''))) ORDER BY data_author.last_name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$firstauthorlist = "<table><tr>";
	$i = 0;
	$echoedAnonym = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thisName = $rowItemsInDB->last_name;
		if (strpos($thisName, "Anonymous") !== false){
			if ($echoedAnonym != 0){
				continue;
			}else{
				$echoedAnonym = 1;
				$firstauthorlist .= "<td $widthString><input type='checkbox' id='firstauthorlistAnonym' onClick='javascript:filterChildCheckBoxChange(\"firstauthorlist\", \"Anonym\")'/>Anonymous</td>"; 
			}
		}else{	
			$firstauthorlist .= "<td $widthString><input type='checkbox' id='firstauthorlist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"firstauthorlist\",  $i)'/>" . $rowItemsInDB->last_name .', ' .$rowItemsInDB->first_name . "</td>"; 
			$i++;
		}

		if ((($i+$echoedAnonym) % $numColumnsForPrimaryAuthor) == 0){
			$firstauthorlist .= "</tr><tr>";
			$widthString = "";
		}
	}
	$firstauthorlist .= "</tr></table>";
	$numOffiltertextfirstauthordiv = $i;

	$queryForSearch	 = "SELECT data_author.* FROM data_author join data_source ON data_author.id = data_source.author_id
				WHERE (data_source.source_item_type = 's' AND ((last_name<>'')OR(first_name<>''))) ORDER BY data_author.last_name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$secondauthorlist = "<table><tr>";
	$i = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$secondauthorlist .= "<td $widthString><input type='checkbox' id='secondauthorlist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"secondauthorlist\",  $i)'/>" . $rowItemsInDB->last_name .', ' .$rowItemsInDB->first_name . "</td>"; 
		$i++;
		if (($i % $numColumnsForSecondaryAuthor) == 0) {
			$secondauthorlist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$secondauthorlist .= "</tr></table>";
	$numOffiltertextsecondauthordiv = $i;

	$queryForSearch	 = "SELECT data_author.* FROM data_author join data_source ON data_author.id = data_source.author_id
				WHERE (data_source.source_item_type = 'r' AND ((last_name<>'')OR(first_name<>''))) ORDER BY data_author.last_name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$thirdauthorlist = "<table><tr>";
	$i = 0;
	$widthString = "width='370px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thirdauthorlist .= "<td $widthString><input type='checkbox' id='thirdauthorlist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"thirdauthorlist\",  $i)'/>" . $rowItemsInDB->last_name .', ' .$rowItemsInDB->first_name . "</td>"; 
		$i++;
		if (($i % $numColumnsForStateRecordsAuthor) == 0) {
			$thirdauthorlist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$thirdauthorlist .= "</tr></table>";
	$numOffiltertextthirdauthordiv = $i;

        $queryForSearch = "SELECT * ,
		CASE WHEN SUBSTRING_INDEX( short_title, ' ', 1 )
		IN (
		'a', 'an', 'the'
		)
		THEN concat( substring( short_title, INSTR( short_title, ' ' ) +1 ) , ', ', substring_index( short_title, ' ', 1 ) )
		ELSE short_title
		END AS short_title_sorted
		FROM data_source
		WHERE (
		short_title <> ''
		AND source_item_type = 'p'
		)
		ORDER BY short_title_sorted";

	$resultForSearch = mysql_query($queryForSearch);
	$firsttitlelist = "<table><tr>";
	$i = 0;
	$widthString = "width='740px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$firsttitlelist .= "<td $widthString><input type='checkbox' id='firsttitleList$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"firsttitleList\",  $i)'/>" . $rowItemsInDB->short_title_sorted . "</td>"; 
		$i++;
		if (($i % $numColumnsForPrimarySource) == 0) {
			$firsttitlelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$firsttitlelist .= "</tr></table>";
	$numOffiltertextfirstlistdiv = $i;


        $queryForSearch = "SELECT * ,
				CASE WHEN SUBSTRING_INDEX( short_title, ' ', 1 )
				IN (
				'a', 'an', 'the'
				)
				THEN concat( substring( short_title, INSTR( short_title, ' ' ) +1 ) , ', ', substring_index( short_title, ' ', 1 ) )
				ELSE short_title
				END AS short_title_sorted
				FROM data_source
				WHERE (
				short_title <> ''
				AND source_item_type = 's'
				)
				ORDER BY short_title_sorted";

	$resultForSearch = mysql_query($queryForSearch);
	$secondtitlelist = "<table><tr>";
	$i = 0;
	$widthString = "width='740px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$secondtitlelist .= "<td $widthString><input type='checkbox' id='secondtitleList$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"secondtitleList\",  $i)'/>" . $rowItemsInDB->short_title_sorted . "</td>"; 
		$i++;
		if (($i % $numColumnsForSecondarySource) == 0) {
			$secondtitlelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$secondtitlelist .= "</tr></table>";
	$numOffiltertextsecondlistdiv = $i;


        $queryForSearch = "SELECT * ,
				CASE WHEN SUBSTRING_INDEX( short_title, ' ', 1 )
				IN (
				'a', 'an', 'the'
				)
				THEN concat( substring( short_title, INSTR( short_title, ' ' ) +1 ) , ', ', substring_index( short_title, ' ', 1 ) )
				ELSE short_title
				END AS short_title_sorted
				FROM data_source
				WHERE (
				short_title <> ''
				AND source_item_type = 'r'
				)
				ORDER BY short_title_sorted";

	$resultForSearch = mysql_query($queryForSearch);
	$thirdtitlelist = "<table><tr>";
	$i = 0;
	$widthString = "width='740px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thirdtitlelist .= "<td $widthString><input type='checkbox' id='thirdtitleList$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"thirdtitleList\",  $i)'/>" . $rowItemsInDB->short_title_sorted . "</td>"; 
		$i++;
		if (($i % $numColumnsForStateRecords) == 0) {
			$thirdtitlelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$thirdtitlelist .= "</tr></table>";
	$numOffiltertextthirdlistdiv = $i;


	$queryForSearch	 = "SELECT * FROM data_person WHERE (preferred_name<>'') ORDER BY preferred_name ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$witchlist = "<table><tr>";
	$i = 0;
	$echoedAnonym = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){


		$thisName =$rowItemsInDB->preferred_name;
		if (strpos($thisName, "Anonymous") !== false){
			if ($echoedAnonym != 0){
				continue;
			}else{
				$echoedAnonym = 1;
				$witchlist .= "<td $widthString><input type='checkbox' id='witchlistAnonym'  onClick='javascript:filterChildCheckBoxChange(\"witchlist\", \"Anonym\")'/>Anonymous</td>"; 
			}
		}else{	
			$witchlist .= "<td $widthString><input type='checkbox' id='witchlist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"witchlist\",  $i)'/>" . $rowItemsInDB->preferred_name . "</td>"; 
			$i++;
		}

		if ((($i+$echoedAnonym) % $numColumnsForPerson) == 0) {
			$witchlist .= "</tr><tr>";
			$widthString = "";
		}
	}
	$witchlist .= "</tr></table>";
	$numOffilterpeoplelistdiv = $i;

	$queryForSearch	 = "SELECT * FROM data_magicalbeing WHERE (name<>'') ORDER BY name ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$mbeinglist = "<table><tr>";
	$i = 0;
	$echoedAnonym = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$thisName = $rowItemsInDB->name;
		if (strpos($thisName, "Anonymous") !== false){
			if ($echoedAnonym != 0){
				continue;
			}else{
				$echoedAnonym = 1;
				$mbeinglist .= "<td $widthString><input type='checkbox' id='mbeinglistAnonym' onClick='javascript:filterChildCheckBoxChange(\"mbeinglist\", \"Anonym\")'/>Anonymous</td>"; 
			}
		}else{	
			$mbeinglist .= "<td $widthString><input type='checkbox' id='mbeinglist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"mbeinglist\",  $i)'/>" . $thisName . "</td>"; 
			$i++;
		}

		if ((($i+$echoedAnonym) % $numColumnsForPreternatural) == 0) {
			$mbeinglist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$mbeinglist .= "</tr></table>";
	$numOffilterpreternaturalnamediv = $i;


	$queryForSearch	 = "SELECT * FROM data_location WHERE (preferred_name<>'') ORDER BY current_county, preferred_name ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$locationlist = "";
	$i = 0;
	$j = 0;
	$widthString = "width='250px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		if ($currentClassification != $rowItemsInDB->current_county){
			$currentClassification = $rowItemsInDB->current_county;
			if ($locationlist != "")  $locationlist .= "</tr></table></fieldset>";
			$locationlist .= "</br><fieldset><legend>" . $rowItemsInDB->current_county . "</legend><table><tr>";
			$j = 0;
		}
		$locationlist .= "<td $widthString><input type='checkbox' id='locationlist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"locationlist\",  $i)'/>" . $rowItemsInDB->preferred_name . "</td>"; 
		$i++;
		$j++;
		if (($j % $numColumnsForLocation) == 0) {
			$locationlist .= "</tr><tr>";
			$widthString = "";
		}
	}

	$locationlist .= "</tr></table></fieldset>";
	$numOffilterlocationdiv = $i;

	$queryForSearch	 = "SELECT data_eventtype.*, data_eventtypeclassification.eventtype_classification FROM data_eventtype, data_eventtypeclassification 
				WHERE data_eventtypeclassification.id = data_eventtype.event_type_class ORDER BY eventtype_classification,event_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$eventTypelist = "";
	$i = 0;
	$j = 0;
	$widthString = "width='250px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		if ($currentClassification != $rowItemsInDB->eventtype_classification){
			$currentClassification = $rowItemsInDB->eventtype_classification;
			if ($eventTypelist != "")  $eventTypelist .= "</tr></table></fieldset>";
			$eventTypelist .= "</br><fieldset><legend>" . $rowItemsInDB->eventtype_classification . "</legend><table><tr>";
			$j = 0;
		}
		$eventTypelist .= "<td $widthString><input type='checkbox' id='eventTypelist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"eventTypelist\", $i)'/>" . $rowItemsInDB->event_type . "</td>"; 
		$i++;
		$j++;
		if (($j % $numColumnsForEventType) == 0) {
			$eventTypelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$eventTypelist .= "</tr></table></fieldset>";
	$numOffiltereventdiv = $i;


	$queryForSearch	 = "SELECT * FROM data_magicalbeingtype ORDER BY magicalbeing_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$preternaturalTypelist = "<table><tr>";
	$i = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preternaturalTypelist .= "<td $widthString><input type='checkbox' id='preternaturalTypelist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"preternaturalTypelist\", $i)'/>" . $rowItemsInDB->magicalbeing_type . "</td>"; 
		$i++;
		if (($i % $numColumnsForPreternaturalType) == 0) {
			$preternaturalTypelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$preternaturalTypelist .= "</tr></table>";
	$numOffilterpreternaturaltypediv = $i;

	$queryForSearch	 = "SELECT * FROM data_persontype ORDER BY person_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$personTypelist = "<table><tr>";
	$i = 0;
	$widthString = "width='250px'";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$personTypelist .= "<td $widthString><input type='checkbox' id='personTypelist$i' value='" . $rowItemsInDB->id . "' onClick='javascript:filterChildCheckBoxChange(\"personTypelist\", $i)'/>" . $rowItemsInDB->person_type . "</td>"; 
		$i++;
		if (($i % $numColumnsForClassification) == 0) {
			$personTypelist .= "</tr><tr>";
			$widthString = "";
		}

	}
	$personTypelist .= "</tr></table>";
	$numOffilterpeopletypediv = $i;




$HTMLBody = <<<MSGGENADVANCE

<div style="display : none;" id='hiddenFilterDIVforNumOfs'>
	numOffiltereventdiv = $numOffiltereventdiv;
	numOffilterpreternaturaltypediv = $numOffilterpreternaturaltypediv;
	numOffilterpeopletypediv = $numOffilterpeopletypediv;
	numOffilterpeoplelistdiv = $numOffilterpeoplelistdiv;
	numOffilterpreternaturalnamediv = $numOffilterpreternaturalnamediv;
	numOffilterlocationdiv = $numOffilterlocationdiv;
	numOffiltertextfirstlistdiv = $numOffiltertextfirstlistdiv;
	numOffiltertextsecondlistdiv = $numOffiltertextsecondlistdiv;
	numOffiltertextthirdlistdiv = $numOffiltertextthirdlistdiv;
	numOffiltertextfirstauthordiv = $numOffiltertextfirstauthordiv;
	numOffiltertextsecondauthordiv = $numOffiltertextsecondauthordiv;
	numOffiltertextthirdauthordiv = $numOffiltertextthirdauthordiv;
</div>

<div class="filterwidthwrepper">
	<div class="filterHeader">
		<H2><i>Advance Search Filters:</i></H2><br>
	</div>

	<div class="filterButtons">
		<input type=BUTTON value="Reset" name="mySubmit" onClick="resetFilter()" style="width: 81px">
		<input type=BUTTON value="Go ..." name="mySubmit" onClick="generateFilterDecks()" style="width: 81px">
	</div>

	<div class="filterNavbarWrapper">
		<ul>
			<li><input type='checkbox' id='filtereventdivChBox' onClick='javascript:filterCheckBoxChange("filtereventdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtereventdiv")'>Events</a></li>
			<li><input type='checkbox' id='filterpeopledivChBox' onClick='javascript:filterCheckBoxChange("filterpeoplediv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filterpeoplediv")'>People</a></li>
			<li><input type='checkbox' id='filterpreternaturaldivChBox' onClick='javascript:filterCheckBoxChange("filterpreternaturaldiv")' style="opacity:1"/>	<a href='javascript:highlightfilterdiv("filterpreternaturaldiv")'>Preternatural</a></li>
			<li><input type='checkbox' id='filterlocationdivChBox' onClick='javascript:filterCheckBoxChange("filterlocationdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filterlocationdiv")'>Location</a></li>
			<li><input type='checkbox' id='filtertextdivChBox' onClick='javascript:filterCheckBoxChange("filtertextdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextdiv")'>Text</a></li>
		</ul>
	</div>

	<div class="filterItemWrapper">
		<div id="filtereventdiv" class="filterItem">$eventTypelist</div>
		<div id="filterpeoplediv" class="filterItem" style="display : none;">
			<input type='checkbox' id='filterpeopletypedivChBox' onClick='javascript:filterCheckBoxChange("filterpeopletypediv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filterpeopletypediv")'>Classification</a> ,
			<input type='checkbox' id='filterpeoplelistdivChBox' onClick='javascript:filterCheckBoxChange("filterpeoplelistdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filterpeoplelistdiv")'>Person</a> ,
			<div id="filterpeopletypediv"	 	class="filterItemLow">$personTypelist</div>
			<div id="filterpeoplelistdiv"		class="filterItemLow" style="display : none;">$witchlist</div>
		</div>
		<div id="filterpreternaturaldiv" class="filterItem" style="display : none;">
			<input type='checkbox' id='filterpreternaturaltypedivChBox' onClick='javascript:filterCheckBoxChange("filterpreternaturaltypediv")' style="opacity:1"/>	<a href='javascript:highlightfilterdiv("filterpreternaturaltypediv")'>Preternatural Type</a> ,
			<input type='checkbox' id='filterpreternaturalnamedivChBox' onClick='javascript:filterCheckBoxChange("filterpreternaturalnamediv")' style="opacity:1"/>	<a href='javascript:highlightfilterdiv("filterpreternaturalnamediv")'>Preternatural</a> ,
			<div id="filterpreternaturaltypediv"	class="filterItemLow">$preternaturalTypelist</div>
			<div id="filterpreternaturalnamediv"	class="filterItemLow" style="display : none;">$mbeinglist</div>
		</div>
		<div id="filterlocationdiv" class="filterItem" style="display : none;">$locationlist</div>
		<div id="filtertextdiv" class="filterItem" style="display : none;">
			<input type='checkbox' id='filtertextfirstauthordivChBox' onClick='javascript:filterCheckBoxChange("filtertextfirstauthordiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextfirstauthordiv")'>Primary Author</a> ,
			<input type='checkbox' id='filtertextfirstlistdivChBox' onClick='javascript:filterCheckBoxChange("filtertextfirstlistdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextfirstlistdiv")'>Primary Source</a> ,
			<input type='checkbox' id='filtertextsecondauthordivChBox' onClick='javascript:filterCheckBoxChange("filtertextsecondauthordiv")' style="opacity:1"/>	<a href='javascript:highlightfilterdiv("filtertextsecondauthordiv")'>Secondary Author</a> ,
			<input type='checkbox' id='filtertextsecondlistdivChBox' onClick='javascript:filterCheckBoxChange("filtertextsecondlistdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextsecondlistdiv")'>Secondary Source</a> ,
			<input type='checkbox' id='filtertextthirdauthordivChBox' onClick='javascript:filterCheckBoxChange("filtertextthirdauthordiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextthirdauthordiv")'>State Records Author</a> ,
			<input type='checkbox' id='filtertextthirdlistdivChBox' onClick='javascript:filterCheckBoxChange("filtertextthirdlistdiv")' style="opacity:1"/>		<a href='javascript:highlightfilterdiv("filtertextthirdlistdiv")'>State Records</a>
			<div id="filtertextfirstauthordiv"		class="filterItemLow">$firstauthorlist</div>
			<div id="filtertextfirstlistdiv"		class="filterItemLow" style="display : none;">$firsttitlelist</div>
			<div id="filtertextsecondauthordiv"		class="filterItemLow" style="display : none;">$secondauthorlist</div>
			<div id="filtertextsecondlistdiv"		class="filterItemLow" style="display : none;">$secondtitlelist</div>
			<div id="filtertextthirdauthordiv"		class="filterItemLow" style="display : none;">$thirdauthorlist</div>
			<div id="filtertextthirdlistdiv"		class="filterItemLow" style="display : none;">$thirdtitlelist</div>
		</div>
	</div>

	<div class="filterCloseButton">
		<A HREF="javascript:hideFilter()"><img width=60 heigth=60 src="http://witching.org/throwing-bones/images/close.png" border="none" /></A>
	</div>

</div>
MSGGENADVANCE;

	echo $HTMLBody;

?>
