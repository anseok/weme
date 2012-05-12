<?php

$queryForSearch	 	= "SELECT * FROM  `data_eventtype` ORDER BY  `data_eventtype`.`event_type` ASC";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralEventType[$rowItemsInDB->id] = $rowItemsInDB->event_type;

$queryForSearch	 	= "SELECT * FROM  `data_magicalbeingtype` ORDER BY  `data_magicalbeingtype`.`magicalbeing_type` ASC";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralPreternaturalType[$rowItemsInDB->id] = $rowItemsInDB->magicalbeing_type;

$queryForSearch	 	= "SELECT * FROM  `data_persontype` ORDER BY  `data_persontype`.`person_type` ASC";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralPersonType[$rowItemsInDB->id] = $rowItemsInDB->person_type;

$queryForSearch	 	= "SELECT * FROM  `data_preternaturalmodeofcontact` ORDER BY  `data_preternaturalmodeofcontact`.`mode_of_contact` ASC ";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralPreternaturalMOC[$rowItemsInDB->id] = $rowItemsInDB->mode_of_contact;

$queryForSearch	 	= "SELECT * FROM  `data_preternaturalfood` ORDER BY  `data_preternaturalfood`.`preternatural_food` ASC ";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralPreternaturalFood[$rowItemsInDB->id] = $rowItemsInDB->preternatural_food;

$queryForSearch	 	= "SELECT * FROM  `data_preternaturalfunction` ORDER BY  `data_preternaturalfunction`.`preternatural_function` ASC ";
$resultForSearch 	= mysql_query($queryForSearch);
while  ($rowItemsInDB 	= mysql_fetch_object($resultForSearch))
	$wemeGeneralPreternaturalFunction[$rowItemsInDB->id] = $rowItemsInDB->preternatural_function;


///////////////// Reaturn Type Values ///////////////////////////////////////////////////

function returnEventType($type){
	global $wemeGeneralEventType;
	if (isset($wemeGeneralEventType[$type]))
		return $wemeGeneralEventType[$type];
	else 
		return "";
}

function returnMagicalBeingType($type){
	global $wemeGeneralPreternaturalType;
	return $wemeGeneralPreternaturalType[$type];
}

function returnWitchType($type){
	global $wemeGeneralPersonType;
	return $wemeGeneralPersonType[$type];
}


function returnModeOfContactType($type){
	global $wemeGeneralPreternaturalMOC;
	return $wemeGeneralPreternaturalMOC[$type];
}

function returnGenderType($type){

		switch ($type){
			case "m":
				return  "Male";
				break;
			case "f":
				return  "Female";
				break;
			case "u":
				return  "Unknown";
				break;
		}
}

function returnRelationType($type){

		switch ($type){
			case "m":
				return  "Mother";
				break;
			case "f":
				return  "Father";
				break;
			case "d":
				return  "Daughter";
				break;
			case "s":
				return  "Son";
				break;
			case "n":
				return  "Neighbor";
				break;
			case "c":
				return  "Co-conspirator";
				break;
			case "a":
				return  "Accuser";
				break;
			case "w":
				return  "Wife";
				break;
			case "h":
				return  "Husband";
				break;

		}
}


///////////////// Reaturn Drop Down Bodies ///////////////////////////////////////////////////


function returnEventTypeBody($sizeOfTrunc){

	global $wemeGeneralEventType;
	$eventTypeDropDown = "<option value='-1'>-</option>";

	foreach ($wemeGeneralEventType as $key => $value)
		$eventTypeDropDown .= "<option value='$key'>" . truncatetext($value,$sizeOfTrunc) . "</option>";

	return $eventTypeDropDown;
}

function returnMBeingTypeBody($sizeOfTrunc){

	global $wemeGeneralPreternaturalType;
	$mbeingTypeDropDown = "<option value='-1'>-</option>";

	foreach ($wemeGeneralPreternaturalType as $key => $value)
		$mbeingTypeDropDown .= "<option value='$key'>" . truncatetext($value,$sizeOfTrunc) . "</option>";

	return $mbeingTypeDropDown;
}

