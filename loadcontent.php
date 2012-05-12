<?php

	include "/home/weme-dev/public_html/connectdb.php";
	include "weme-general.php";

	$HTMLBody = "";

	if 		(isset($_GET['cat'])) 		$thisCat = $_GET['cat'];
	elseif 		(isset($_POST['cat'])) 		$thisCat = $_POST['cat'];
	elseif		(isset($argv[1]))		$thisCat = $argv[1];
	else 		$thisCat = "events" ;

	if 		(isset($_GET['linkID'])) 	$thisid = $_GET['linkID'];
	elseif 		(isset($_POST['linkID'])) 	$thisid = $_POST['linkID'];
	elseif		(isset($argv[2]))		$thisid = $argv[2];
	else 		$thisid = "6" ;

	if 		(isset($_GET['hyperMode'])) 	$hyperMode = $_GET['hyperMode'];
	elseif 		(isset($_POST['hyperMode'])) 	$hyperMode = $_POST['hyperMode'];
	elseif		(isset($argv[3]))		$hyperMode = $argv[3];
	else 		$hyperMode = 1 ;

	if (count($_SERVER['argv']) == 3){
		$thisCat = $_SERVER['argv'][1];
		$thisid = $_SERVER['argv'][2];
		$copyRight = "
<BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/>
<CENTER><B>URL: http://witching.org/throwing-bones#$thisCat-$thisid</B></CENTER>
<BR/><BR/>
<CENTER>
Many of the texts cited are provided courtesy of EEBO. Regarding These Original Texts Excerpts: <BR/>
Keyboarded and encoded full text © 2003-2010 Early English Books Online Text Creation Partnership.  <BR/>
All Rights Reserved. Reproduced with the permission of the Early English Books Online  <BR/>
Text Creation Partnership. Further reproduction is prohibited without permission. <BR/>
</CENTER>";
	}else{

		$copyRight = "
<BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/><BR/>
<CENTER><B>URL: http://witching.org/throwing-bones#$thisCat-$thisid</B></CENTER>
<BR/><BR/>
<CENTER><img src=\"http://witching.org/throwing-bones/copyright.png\" alt=\"
Many of the texts cited are provided courtesy of EEBO. Regarding These Original Texts Excerpts: <BR/>
Keyboarded and encoded full text © 2003-2010 Early English Books Online Text Creation Partnership.  <BR/>
All Rights Reserved. Reproduced with the permission of the Early English Books Online  <BR/>
Text Creation Partnership. Further reproduction is prohibited without permission. <BR/>\" />
</CENTER>";

	}

	switch ($thisCat) {
	    case "people":
			echopeople($thisid,$hyperMode);
	    	break;
	    case "locations":
			echolocations($thisid,$hyperMode);
	    	break;
	    case "events":
			echoevents($thisid,$hyperMode);
	    	break;
	    case "magical":
			echomagical($thisid,$hyperMode);
	    	break;
	    case "text":
			echotext($thisid,$hyperMode);
	    	break;
	    case "law":
			echolaw($thisid,$hyperMode);
	    	break;
	    case "eventforpeople":
			echoeventforpeople($thisid,$hyperMode);
	    	break;
	    case "eventformagical":
			echoeventformagical($thisid,$hyperMode);
	    	break;


	    case "preternaturalFunction":
			echoPreternaturalFunction($thisid,$hyperMode);
	    	break;
	    case "preternaturalFedWith":
			echoPreternaturalFedWith($thisid,$hyperMode);
	    	break;
	    case "preternaturalForm":
			echoPreternaturalForm($thisid,$hyperMode);
	    	break;
	    case "preternaturalMOC":
			echoPreternaturalMOC($thisid,$hyperMode);
	    	break;
	    case "preternaturalType":
			echoPreternaturalType($thisid,$hyperMode);
	    	break;


	    case "personType":
			echoPersonType($thisid,$hyperMode);
	    	break;
	    case "personStatus":
			echoPersonStatus($thisid,$hyperMode);
	    	break;
	    case "personOccup":
			echoPersonOccup($thisid,$hyperMode);
	    	break;

	    case "eventType":
			echoEventType($thisid,$hyperMode);
	    	break;

	    case "year":
			echoYear($thisid,$hyperMode);
	    	break;

	}

	echo $copyRight;

//////////////////////////////////////////////////////////////////////////////////////////////////