function returnWitchTypeBody($sizeOfTrunc){

	global $wemeGeneralPersonType;
	$witchTypeDropDown = "<option value='-1'>-</option>";

	foreach ($wemeGeneralPersonType as $key => $value)
		$witchTypeDropDown .= "<option value='$key'>" . truncatetext($value,$sizeOfTrunc) . "</option>";

	return $witchTypeDropDown;
}

function returnAuthorBody($sizeOfTrunc){

	$queryForSearch	 	= "SELECT data_author.id,data_author.last_name,data_author.first_name FROM data_author WHERE (last_name<>'')OR(first_name<>'') ORDER BY data_author.last_name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$dropDown = "<option value='-1'>-</option>";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$dropDown .= "<option value='" . $rowItemsInDB->id . "'> ". truncatetext($rowItemsInDB->last_name .', ' .$rowItemsInDB->first_name, $sizeOfTrunc). "</option>"; 

	return $dropDown;
}

function returnSourceBody($sizeOfTrunc){

	$queryForSearch	 	= "SELECT data_source.id,data_source.short_title FROM data_source WHERE (short_title<>'') ORDER BY short_title ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$dropDown = "<option value='-1'>-</option>";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$dropDown .= "<option value='" . $rowItemsInDB->id . "'> ". truncatetext($rowItemsInDB->short_title, $sizeOfTrunc). "</option>"; 

	return $dropDown;
}

function returnPersonBody($sizeOfTrunc){

	$queryForSearch	 	= "SELECT data_person.preferred_name,data_person.id FROM data_person WHERE (preferred_name<>'') ORDER BY preferred_name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$dropDown = "<option value='-1'>-</option>";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$dropDown .= "<option value='" . $rowItemsInDB->id . "'> ". truncatetext($rowItemsInDB->preferred_name, $sizeOfTrunc). "</option>"; 

	return $dropDown;
}

function returnEventAssertionBody($sizeOfTrunc){

	$queryForSearch	 	= "SELECT id,short_desc FROM data_eventassertion WHERE (short_desc<>'') ORDER BY short_desc ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$dropDown = "<option value='-1'>-</option>";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$dropDown .= "<option value='" . $rowItemsInDB->id . "'> ". truncatetext($rowItemsInDB->short_desc, $sizeOfTrunc). "</option>"; 

	return $dropDown;
}

function returnMagicalBeingBody($sizeOfTrunc){

	$queryForSearch	 	= "SELECT id,name FROM data_magicalbeing WHERE (name<>'') ORDER BY name ASC";

	$resultForSearch = mysql_query($queryForSearch);
	$dropDown = "<option value='-1'>-</option>";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch))
		$dropDown .= "<option value='" . $rowItemsInDB->id . "'> ". truncatetext($rowItemsInDB->name, $sizeOfTrunc). "</option>"; 

	return $dropDown;
}


function truncatetext($text, $maxsize){
		if (strlen($text) > $maxsize)
			return substr($text, 0, $maxsize) . '...';
		else	
			return $text;
}


///////////////// Mapping Load Content ///////////////////////////////////////////////////

function addContent($origText, $key, $value){
	return "$origText  <B>$key</B> : $value<BR><BR>";
}

function addListContent($origText, $value){
	return "$origText <BR> $value";
}

function addHeader($origText, $value){
	return "$origText <H2>$value</H2>";
}

function addSmallHeader($origText, $value){
	return "$origText <H3>$value</H3>";
}


function addSmallerHeader($origText, $value){
	return "$origText <H4>$value</H4>";
}

function addBR($origText){
	return "$origText <BR><BR>";
}