function echopeople($thisid,$hyperMode){ 

	$HTMLBody = "";
	$HTMLBody = addHeader($HTMLBody, "Person Information");

	$queryForSearch	 = "SELECT * FROM data_person WHERE `id` =". $thisid;
	$resultForSearch = mysql_query($queryForSearch);

	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

		$HTMLBody 	= ($rowItemsInDB->first_name =="")?		$HTMLBody :	addContent($HTMLBody, "First Name"		, $rowItemsInDB->first_name);
		$HTMLBody 	= ($rowItemsInDB->last_name =="")?		$HTMLBody :	addContent($HTMLBody, "Last Name"		, $rowItemsInDB->last_name);
		$HTMLBody 	= ($rowItemsInDB->preferred_name =="")?		$HTMLBody :	addContent($HTMLBody, "Preferred Name"		, makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode));
		$HTMLBody 	= ($rowItemsInDB->gender =="")?			$HTMLBody :	addContent($HTMLBody, "Gender"			, ($rowItemsInDB->gender == "f")? "Female": "Male");
		addPersonLocationsToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode);
	}


	$queryForSearch	 = "SELECT * FROM data_personassertion WHERE `refers_to_id` =$thisid";
	$resultForSearch = mysql_query($queryForSearch);

	$HTMLBody = addHeader($HTMLBody, "Person Assertions:");
	$assertionCounter = 0;
	while($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$assertionCounter++;
		$HTMLBody 	= addSmallHeader($HTMLBody, "Person Assertion Number " . $assertionCounter. ":");
		$HTMLBody 	= addAssertionPersonToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	}

	$queryForSearch	 =  "SELECT * FROM data_eventassertion RIGHT JOIN Participates ON data_eventassertion.id = Participates.eventassertion_id WHERE (Participates.person_id = $thisid) ORDER BY \"data_eventassertion.start_date\" ASC";
	$resultForSearch = mysql_query($queryForSearch);

	if (mysql_num_rows($resultForSearch) != 0){
		$HTMLBody = addHeader($HTMLBody, "Events Information");
		$eventCounter = 0;
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
			$eventCounter++;

			$HTMLBody 	= addSmallHeader($HTMLBody, "Events Number " . $eventCounter. ":");
			$HTMLBody 	= addAssertionEventToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
			$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);

			$thisEventAssertionID 	= $rowItemsInDB->id;
			$queryForMagicalSearch	=  "SELECT data_magicalbeing.name,data_magicalbeing.id FROM data_magicalbeing LEFT JOIN Intervenes ON data_magicalbeing.id = Intervenes.magicalbeing_id WHERE (Intervenes.eventassertion_id = $thisEventAssertionID) ORDER BY \"data_magicalbeing.name\" ASC";
			$resultForMagicalSearch = mysql_query($queryForMagicalSearch);
			if (mysql_num_rows($resultForMagicalSearch) != 0){
				$HTMLBody 	= addBR($HTMLBody);
				$HTMLBody 	= addSmallHeader($HTMLBody, "Preternatural in This Event:");
				while ($rowMagicItemsInDB = mysql_fetch_object($resultForMagicalSearch)){
					$HTMLBody = addListContent($HTMLBody, makeHyper($rowMagicItemsInDB->name, 'magical',$rowMagicItemsInDB->id,$hyperMode));
				}
			}

			$queryForPeopleSearch	 =  "SELECT data_person.preferred_name,data_person.id FROM data_person LEFT JOIN Participates ON data_person.id = Participates.person_id WHERE (Participates.eventassertion_id = $thisEventAssertionID) ORDER BY \"data_person.preferred_name\" ASC";
			$resultForPeopleSearch = mysql_query($queryForPeopleSearch);
			if (mysql_num_rows($resultForPeopleSearch) != 0){
				$HTMLBody 	= addBR($HTMLBody);
				$HTMLBody 	= addSmallerHeader($HTMLBody, "People in This Event:");
				while ($rowPeopleItemsInDB = mysql_fetch_object($resultForPeopleSearch)){
					$HTMLBody	= addListContent($HTMLBody, makeHyper($rowPeopleItemsInDB->preferred_name, 'people',$rowPeopleItemsInDB->id,$hyperMode));
				}
			}
		}
	}
	echo $HTMLBody;
} 

function echolocations($thisid,$hyperMode){ 
	$HTMLBody = "";
	$HTMLBody = addHeader($HTMLBody, "Location Information");

	$queryForSearch	 = "SELECT * FROM data_location WHERE `id` =". $thisid;
	$resultForSearch = mysql_query($queryForSearch);

	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

		$HTMLBody 	= ($rowItemsInDB->preferred_name =="")?		$HTMLBody :	addContent($HTMLBody, "Modernized spelling" 	, $rowItemsInDB->preferred_name );
		$HTMLBody 	= ($rowItemsInDB->parish =="")?			$HTMLBody :	addContent($HTMLBody, "Parish" 			, $rowItemsInDB->parish );
		$HTMLBody 	= ($rowItemsInDB->city =="")?			$HTMLBody :	addContent($HTMLBody, "City/Town" 		, $rowItemsInDB->city );
		$HTMLBody 	= ($rowItemsInDB->old_county =="")?		$HTMLBody :	addContent($HTMLBody, "Pre-1974 County" 	, $rowItemsInDB->old_county );
		$HTMLBody 	= ($rowItemsInDB->current_county =="")?		$HTMLBody :	addContent($HTMLBody, "Present-day County" 	, $rowItemsInDB->current_county );
		$HTMLBody 	= ($rowItemsInDB->nation =="")?			$HTMLBody :	addContent($HTMLBody, "Country" 		, $rowItemsInDB->nation );
		$HTMLBody 	= ($rowItemsInDB->latitude =="")?		$HTMLBody :	addContent($HTMLBody, "Latitude" 		, $rowItemsInDB->latitude );
		$HTMLBody 	= ($rowItemsInDB->longitude =="")?		$HTMLBody :	addContent($HTMLBody, "Longitude" 		, $rowItemsInDB->longitude );
	}



	$queryForSearch	 = "	SELECT DISTINCT data_person.preferred_name, data_person.id 
				FROM data_person, Participates, data_eventassertion
				WHERE (data_person.id = Participates.person_id) AND (Participates.eventassertion_id = data_eventassertion.id) AND (data_eventassertion.location_id =  $thisid)  
				ORDER BY \"data_person.preferred_name\" ASC";	

	$resultForSearch = mysql_query($queryForSearch);

	if (mysql_num_rows($resultForSearch) != 0){

		$HTMLBody 		= addSmallHeader($HTMLBody, "People in This Location:");
		$thisIsFirstPerson	= 1;
		while ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
			if ($thisIsFirstPerson == 1){
				$HTMLBody	.= makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
				$thisIsFirstPerson	= 0;
			}else
				$HTMLBody	.= ", " . makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
		$HTMLBody 		= addBR($HTMLBody);
	}

	$queryForSearch	 = "SELECT * FROM data_eventassertion WHERE `location_id` =$thisid ORDER BY \"data_eventassertion.start_date\" ASC";	
	$resultForSearch = mysql_query($queryForSearch);

	if (mysql_num_rows($resultForSearch) != 0){

		$HTMLBody = addSmallHeader(addBR($HTMLBody), "Events in this Location");
		$eventCounter = 0;
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
			$eventCounter++;
			$HTMLBody 	= addSmallHeader($HTMLBody, "Events Number " . $eventCounter. ":");
			$HTMLBody 	= addAssertionEventToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
			$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
		}
	}

	echo $HTMLBody;
} 