function addSourceToHTML($thisSourceid, $myID ,$HTMLBody,$hyperMode){

	if ($thisSourceid == "") return $HTMLBody;
	$sourceType = array('p' =>'Primary','s' =>'Secondary','r' =>'State records' );
	$querySource = "SELECT data_source . * , data_author.first_name, data_author.last_name FROM data_source
								LEFT JOIN data_author ON data_source.author_id = data_author.id WHERE data_source.id =". $thisSourceid;
	$resultForSource = mysql_query($querySource);
	if ($rowSearchInDB = mysql_fetch_object($resultForSource)){
		$publicationInfo = "";
		$publicationInfo = ($rowSearchInDB->source_item_type =="")? 	$publicationInfo : addContent($publicationInfo, "Source Type $myID" 		, $sourceType[$rowSearchInDB->source_item_type]);
		$publicationInfo = ($rowSearchInDB->short_title =="")? 		$publicationInfo : addContent($publicationInfo, "Source Short Title $myID" 	, "<i>" . makeHyper($rowSearchInDB->short_title, 'text',$rowSearchInDB->id,$hyperMode) . "</i>");
		$publicationInfo = ($rowSearchInDB->long_title =="")? 		$publicationInfo : addContent($publicationInfo, "Source Long Title $myID" 	, "<i>" . $rowSearchInDB->long_title . "</i>");
		$publicationInfo = ($rowSearchInDB->first_name =="")? 		addContent($publicationInfo, "Author First Name $myID" , "Anonymous") : 	addContent($publicationInfo,  "Author First Name $myID" , $rowSearchInDB->first_name);
		$publicationInfo = ($rowSearchInDB->last_name =="")? 		addContent($publicationInfo, "Author Last Name $myID" , "Anonymous") : 		addContent($publicationInfo, "Author Last Name $myID" , $rowSearchInDB->last_name);
		$publicationInfo = ($rowSearchInDB->source_url=="")? 		$publicationInfo : addContent($publicationInfo, "URL" 	, "<a href=\"$rowSearchInDB->source_url\" target=\"_blank\">$rowSearchInDB->source_url</a>");
		$publicationInfo = ($rowSearchInDB->pub_place =="")? 		$publicationInfo : addContent($publicationInfo, "Publication Place $myID" 	, $rowSearchInDB->pub_place);
		$publicationInfo = ($rowSearchInDB->sold_at =="")? 		$publicationInfo : addContent($publicationInfo, "Sold At $myID" 		, $rowSearchInDB->sold_at);
		$publicationInfo = ($rowSearchInDB->date_published =="")? 	$publicationInfo : addContent($publicationInfo, "Date Published $myID" 		, makeHyper($rowSearchInDB->date_published , 'year',substr($rowSearchInDB->date_published, 0, 4),$hyperMode));
		$publicationInfo = ($rowSearchInDB->wing_num =="")? 		$publicationInfo : addContent($publicationInfo, "WING Number $myID" 		, $rowSearchInDB->wing_num);
		$publicationInfo = ($rowSearchInDB->stc_num =="")? 		$publicationInfo : addContent($publicationInfo, "Short Title Catalogue Number $myID" , $rowSearchInDB->stc_num);
		$publicationInfo = ($rowSearchInDB->thompson_num =="")? 	$publicationInfo : addContent($publicationInfo, "Thompson Number $myID" 	, $rowSearchInDB->thompson_num);
		$HTMLBody 	.= ($publicationInfo == "")? "":"<BR>" . 	$publicationInfo . "<BR>" . "<BR>" . "" ;
	}
	return $HTMLBody;
}

function addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode){
	$HTMLBody	= addAssertionSourceToHTMLOne($rowItemsInDB ,$HTMLBody);
	$HTMLBody 	= addSourceToHTML($rowItemsInDB->in_source_id, 1,$HTMLBody,$hyperMode);

	for ($i = 2; $i < 14; $i++){
		$thisSource = "in_source".$i."_id";
		$HTMLBody 	= addSourceToHTML($rowItemsInDB->$thisSource, $i,$HTMLBody,$hyperMode);
		$HTMLBody	= addAssertionSourceToHTMLGeneral($rowItemsInDB ,$HTMLBody, $i);
	}

	return $HTMLBody;
}