function echolaw($thisid,$hyperMode){ 
	$HTMLBody = "";
	$HTMLBody = addHeader($HTMLBody, "Law Information");

	$queryForSearch	 = "SELECT data_highlightedcrimes.description FROM Lawhighlightedcrimes,data_highlightedcrimes,data_law WHERE (data_law.id=Lawhighlightedcrimes.law_id) AND 
				(Lawhighlightedcrimes.highlightedcrimes_id = data_highlightedcrimes.id) AND data_law.id =". $thisid . " ORDER BY Lawhighlightedcrimes.id"; 

	$resultForSearch = mysql_query($queryForSearch);

	$hilighteCrimes = "";

	while($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$hilighteCrimes 	.= (($hilighteCrimes=="")? "<B>Highlighted Crime(s) : </B><BR><BR>" : "" ) . $rowItemsInDB->description . "<BR><BR>";
	}


	$queryForSearch	 = "SELECT data_highlightedrepercussions.description FROM Lawhighlightedrepercussions,data_highlightedrepercussions,data_law WHERE (data_law.id=Lawhighlightedrepercussions.law_id) AND 
				(Lawhighlightedrepercussions.highlightedrepercussions_id = data_highlightedrepercussions.id) AND data_law.id =". $thisid . " ORDER BY Lawhighlightedrepercussions.id";

	$resultForSearch = mysql_query($queryForSearch);

	$hilighteRep = "";
	while($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$hilighteRep 	.= (($hilighteRep=="")? "<B>Highlighted Punishment(s) : </B><BR><BR>" : "" ) . $rowItemsInDB->description . "<BR><BR>";
	}





	$queryForSearch	 = "SELECT * FROM data_law WHERE `id` =". $thisid;
	$resultForSearch = mysql_query($queryForSearch);

	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

		$HTMLBody 	= ($rowItemsInDB->monarch =="")?		$HTMLBody :	addContent($HTMLBody, "Monarch" 	, $rowItemsInDB->monarch );
		$HTMLBody 	= ($rowItemsInDB->shorttitle =="")?		$HTMLBody :	addContent($HTMLBody, "Short Title" 	, $rowItemsInDB->shorttitle );
		$HTMLBody 	= ($rowItemsInDB->longtitle =="")?		$HTMLBody :	addContent($HTMLBody, "Long Title" 	, $rowItemsInDB->longtitle );
		$HTMLBody 	= ($rowItemsInDB->year =="")?			$HTMLBody :	addContent($HTMLBody, "Year" 		, makeHyper($rowItemsInDB->year, 'year',$rowItemsInDB->year,$hyperMode) );
		$HTMLBody 	.= $hilighteCrimes . $hilighteRep;
		$HTMLBody 	= ($rowItemsInDB->fulltext =="")?		$HTMLBody :	addContent($HTMLBody, "Full Text" 	, $rowItemsInDB->fulltext );
	}

	echo $HTMLBody;
} 



function echoevents($thisid,$hyperMode){ 

	$HTMLBody = "";
	$queryForSearch	 = "SELECT * FROM data_eventassertion WHERE `id` =". $thisid;
	$resultForSearch = mysql_query($queryForSearch);

	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$HTMLBody 	= addHeader($HTMLBody, "Event Information");
		$HTMLBody 	= addAssertionEventToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	}

	$queryForMagicalSearch	 =  "SELECT data_magicalbeing.id,data_magicalbeing.name FROM data_magicalbeing LEFT JOIN Intervenes ON data_magicalbeing.id = Intervenes.magicalbeing_id WHERE (Intervenes.eventassertion_id = $thisid) ORDER BY \"data_magicalbeing.name\" ASC";
	$resultForMagicalSearch = mysql_query($queryForMagicalSearch);
	if (mysql_num_rows($resultForMagicalSearch) != 0){
		$HTMLBody 	= addSmallHeader($HTMLBody, "Preternaturals in This Event:");
		$magCounter = 0;
		while ($rowMagicItemsInDB = mysql_fetch_object($resultForMagicalSearch)){
			$magCounter++;
			$HTMLBody 	= addSmallerHeader($HTMLBody, "Preternatural Number " . $magCounter. ":");
			$HTMLBody 	= ($rowMagicItemsInDB->name =="")?			$HTMLBody :	addContent($HTMLBody, "Name" , makeHyper($rowMagicItemsInDB->name, 'magical',$rowMagicItemsInDB->id,$hyperMode) );
			$HTMLBody	= addPreternaturalTypeToHTML($rowMagicItemsInDB->id ,$HTMLBody,$hyperMode );
		}
	}

	$queryForPeopleSearch	 	=  "SELECT data_person.preferred_name,data_person.id FROM data_person LEFT JOIN Participates ON data_person.id = Participates.person_id WHERE (Participates.eventassertion_id = $thisid) ORDER BY \"data_person.preferred_name\" ASC";
	$resultForPeopleSearch 		= mysql_query($queryForPeopleSearch);
	if (mysql_num_rows($resultForPeopleSearch) != 0){
		$HTMLBody 		= addSmallHeader($HTMLBody, "People in This Event:");
		while ($rowPeopleItemsInDB = mysql_fetch_object($resultForPeopleSearch))
			$HTMLBody	= addListContent($HTMLBody, makeHyper($rowPeopleItemsInDB->preferred_name, 'people',$rowPeopleItemsInDB->id,$hyperMode));
		$HTMLBody 		= addBR($HTMLBody);
	}

	$HTMLBody 	= addSmallHeader($HTMLBody, "Sources Linked to this Event:");
	$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);

	echo $HTMLBody;
} 