function addAssertionSourceToHTMLOne($rowItemsInDB ,$HTMLBody){
	$HTMLBody 	= ($rowItemsInDB->short_desc =="")?		$HTMLBody : 	addContent($HTMLBody, "Short Description"	, $rowItemsInDB->short_desc);
	$HTMLBody 	= ($rowItemsInDB->orig_text =="")?		$HTMLBody :	addContent($HTMLBody, "Original Text"		, $rowItemsInDB->orig_text);
	$HTMLBody 	= ($rowItemsInDB->notes =="")?			$HTMLBody :	addContent($HTMLBody, "Notes"			, $rowItemsInDB->notes);
	$HTMLBody 	= ($rowItemsInDB->position_para =="")?		$HTMLBody :	addContent($HTMLBody, "Paragraph Position"	, $rowItemsInDB->position_para);
	$HTMLBody 	= ($rowItemsInDB->position =="")?		$HTMLBody :	addContent($HTMLBody, "Page Number"		, $rowItemsInDB->position);
	return $HTMLBody;
}

function addAssertionSourceToHTMLGeneral($rowItemsInDB ,$HTMLBody, $thisNum){
	$temp	 	= "orig_text" . $thisNum;
	$HTMLBody 	= ($rowItemsInDB->$temp =="")?			$HTMLBody :	addContent($HTMLBody, "Original Text $thisNum"		, $rowItemsInDB->$temp);
	$temp	 	= "note" . $thisNum;
	$HTMLBody 	= ($rowItemsInDB->$temp =="")?			$HTMLBody :	addContent($HTMLBody, "Notes $thisNum"			, $rowItemsInDB->$temp);
	$temp	 	= "position_para" . $thisNum;
	$HTMLBody 	= ($rowItemsInDB->$temp =="")?			$HTMLBody :	addContent($HTMLBody, "Paragraph Position $thisNum"	, $rowItemsInDB->$temp);
	$temp	 	= "position" . $thisNum;
	$HTMLBody 	= ($rowItemsInDB->$temp =="")?			$HTMLBody :	addContent($HTMLBody, "Page Number $thisNum"		, $rowItemsInDB->$temp);
	return $HTMLBody;
}

function addAssertionEventToHTML($rowItemsInDB ,$HTMLBody, $hyperMode){

	$id 			= $rowItemsInDB->id;
	$startDate 		= $rowItemsInDB->start_date;
	$endDate 		= $rowItemsInDB->end_date;
	$recordedLocationID 	= $rowItemsInDB->location_id;
	$recordedLocation 	= $rowItemsInDB->recorded_location;
	$shortDesc 		= $rowItemsInDB->short_desc;
	$law_id 		= $rowItemsInDB->law_id;
	$queryForSearch	 	= "SELECT data_eventtype.id, data_eventtype.event_type FROM Eventtype, data_eventtype WHERE (data_eventtype.id = Eventtype.eventtype_id) AND (Eventtype.eventassertion_id =$id)";	
	$resultForSearch 	= mysql_query($queryForSearch);
	$eventCounter		= 0;
	$HTMLBody 		= addContent($HTMLBody, "Event Short Description" , makeHyper($shortDesc, 'events',$id,$hyperMode));
	$HTMLBody 		= addSmallHeader($HTMLBody, "Event Types:");
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$eventCounter++;
		$HTMLBody 	= addContent($HTMLBody, "Event Type $eventCounter", makeHyper($rowItemsInDB->event_type, 'eventType', $rowItemsInDB->id ,$hyperMode));
	}
	$HTMLBody 		= ($startDate =="")?		$HTMLBody : addContent($HTMLBody, "Event Start Date"		, makeHyper($startDate , 'year',substr($startDate, 0, 4),$hyperMode));
	$HTMLBody 		= ($endDate =="")?		$HTMLBody : addContent($HTMLBody, "Event End Date"		, makeHyper($endDate , 'year',substr($endDate, 0, 4),$hyperMode));

	$HTMLBody 		= ($recordedLocation =="")?	$HTMLBody : addContent($HTMLBody, "Event Recorded Location"	, makeHyper($recordedLocation, 'locations', $recordedLocationID ,$hyperMode) );
	$queryForSearch	 	= "SELECT data_law.shorttitle FROM data_law WHERE (id = $law_id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	if ($rowItemsInDB 	= mysql_fetch_object($resultForSearch)){
		$HTMLBody 	= addContent($HTMLBody, "Law", makeHyper($rowItemsInDB->shorttitle, 'laws', $law_id ,$hyperMode) );
	}
	return $HTMLBody;
}