function echomagical($thisid,$hyperMode){ 
	$HTMLBody = "";
	$HTMLBody = addHeader($HTMLBody, "Preternatural Information");

	$queryForSearch	 = "SELECT * FROM data_magicalbeing WHERE `id` =". $thisid;
	$resultForSearch = mysql_query($queryForSearch);

	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){

		$HTMLBody 	= ($rowItemsInDB->name =="")?		$HTMLBody :	addContent($HTMLBody, "Name"		, $rowItemsInDB->name);
		$HTMLBody	= addPreternaturalTypeToHTML($rowItemsInDB->id ,$HTMLBody,$hyperMode);
	}
	addPreternaturalLocationsToHTML($rowItemsInDB->id ,$HTMLBody ,$hyperMode);

	$queryForSearch	 = "SELECT * FROM data_magicalbeingassertion WHERE `refers_to_id` =$thisid";
	$resultForSearch = mysql_query($queryForSearch);

	$magicalAssertionCounter = 0;
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$magicalAssertionCounter++;

		$HTMLBody 	= addSmallerHeader($HTMLBody, "Preternatural Assertion Number: ". $magicalAssertionCounter);
		$HTMLBody 	= addAssertionPreternaturalToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	}


	$queryForSearch	 =  "SELECT data_eventassertion.*, Intervenes.eventassertion_id, Intervenes.magicalbeing_id FROM data_eventassertion LEFT JOIN Intervenes ON data_eventassertion.id = Intervenes.eventassertion_id WHERE (Intervenes.magicalbeing_id = $thisid) ORDER BY \"data_eventassertion.in_source_id\" ASC";
	$resultForSearch = mysql_query($queryForSearch);

	if (mysql_num_rows($resultForSearch) != 0){
		$HTMLBody = addHeader($HTMLBody, "Events Recorded for this Preternatural: ");
		$eventCounter = 0;
		$previousSourceID = -1;
		$descCounter = 0;
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){


			if ($previousSourceID != $rowItemsInDB->in_source_id){
				
				$eventCounter++;
				$descCounter = 1;
				$previousSourceID = $rowItemsInDB->in_source_id;
				$HTMLBody 	= addSmallHeader($HTMLBody, "Event Number " . $eventCounter. ":");
				$HTMLBody 	= addSmallerHeader($HTMLBody, "Description Number " . $descCounter. ":");
			}else{
				$descCounter++;
				$HTMLBody 	= addSmallerHeader($HTMLBody, "Description Number " . $descCounter. ":");
			}

			$HTMLBody 	= addAssertionEventToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
			$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);



			$thisEventAssertionID = $rowItemsInDB->id;

			$queryForMagicalSearch	 =  "SELECT data_magicalbeing.name,data_magicalbeing.id FROM data_magicalbeing LEFT JOIN Intervenes ON data_magicalbeing.id = Intervenes.magicalbeing_id WHERE (Intervenes.eventassertion_id = $thisEventAssertionID) ORDER BY \"data_magicalbeing.name\" ASC";
			$resultForMagicalSearch = mysql_query($queryForMagicalSearch);
			if (mysql_num_rows($resultForMagicalSearch) != 0){

				$HTMLBody 	= addBR($HTMLBody);
				$HTMLBody 	= addSmallHeader($HTMLBody, "Preternaturals in This Event:");
				while ($rowMagicItemsInDB = mysql_fetch_object($resultForMagicalSearch)){
					$HTMLBody = addListContent($HTMLBody, makeHyper($rowMagicItemsInDB->name, 'magical',$rowMagicItemsInDB->id,$hyperMode));
				}
			}

			$queryForPeopleSearch	 =  "SELECT data_person.preferred_name,data_person.id FROM data_person LEFT JOIN Participates ON data_person.id = Participates.person_id WHERE (Participates.eventassertion_id = $thisEventAssertionID) ORDER BY \"data_person.preferred_name\" ASC";


			$resultForPeopleSearch = mysql_query($queryForPeopleSearch);
			if (mysql_num_rows($resultForPeopleSearch) != 0){
				$HTMLBody 	= addBR($HTMLBody);
				$HTMLBody 	= addSmallerHeader($HTMLBody, "People in This Event:");
				while ($rowPeopleItemsInDB = mysql_fetch_object($resultForPeopleSearch)){
					$HTMLBody	= addListContent($HTMLBody, makeHyper($rowPeopleItemsInDB->preferred_name, 'people',$rowPeopleItemsInDB->id,$hyperMode));
				}
			}


		}
	}
	echo $HTMLBody;
} 