function addAssertionPersonToHTML($rowItemsInDB ,$HTMLBody,$hyperMode){
	$HTMLBody	= addPersonTypeToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode);
	$HTMLBody 	= ($rowItemsInDB->name_detail =="")?	$HTMLBody :	addContent($HTMLBody, "Name Detail"		, $rowItemsInDB->name_detail);
	$HTMLBody 	= ($rowItemsInDB->description =="")?	$HTMLBody :	addContent($HTMLBody, "Description"		, $rowItemsInDB->description);
	$HTMLBody	= addPersonRelativesToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	$HTMLBody	= addPersonStatusToHTML($rowItemsInDB->id ,$HTMLBody,$hyperMode);
	$HTMLBody	= addPersonOccupationToHTML($rowItemsInDB->id ,$HTMLBody,$hyperMode);
	$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	return $HTMLBody;
}

function addAssertionPreternaturalToHTML($rowItemsInDB ,$HTMLBody,$hyperMode){
	$HTMLBody	= addPreternaturalFunctionToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode);
	$HTMLBody	= addPreternaturalFoodToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode);
	$HTMLBody 	= ($rowItemsInDB->container =="")?		$HTMLBody :	addContent($HTMLBody, "Container"			, $rowItemsInDB->container);
	$HTMLBody	= addPreternaturalFormToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode);
	$HTMLBody	= addPreternaturalModeOfContactToHTML($rowItemsInDB->id ,$HTMLBody, $hyperMode, $hyperMode);
	$HTMLBody	= addPreternaturalInheritedFromToHTML($rowItemsInDB->source_id ,$HTMLBody, $hyperMode);
	$HTMLBody	= addPreternaturalOwnedUsedSharedWith($rowItemsInDB->id ,$HTMLBody,$hyperMode, $hyperMode);
	$HTMLBody	= addAssertionSourceToHTML($rowItemsInDB ,$HTMLBody,$hyperMode);
	$HTMLBody 	= ($rowItemsInDB->relation_type =="")?		$HTMLBody :	addContent($HTMLBody, "Relation Type"			, $rowItemsInDB->relation_type);
	return $HTMLBody;
}

function addPersonRelativesToHTML($assertionID, $HTMLBody, $hyperMode){
	$recordedRelationship 	= returnRelationType($assertionID->personal_relationship);
	$HTMLBody 		= addBR($HTMLBody);
	$HTMLBody 		= ($recordedRelationship =="")?	$HTMLBody : addContent($HTMLBody, "Personal relationship asserted in text"	, $recordedRelationship);

	$queryForSearch	 	= "SELECT data_person.* FROM  data_person, Relationship WHERE data_person.id=Relationship.person_id AND Relationship.personassertion_id = " . $assertionID->id;
	$resultForSearch 	= mysql_query($queryForSearch);
	if (mysql_num_rows($resultForSearch) != 0){
		$tempBody		= '';
		while ($rowPeopleItemsInDB = mysql_fetch_object($resultForSearch)){
			$tempBody 	.= ($tempBody == '')? '' : ', ';
			$tempBody 	.= makeHyper($rowPeopleItemsInDB->preferred_name, 'people',$rowPeopleItemsInDB->id,$hyperMode);
		}
		$HTMLBody 		= addContent($HTMLBody, "Known kin group ", $tempBody);
		$HTMLBody 		= addBR($HTMLBody);
	}
	return $HTMLBody;
}