function echotext($thisid,$hyperMode){ 

	$HTMLBody = "";
	$HTMLBody = addHeader($HTMLBody, "Textual Source Information");
	$HTMLBody = addSourceToHTML($thisid, '',$HTMLBody,$hyperMode);
	$queryForSearch	 =  "SELECT * FROM data_eventassertion WHERE ((in_source_id = $thisid) OR (in_source2_id = $thisid) OR (in_source3_id = $thisid)
 									OR (in_source4_id = $thisid) OR (in_source5_id = $thisid) OR (in_source6_id = $thisid)
 									OR (in_source7_id = $thisid) OR (in_source8_id = $thisid) OR (in_source9_id = $thisid)) ORDER BY \"data_eventassertion.start_date\" ASC";
	$resultForSearch = mysql_query($queryForSearch);
	if (mysql_num_rows($resultForSearch) != 0){
		$HTMLBody 	= addSmallHeader($HTMLBody, "Events In This Source: ");
		$eventCounter = 0;
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
			$eventCounter++;
			$HTMLBody 	= addSmallerHeader($HTMLBody, "Events Number: " . $eventCounter);
			$HTMLBody 	= addAssertionEventToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
			$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);

		}
	}

	$queryForSearch	 =  "SELECT * FROM data_magicalbeingassertion WHERE ((in_source_id = $thisid) OR (in_source2_id = $thisid) OR (in_source3_id = $thisid)
 									OR (in_source4_id = $thisid) OR (in_source5_id = $thisid) OR (in_source6_id = $thisid)
 									OR (in_source7_id = $thisid) OR (in_source8_id = $thisid) OR (in_source9_id = $thisid)) ORDER BY \"data_magicalbeingassertion.name\" ASC";
	$resultForSearch = mysql_query($queryForSearch);
	if (mysql_num_rows($resultForSearch) != 0){
		$HTMLBody 	= addBR($HTMLBody);
		$HTMLBody 	= addSmallHeader($HTMLBody, "Preternaturals Assertions In This Source: ");
		$magicalAssertionCounter = 0;
		while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
			$magicalAssertionCounter++;
			$HTMLBody 	= addSmallerHeader($HTMLBody, "Preternatural Assertion Number: ". $magicalAssertionCounter);
			$HTMLBody 	= addAssertionPreternaturalToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
		}
	}


	$queryForSearch	 =  "SELECT * FROM data_personassertion WHERE ((in_source_id = $thisid) OR (in_source2_id = $thisid) OR (in_source3_id = $thisid)
 									OR (in_source4_id = $thisid) OR (in_source5_id = $thisid) OR (in_source6_id = $thisid)
 									OR (in_source7_id = $thisid) OR (in_source8_id = $thisid) OR (in_source9_id = $thisid)) ORDER BY \"data_personassertion.name\" ASC";
	$resultForSearch = mysql_query($queryForSearch);
	if (mysql_num_rows($resultForSearch) != 0){
		$HTMLBody 	= addBR($HTMLBody);
		$HTMLBody 	= addSmallHeader($HTMLBody, "People In This Source: ");
		$assertionCounter = 0;
		while($rowItemsInDB = mysql_fetch_object($resultForSearch)){
			$assertionCounter++;
			$HTMLBody 	= addSmallHeader($HTMLBody, "Person Assertion Number " . $assertionCounter. ":");
			$HTMLBody 	= addAssertionPersonToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
		}
	}

	echo $HTMLBody;

} 

function echoeventforpeople($thisid,$hyperMode){ 

	$queryForSearch	 =  "SELECT data_eventassertion.* FROM data_eventassertion RIGHT JOIN Participates ON 
				data_eventassertion.id = Participates.eventassertion_id WHERE (Participates.person_id = $thisid) 
				ORDER BY \"data_eventassertion.start_date\" ASC LIMIT 1";
	$resultForSource = mysql_query($queryForSearch);
	if ($rowSearchInDB = mysql_fetch_object($resultForSource))
		echoevents($rowSearchInDB->id,$hyperMode);	

}

function echoeventformagical($thisid,$hyperMode){ 

	$queryForSearch	 =  "SELECT data_eventassertion.* FROM data_eventassertion LEFT JOIN Intervenes 
				ON data_eventassertion.id = Intervenes.eventassertion_id WHERE (Intervenes.magicalbeing_id = $thisid) 
				ORDER BY \"data_eventassertion.start_date\" ASC LIMIT 1";

	$resultForSource = mysql_query($queryForSearch);

	if ($rowSearchInDB = mysql_fetch_object($resultForSource))
		echoevents($rowSearchInDB->id,$hyperMode);	
}

function echoPreternaturalFunction($thisid,$hyperMode){ 

	$queryForSearch	 	= "SELECT data_preternaturalfunction.preternatural_function FROM data_preternaturalfunction
					WHERE (data_preternaturalfunction.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Preternaturals for Function: " . $rowItemsInDB->preternatural_function);

	$queryForSearch	 	= "SELECT data_magicalbeing.id, data_magicalbeing.name FROM data_magicalbeingassertion, Preternaturalfunction, data_magicalbeing
					WHERE (Preternaturalfunction.preternaturalfunction_id = $thisid) 
					AND(Preternaturalfunction.magicalbeingassertion_id = data_magicalbeingassertion.id)
					AND(data_magicalbeingassertion.refers_to_id = data_magicalbeing.id)
					ORDER BY 'data_magicalbeing.name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$preterCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preterCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Preternatural $preterCounter ", makeHyper($rowItemsInDB->name, 'magical',$rowItemsInDB->id,$hyperMode));


	}

	echo $HTMLBody;
}