function addPersonTypeToHTML($assertionID ,$HTMLBody,$hyperMode){
	$queryForSearch	 	= "SELECT data_persontype.id, data_persontype.person_type FROM data_persontype, Persontype
					WHERE (Persontype.personassertion_id = $assertionID)AND(Persontype.persontype_id = data_persontype.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->person_type , 'personType',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 		= addContent($HTMLBody, "Person Type ", $tempBody);
	return $HTMLBody;
}

function addPersonStatusToHTML($assertionID ,$HTMLBody,$hyperMode){
	$queryForSearch	 	= "SELECT data_personstatus.id, data_personstatus.person_status FROM data_personstatus, Personstatus
					WHERE (Personstatus.personassertion_id=$assertionID)AND(Personstatus.personstatus_id=data_personstatus.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->person_status , 'personStatus',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody	 	= addContent($HTMLBody, "Person Status ", $tempBody);
	return $HTMLBody;
}

function addPersonOccupationToHTML($assertionID ,$HTMLBody,$hyperMode){
	$queryForSearch	 	= "SELECT data_personoccup.id ,data_personoccup.person_occup FROM data_personoccup, Personoccup
					WHERE (Personoccup.personassertion_id=$assertionID)AND(Personoccup.personoccup_id=data_personoccup.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->person_occup , 'personOccup',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 		= addContent($HTMLBody, "Person Occupation ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalTypeToHTML($assertionID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT data_magicalbeingtype.id,data_magicalbeingtype.magicalbeing_type FROM data_magicalbeingtype, Magicalbeingtype
					WHERE (Magicalbeingtype.magicalbeing_id=$assertionID)AND(Magicalbeingtype.magicalbeingtype_id=data_magicalbeingtype.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->magicalbeing_type , 'preternaturalType',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 		= addContent($HTMLBody, "Preternatural Type ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalFunctionToHTML($preternaturalAssertionID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT data_preternaturalfunction.preternatural_function,data_preternaturalfunction.id FROM data_preternaturalfunction, Preternaturalfunction
					WHERE (Preternaturalfunction.magicalbeingassertion_id=$preternaturalAssertionID)
					AND(Preternaturalfunction.preternaturalfunction_id=data_preternaturalfunction.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preternatural_function, 'preternaturalFunction',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 		= addContent($HTMLBody, "Preternatural Function ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalFoodToHTML($preternaturalAssertionID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT data_preternaturalfood.preternatural_food, data_preternaturalfood.id FROM data_preternaturalfood, Preternaturalfood
					WHERE (Preternaturalfood.magicalbeingassertion_id=$preternaturalAssertionID)
					AND(Preternaturalfood.preternaturalfood_id=data_preternaturalfood.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preternatural_food, 'preternaturalFedWith',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 	= addContent($HTMLBody, "Preternatural Fed/Paid With ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalFormToHTML($preternaturalAssertionID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT data_perternaturalform.preternatural_form, data_perternaturalform.id FROM data_perternaturalform, Perternaturalform
					WHERE (Perternaturalform.magicalbeingassertion_id=$preternaturalAssertionID)
					AND(Perternaturalform.perternaturalform_id=data_perternaturalform.id)";

	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preternatural_form, 'preternaturalForm',$rowItemsInDB->id,$hyperMode); 
	}
	$HTMLBody 	= addContent($HTMLBody, "Preternatural Form ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalModeOfContactToHTML($preternaturalAssertionID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT data_preternaturalmodeofcontact.mode_of_contact, data_preternaturalmodeofcontact.id FROM data_preternaturalmodeofcontact, Preternaturalmodofcontact
					WHERE (Preternaturalmodofcontact.magicalbeingassertion_id=$preternaturalAssertionID)
					AND(Preternaturalmodofcontact.preternaturalmodeofcontact_id=data_preternaturalmodeofcontact.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->mode_of_contact, 'preternaturalMOC',$rowItemsInDB->id,$hyperMode); 

	}
	$HTMLBody 	= addContent($HTMLBody, "Preternatural Mode of Contact ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalInheritedFromToHTML($personID ,$HTMLBody, $hyperMode){
	$queryForSearch	 	= "SELECT * FROM data_person WHERE id=$personID";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	if ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
	}
	$HTMLBody 	= addContent($HTMLBody, "Recieved or Inherited From: ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalOwnedUsedSharedWith($preternaturalAssertionID ,$HTMLBody, $hyperMode){

	$queryForSearch	 	= "SELECT data_person.* FROM Preternatualassertionownedby, data_person 
					WHERE (Preternatualassertionownedby.magicalbeingassertion_id = $preternaturalAssertionID)
					AND (Preternatualassertionownedby.person_id = data_person.id)
					ORDER BY data_person.preferred_name";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB 	= mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
	}
	$HTMLBody 		= addContent($HTMLBody, "Preternatural Owned By ", $tempBody);

	$queryForSearch	 	= "SELECT data_person.* FROM Preternatualassertionsharedwith, data_person 
					WHERE (Preternatualassertionsharedwith.magicalbeingassertion_id = $preternaturalAssertionID)
					AND (Preternatualassertionsharedwith.person_id = data_person.id)
					ORDER BY data_person.preferred_name";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB 	= mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
	}
	$HTMLBody 		= addContent($HTMLBody, "Preternatural Shared With ", $tempBody);

	$queryForSearch	 	= "SELECT data_person.* FROM Preternatualassertionusedagainst, data_person 
					WHERE (Preternatualassertionusedagainst.magicalbeingassertion_id = $preternaturalAssertionID)
					AND (Preternatualassertionusedagainst.person_id = data_person.id)
					ORDER BY data_person.preferred_name";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB 	= mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'people',$rowItemsInDB->id,$hyperMode);
	}
	$HTMLBody 		= addContent($HTMLBody, "Preternatural Used Against ", $tempBody);
	return $HTMLBody;
}

function addPersonLocationsToHTML($personID ,$HTMLBody,$hyperMode){
	$queryForSearch	 	= "SELECT data_location.preferred_name,data_location.id FROM data_location, Personlocation
					WHERE (Personlocation.person_id=$personID)AND(Personlocation.location_id=data_location.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'locations',$rowItemsInDB->id,$hyperMode);
	}

	$HTMLBody 	= addContent($HTMLBody, "Person Location(s) ", $tempBody);
	return $HTMLBody;
}

function addPreternaturalLocationsToHTML($preternaturalID ,$HTMLBody,$hyperMode){
	$queryForSearch	 	= "SELECT data_location.preferred_name,data_location.id FROM data_location, Preternatuallocation
					WHERE (Preternatuallocation.magicalbeing_id=$preternaturalID)AND(Preternatuallocation.location_id=data_location.id)";
	$resultForSearch 	= mysql_query($queryForSearch);
	$tempBody		= '';
	while ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$tempBody 	.= ($tempBody == '')? '' : ', ';
		$tempBody 	.= makeHyper($rowItemsInDB->preferred_name, 'locations',$rowItemsInDB->id,$hyperMode);
	}

	$HTMLBody 	= addContent($HTMLBody, "Preternatural Location(s) ", $tempBody);
	return $HTMLBody;
}

function cleansFromStrangeCharacters($value){
	$value = htmlentities($value);
	$value = preg_replace('/[^(\x20-\x7F)]*/','', $value);
	return $value;
}

?>