function echoPreternaturalFedWith($thisid,$hyperMode){ 

	$queryForSearch	 	= "SELECT data_preternaturalfood.preternatural_food FROM data_preternaturalfood
					WHERE (data_preternaturalfood.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Preternaturals for Food: " . $rowItemsInDB->preternatural_food);

	$queryForSearch	 	= "SELECT data_magicalbeing.id, data_magicalbeing.name FROM data_magicalbeingassertion, Preternaturalfood, data_magicalbeing
					WHERE (Preternaturalfood.preternaturalfood_id = $thisid) 
					AND(Preternaturalfood.magicalbeingassertion_id = data_magicalbeingassertion.id)
					AND(data_magicalbeingassertion.refers_to_id = data_magicalbeing.id)
					ORDER BY 'data_magicalbeing.name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$preterCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preterCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Preternatural $preterCounter ", makeHyper($rowItemsInDB->name, 'magical',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPreternaturalForm($thisid,$hyperMode){ 

	$queryForSearch	 	= "SELECT  data_perternaturalform.preternatural_form FROM data_perternaturalform
					WHERE (data_perternaturalform.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Preternaturals for Form: " . $rowItemsInDB->preternatural_form);

	$queryForSearch	 	= "SELECT data_magicalbeing.id, data_magicalbeing.name FROM data_magicalbeingassertion, Perternaturalform, data_magicalbeing
					WHERE (Perternaturalform.perternaturalform_id = $thisid) 
					AND(Perternaturalform.magicalbeingassertion_id = data_magicalbeingassertion.id)
					AND(data_magicalbeingassertion.refers_to_id = data_magicalbeing.id)
					ORDER BY 'data_magicalbeing.name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$preterCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preterCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Preternatural $preterCounter ", makeHyper($rowItemsInDB->name, 'magical',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPreternaturalMOC($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_preternaturalmodeofcontact.mode_of_contact FROM data_preternaturalmodeofcontact
					WHERE (data_preternaturalmodeofcontact.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Preternaturals for Mode of Contact: " . $rowItemsInDB->mode_of_contact);

	$queryForSearch	 	= "SELECT data_magicalbeing.id, data_magicalbeing.name FROM data_magicalbeingassertion, Preternaturalmodofcontact, data_magicalbeing
					WHERE (Preternaturalmodofcontact.preternaturalmodeofcontact_id = $thisid) 
					AND(Preternaturalmodofcontact.magicalbeingassertion_id = data_magicalbeingassertion.id)
					AND(data_magicalbeingassertion.refers_to_id = data_magicalbeing.id)
					ORDER BY 'data_magicalbeing.name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$preterCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preterCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Preternatural $preterCounter ", makeHyper($rowItemsInDB->name, 'magical',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPreternaturalType($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_magicalbeingtype.magicalbeing_type FROM data_magicalbeingtype
					WHERE (data_magicalbeingtype.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Preternaturals Type: " . $rowItemsInDB->magicalbeing_type);

	$queryForSearch	 	= "SELECT data_magicalbeing.id, data_magicalbeing.name FROM Magicalbeingtype, data_magicalbeing
					WHERE (Magicalbeingtype.magicalbeing_id = data_magicalbeing.id ) AND (Magicalbeingtype.magicalbeingtype_id = $thisid)
					ORDER BY 'data_magicalbeing.name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$preterCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$preterCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Preternatural $preterCounter ", makeHyper($rowItemsInDB->name, 'magical',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPersonType($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_persontype.person_type FROM data_persontype
					WHERE (data_persontype.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Person Type: " . $rowItemsInDB->person_type);

	$queryForSearch	 	= "SELECT data_person.preferred_name, data_person.id FROM data_person, data_personassertion, Persontype
					WHERE (data_personassertion.refers_to_id = data_person.id)
						AND(data_personassertion.id = Persontype.personassertion_id)
						AND(Persontype.persontype_id = $thisid)
					ORDER BY 'data_person.preferred_name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$personCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$personCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Person $personCounter ", makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPersonStatus($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_personstatus.person_status FROM data_personstatus
					WHERE (data_personstatus.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Person Status: " . $rowItemsInDB->person_status);

	$queryForSearch	 	= "SELECT data_person.preferred_name, data_person.id FROM data_person, data_personassertion, Personstatus
					WHERE (data_personassertion.refers_to_id = data_person.id)
						AND(data_personassertion.id = Personstatus.personassertion_id)
						AND(Personstatus.personstatus_id = $thisid)
					ORDER BY 'data_person.preferred_name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$personCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$personCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Person $personCounter ", makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoPersonOccup($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_personoccup.person_occup FROM data_personoccup
					WHERE (data_personoccup.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Person Occupation: " . $rowItemsInDB->person_occup);

	$queryForSearch	 	= "SELECT data_person.preferred_name, data_person.id FROM data_person, data_personassertion, Personoccup
					WHERE (data_personassertion.refers_to_id = data_person.id)
						AND(data_personassertion.id = Personoccup.personassertion_id)
						AND(Personoccup.personoccup_id = $thisid)
					ORDER BY 'data_person.preferred_name' ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$personCounter		= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$personCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Person $personCounter ", makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}

function echoEventType($thisid,$hyperMode){  

	$queryForSearch	 	= "SELECT data_eventtype.event_type FROM data_eventtype
					WHERE (data_eventtype.id = $thisid)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$rowItemsInDB 		= mysql_fetch_object($resultForSearch);
	$HTMLBody 		= addHeader("", "Event Type: " . $rowItemsInDB->event_type);

	$queryForSearch	 	= "SELECT data_eventassertion.start_date, data_eventassertion.id, data_eventassertion.short_desc
					FROM Eventtype, data_eventassertion
					WHERE (Eventtype.eventtype_id = $thisid)AND (Eventtype.eventassertion_id = data_eventassertion.id)
					ORDER BY  `data_eventassertion`.`start_date` ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$eventAssertCounter	= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$eventAssertCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Event Assertion $eventAssertCounter ", 
					makeHyper(substr($rowItemsInDB->start_date, 0, 4) , 'year',substr($rowItemsInDB->start_date, 0, 4),$hyperMode) . ", " .
					makeHyper($rowItemsInDB->short_desc , 'events',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}


function echoYear($thisid,$hyperMode){  

	$HTMLBody 		= addHeader("", "Event Year: " . $thisid);

	$queryForSearch	 	= "SELECT data_eventassertion.start_date, data_eventassertion.id, data_eventassertion.short_desc
					FROM data_eventassertion
					WHERE  ($thisid =  SUBSTR(data_eventassertion.start_date, 1, 4))
					ORDER BY  `data_eventassertion`.`start_date` ASC";

	$resultForSearch 	= mysql_query($queryForSearch);
	$eventAssertCounter	= 0;

	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$eventAssertCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Event Assertion $eventAssertCounter ", 
					makeHyper(substr($rowItemsInDB->start_date, 0, 4) , 'year',substr($rowItemsInDB->start_date, 0, 4),$hyperMode) . ", " .
					makeHyper($rowItemsInDB->short_desc , 'events',$rowItemsInDB->id,$hyperMode));
	}

	echo $HTMLBody;
}









function makeHyper($itemTitle, $itemCat, $thisid, $hyperMode){
	if ($hyperMode == 1)
		return "<A href=\"javascript:showIndividualCard('$itemCat',$thisid)\">$itemTitle</A>";
	return $itemTitle;
}

?>
