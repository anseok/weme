<?php
function buildWemeGeneral() {
  /* 1. Do we need to do this for every client? */
  foreach (array(array('var' => 'EventType',
		       'tab' => 'eventtype',
		       'val' => 'event_type'),
		 array('var' => 'PreternaturalType',
		       'tab' => 'magicalbeingtype',
		       'val' => 'magicalbeing_type'),
		 array('var' => 'PersonType',
		       'tab' => 'persontype',
		       'val' => 'person_type'),
		 array('var' => 'PreternaturalMOC',
		       'tab' => 'preternaturalmodeofcontact',
		       'val' => 'mode_of_contact'),
		 array('var' => 'PreternaturalFood',
		       'tab' => 'preternaturalfood',
		       'val' => 'preternatural_food'),
		 array('var' => 'PreternaturalFunction',
		       'tab' => 'preternaturalfunction',
		       'val' => 'preternatural_function')) as $a) {
    $resultForSearch = mysql_query('SELECT id, ' . $a['val'] .
				   ' FROM data_' . $a['tab']);
    while ($rowItemsInDB = mysql_fetch_array($resultForSearch))
      $GLOBALS['wemeGeneral' . $a['var']][$rowItemsInDB['id']]
	= $rowItemsInDB[$a['val']];
  }
}

buildWemeGeneral();

///////////////// Return Type Values ///////////////////////////////////

function returnEventType($type) {
  global $wemeGeneralEventType;
  return isset($wemeGeneralEventType[$type])
    ? $wemeGeneralEventType[$type] : '';
}

function returnMagicalBeingType($type) {
  global $wemeGeneralPreternaturalType;
  return $wemeGeneralPreternaturalType[$type];
}

function returnWitchType($type) {
  global $wemeGeneralPersonType;
  return $wemeGeneralPersonType[$type];
}

function returnModeOfContactType($type) {
  global $wemeGeneralPreternaturalMOC;
  return $wemeGeneralPreternaturalMOC[$type];
}

function returnGenderType($type) {
  switch ($type) {
  case "m": return "Male";
  case "f": return "Female";
  case "u": return "Unknown";
  }
}

function returnRelationType($type) {
  switch ($type) {
  case "m": return "Mother";
  case "f": return "Father";
  case "d": return "Daughter";
  case "s": return "Son";
  case "n": return "Neighbor";
  case "c": return "Co-conspirator";
  case "a": return "Accuser";
  case "w": return  "Wife";
  case "h": return  "Husband";
  }
}

///////////////// Return Drop Down Bodies //////////////////////////////

function truncatetext($text, $maxsize) {
  return strlen($text) > $maxsize ? substr($text, 0, $maxsize) . '...'
    : $text;
}

function genDropdownArray($a, $sizeOfTrunc) {
  $dropdown = "<option value='-1'>-</option>";
  foreach ($a as $k => $v)
    $dropdown .= "<option value='$k'>" . truncatetext($v, $sizeOfTrunc)
    . '</option>';
  return $dropdown;
}

function returnEventTypeBody($sizeOfTrunc) {
  global $wemeGeneralEventType;
  return genDropdownArray($wemeGeneralEventType, $sizeOfTrunc);
}

function returnMBeingTypeBody($sizeOfTrunc) {
  global $wemeGeneralPreternaturalType;
  return genDropdownArray($wemeGeneralPreternaturalType, $sizeOfTrunc);
}

function returnWitchTypeBody($sizeOfTrunc) {
  global $wemeGeneralPersonType;
  return genDropdownArray($wemeGeneralPersonType, $sizeOfTrunc);
}

// works up to here

function genRowString($rowItemsInDB, $keys) {
  foreach ($keys as $k => $v)
    $a[$k] = $rowItemsInDB[$v];
  return implode(', ', $a);
}

function genDropdownSQL($q, $keys, $sizeOfTrunc) {
  $resultForSearch = mysql_query($q);
  $dropdown = "<option value='-1'>-</option>";
  while ($rowItemsInDB = mysql_fetch_array($resultForSearch))
    $dropdown .= "<option value='" . $rowItemsInDB['id'] . "'> "
      . truncatetext(genRowString($rowItemsInDB, $keys), $sizeOfTrunc)
      . "</option>";
  return $dropdown;
}

function returnAuthorBody($sizeOfTrunc) {
  return genDropdownSQL(
    "SELECT id, last_name, first_name FROM data_author
     WHERE last_name <> '' OR first_name <> '' ORDER BY last_name",
    array('last_name', 'first_name'), $sizeOfTrunc);
}

function returnSourceBody($sizeOfTrunc) {
  return genDropdownSQL(
    "SELECT id, short_title FROM data_source
     WHERE short_title <> '' ORDER BY short_title",
    array('short_title'), $sizeOfTrunc);
}

function returnPersonBody($sizeOfTrunc) {
  return genDropdownSQL(
    "SELECT preferred_name, id FROM data_person
     WHERE preferred_name <> '' ORDER BY preferred_name",
    array('preferred_name'), $sizeOfTrunc);
}

function returnEventAssertionBody($sizeOfTrunc) {
  return genDropdownSQL(
    "SELECT id, short_desc FROM data_eventassertion
     WHERE short_desc <> '' ORDER BY short_desc",
    array('short_desc'), $sizeOfTrunc);
}

function returnMagicalBeingBody($sizeOfTrunc) {
  return genDropdownSQL(
    "SELECT id, name FROM data_magicalbeing
     WHERE name <> '' ORDER BY name",
    array('name'), $sizeOfTrunc);
}

///////////////// Mapping Load Content /////////////////////////////////

function addContent($origText, $key, $value) {
  return "$origText  <B>$key</B> : $value<BR><BR>";
}

function addListContent($origText, $value) {
  return "$origText <BR> $value";
}

function addHeader($origText, $value) {
  return "$origText <H2>$value</H2>";
}

function addSmallHeader($origText, $value) {
  return "$origText <H3>$value</H3>";
}

function addSmallerHeader($origText, $value) {
  return "$origText <H4>$value</H4>";
}

function addBR($origText) {
  return "$origText <BR><BR>";
}

function addSourceToHTML($thisSourceid, $myID, $HTMLBody, $hyperMode) {
  if ($thisSourceid == "") return $HTMLBody;
  $sourceType = array('p' => 'Primary',
		      's' => 'Secondary',
		      'r' =>'State records');
  $querySource = "SELECT data_source.*,
                         data_author.first_name,
                         data_author.last_name
                  FROM data_source LEFT JOIN data_author
                  ON data_source.author_id = data_author.id
                  WHERE data_source.id =" . $thisSourceid;
  $resultForSource = mysql_query($querySource);
  if ($rowSearchInDB = mysql_fetch_object($resultForSource)) {
    $publicationInfo = "";
    if ($rowSearchInDB->source_item_type != '')
      $publicationInfo = addContent($publicationInfo, "Source Type $myID",
	$sourceType[$rowSearchInDB->source_item_type]);
    if ($rowSearchInDB->short_title != '')
      $publicationInfo = addContent($publicationInfo, "Source Short Title $myID",
	"<i>" . makeHyper($rowSearchInDB->short_title, 'text', $rowSearchInDB->id, $hyperMode) . "</i>");
    if ($rowSearchInDB->long_title != '')
      $publicationInfo = addContent($publicationInfo, "Source Long Title $myID",
	"<i>" . $rowSearchInDB->long_title . "</i>");
    $publicationInfo = addContent($publicationInfo, "Author First Name $myID",
	$rowSearchInDB->first_name == '' ? "Anonymous" : $rowSearchInDB->first_name);
    $publicationInfo = addContent($publicationInfo, "Author Last Name $myID",
	$rowSearchInDB->last_name == '' ? "Anonymous" : $rowSearchInDB->last_name);
    if ($rowSearchInDB->source_url != '')
      $publicationInfo = addContent($publicationInfo, "URL",
	"<a href=\"$rowSearchInDB->source_url\" target=\"_blank\">$rowSearchInDB->source_url</a>");
    if ($rowSearchInDB->pub_place != '')
      $publicationInfo = addContent($publicationInfo, "Publication Place $myID",
	$rowSearchInDB->pub_place);
    if ($rowSearchInDB->sold_at != '')
      $publicationInfo = addContent($publicationInfo, "Sold At $myID",
	$rowSearchInDB->sold_at);
    if ($rowSearchInDB->date_published != '')
      $publicationInfo = addContent($publicationInfo, "Date Published $myID",
	makeHyper($rowSearchInDB->date_published, 'year', substr($rowSearchInDB->date_published, 0, 4), $hyperMode));
    if ($rowSearchInDB->wing_num != '')
      $publicationInfo = addContent($publicationInfo, "WING Number $myID",
	$rowSearchInDB->wing_num);
    if ($rowSearchInDB->stc_num != '')
      $publicationInfo = addContent($publicationInfo, "Short Title Catalogue Number $myID",
	$rowSearchInDB->stc_num);
    if ($rowSearchInDB->thompson_num != '')
      $publicationInfo = addContent($publicationInfo, "Thompson Number $myID",
	$rowSearchInDB->thompson_num);
    $HTMLBody .= $publicationInfo == "" ? "" : "<BR>" . $publicationInfo . "<BR><BR>";
  }
  return $HTMLBody;
}

function addAssertionSourceToHTML($rowItemsInDB, $HTMLBody, $hyperMode) {
  $HTMLBody = addSourceToHTML($rowItemsInDB->in_source_id, 1,
    addAssertionSourceToHTMLOne($rowItemsInDB, $HTMLBody), $hyperMode);
  for ($i = 2; $i < 14; ++$i) {
    $thisSource = 'in_source' . $i . '_id';
    $HTMLBody = addAssertionSourceToHTMLGeneral($rowItemsInDB,
      addSourceToHTML($rowItemsInDB->$thisSource,
		      $i, $HTMLBody, $hyperMode),
      $i);
  }
  return $HTMLBody;
}

function addAssertionSourceToHTMLOne($rowItemsInDB, $HTMLBody) {
  if ($rowItemsInDB->short_desc != '')
    $HTMLBody = addContent($HTMLBody, "Short Description",
			   $rowItemsInDB->short_desc);
  if ($rowItemsInDB->orig_text != '')
    $HTMLBody = addContent($HTMLBody, "Original Text",
			   $rowItemsInDB->orig_text);
  if ($rowItemsInDB->notes != '')
    $HTMLBody = addContent($HTMLBody, "Notes",
			   $rowItemsInDB->notes);
  if ($rowItemsInDB->position_para != '')
    $HTMLBody = addContent($HTMLBody, "Paragraph Position",
			   $rowItemsInDB->position_para);
  if ($rowItemsInDB->position != '')
    $HTMLBody = addContent($HTMLBody, "Page Number",
			   $rowItemsInDB->position);
  return $HTMLBody;
}

function addAssertionSourceToHTMLGeneral($rowItemsInDB, $HTMLBody, $thisNum) {
  $temp	= "orig_text" . $thisNum;
  if ($rowItemsInDB->$temp != '')
    $HTMLBody = addContent($HTMLBody, "Original Text $thisNum",
			   $rowItemsInDB->$temp);
  $temp	= "note" . $thisNum;
  if ($rowItemsInDB->$temp != '')
    $HTMLBody = addContent($HTMLBody, "Notes $thisNum",
			   $rowItemsInDB->$temp);
  $temp	= "position_para" . $thisNum;
  if ($rowItemsInDB->$temp != '')
    $HTMLBody = addContent($HTMLBody, "Paragraph Position $thisNum",
			   $rowItemsInDB->$temp);
  $temp	= "position" . $thisNum;
  if ($rowItemsInDB->$temp != '')
    $HTMLBody = addContent($HTMLBody, "Page Number $thisNum",
			   $rowItemsInDB->$temp);
  return $HTMLBody;
}

function addAssertionEventToHTML($rowItemsInDB, $HTMLBody, $hyperMode) {
  $id = $rowItemsInDB->id;
  $startDate = $rowItemsInDB->start_date;
  $endDate = $rowItemsInDB->end_date;
  $recordedLocationID = $rowItemsInDB->location_id;
  $recordedLocation = $rowItemsInDB->recorded_location;
  $shortDesc = $rowItemsInDB->short_desc;
  $law_id = $rowItemsInDB->law_id;
  $eventCounter	= 0;
  $HTMLBody = addContent($HTMLBody, "Event Short Description",
    makeHyper($shortDesc, 'events', $id, $hyperMode));
  $HTMLBody = addSmallHeader($HTMLBody, "Event Types:");
  $resultForSearch = mysql_query(
    "SELECT data_eventtype.id, data_eventtype.event_type
     FROM data_eventtype INNER JOIN Eventtype
     ON data_eventtype.id = Eventtype.eventtype_id
     WHERE Eventtype.eventassertion_id = $id");
  while ($rowItemsInDB = mysql_fetch_object($resultForSearch)) {
    ++$eventCounter;
    $HTMLBody = addContent($HTMLBody, "Event Type $eventCounter",
      makeHyper($rowItemsInDB->event_type, 'eventType',
		$rowItemsInDB->id, $hyperMode));
  }
  if ($startDate != '')
    $HTMLBody = addContent($HTMLBody, "Event Start Date",
      makeHyper($startDate, 'year', substr($startDate, 0, 4),
		$hyperMode));
  if ($endDate != '')
    $HTMLBody = addContent($HTMLBody, "Event End Date",
      makeHyper($endDate, 'year', substr($endDate, 0, 4), $hyperMode));
  if ($recordedLocation != '')
    $HTMLBody = addContent($HTMLBody, "Event Recorded Location",
      makeHyper($recordedLocation, 'locations', $recordedLocationID,
		$hyperMode));
  $resultForSearch = mysql_query(
    "SELECT shorttitle FROM data_law WHERE id = $law_id");
  if ($rowItemsInDB = mysql_fetch_object($resultForSearch))
    $HTMLBody = addContent($HTMLBody, "Law",
      makeHyper($rowItemsInDB->shorttitle, 'laws', $law_id, $hyperMode));
  return $HTMLBody;
}

function addAssertionPersonToHTML($rowItemsInDB, $HTMLBody, $hyperMode) {
  $HTMLBody = addPersonTypeToHTML($rowItemsInDB->id, $HTMLBody,
    $hyperMode);
  if ($rowItemsInDB->name_detail != '')
    $HTMLBody = addContent($HTMLBody, "Name Detail",
      $rowItemsInDB->name_detail);
  if ($rowItemsInDB->description != '')
    $HTMLBody = addContent($HTMLBody, "Description",
      $rowItemsInDB->description);
  return addAssertionSourceToHTML($rowItemsInDB,
    addPersonOccupationToHTML($rowItemsInDB->id,
      addPersonStatusToHTML($rowItemsInDB->id,
        addPersonRelativesToHTML($rowItemsInDB, $HTMLBody, $hyperMode),
	$hyperMode),
      $hyperMode),
    $hyperMode);
}

function addAssertionPreternaturalToHTML($rowItemsInDB, $HTMLBody, $hyperMode) {
  $HTMLBody = addPreternaturalFoodToHTML($rowItemsInDB->id,
    addPreternaturalFunctionToHTML($rowItemsInDB->id, $HTMLBody,
      $hyperMode),
    $hyperMode);
  if ($rowItemsInDB->container != '')
    $HTMLBody = addContent($HTMLBody, "Container",
      $rowItemsInDB->container);
  $HTMLBody = addAssertionSourceToHTML($rowItemsInDB,
    addPreternaturalOwnedUsedSharedWith($rowItemsInDB->id,
      addPreternaturalInheritedFromToHTML($rowItemsInDB->source_id,
	addPreternaturalModeOfContactToHTML($rowItemsInDB->id,
          addPreternaturalFormToHTML($rowItemsInDB->id, $HTMLBody,
            $hyperMode),
	  $hyperMode),
        $hyperMode),
      $hyperMode),
    $hyperMode);
  if ($rowItemsInDB->relation_type != '')
    $HTMLBody = addContent($HTMLBody, "Relation Type",
      $rowItemsInDB->relation_type);
  return $HTMLBody;
}

function addPersonRelativesToHTML($assertionID, $HTMLBody, $hyperMode) {
  $recordedRelationship = returnRelationType($assertionID->personal_relationship);
  $HTMLBody = addBR($HTMLBody);
  if ($recordedRelationship != '')
    $HTMLBody = addContent($HTMLBody,
      "Personal relationship asserted in text", $recordedRelationship);
  $resultForSearch = mysql_query(
    "SELECT data_person.id, data_person.preferred_name
     FROM data_person INNER JOIN Relationship
     ON data_person.id = Relationship.person_id 
     WHERE Relationship.personassertion_id = " . $assertionID->id);
  if (mysql_num_rows($resultForSearch) > 0) {
    $tempBody = '';
    while ($rowPeopleItemsInDB = mysql_fetch_object($resultForSearch)) {
      if ($tempBody != '') $tempBody .= ', ';
      $tempBody .= makeHyper($rowPeopleItemsInDB->preferred_name,
        'people', $rowPeopleItemsInDB->id, $hyperMode);
    }
    $HTMLBody = addBR(addContent($HTMLBody, "Known kin group ", $tempBody));
  }
  return $HTMLBody;
}

function addToHTML($query, $property, $name, $hyperMode, $contentStr, $HTMLBody) {
  $resultForSearch = mysql_query($query);
  $tempBody = '';
  while ($rowItemsInDB = mysql_fetch_object($resultForSearch)) {
    if ($tempBody != '') $tempBody .= ', ';
    $tempBody .= makeHyper($rowItemsInDB->$property, $name,
			   $rowItemsInDB->id, $hyperMode);
  }
  return addContent($HTMLBody, $contentStr, $tempBody);
}

function addPersonTypeToHTML($assertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_persontype.id, data_persontype.person_type
     FROM data_persontype INNER JOIN Persontype
     ON data_persontype.id = Persontype.persontype_id
     WHERE Persontype.personassertion_id = $assertionID",
    'person_type', 'personType',
    $hyperMode, 'Person Type ', $HTMLBody);
}

function addPersonStatusToHTML($assertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_personstatus.id, data_personstatus.person_status
     FROM data_personstatus INNER JOIN Personstatus
     ON data_personstatus.id = Personstatus.personstatus_id
     WHERE Personstatus.personassertion_id = $assertionID",
    'person_status', 'personStatus',
    $hyperMode, 'Person Status ', $HTMLBody);
}

function addPersonOccupationToHTML($assertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_personoccup.id, data_personoccup.person_occup
     FROM data_personoccup INNER JOIN Personoccup
     ON data_personoccup.id = Personoccup.personoccup_id
     WHERE Personoccup.personassertion_id = $assertionID",
    'person_occup', 'personOccup',
    $hyperMode, 'Person Occupation ', $HTMLBody);
}

function addPreternaturalTypeToHTML($assertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_magicalbeingtype.id,
       data_magicalbeingtype.magicalbeing_type
     FROM data_magicalbeingtype INNER JOIN Magicalbeingtype
     ON data_magicalbeingtype.id = Magicalbeingtype.magicalbeingtype_id
     WHERE Magicalbeingtype.magicalbeing_id = $assertionID",
    'magicalbeing_type', 'preternaturalType',
    $hyperMode, 'Preternatural Type ', $HTMLBody);
}

function addPreternaturalFunctionToHTML($preternaturalAssertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_preternaturalfunction.id,
       data_preternaturalfunction.preternatural_function
     FROM data_preternaturalfunction INNER JOIN Preternaturalfunction
     ON data_preternaturalfunction.id = Preternaturalfunction.preternaturalfunction_id
     WHERE Preternaturalfunction.magicalbeingassertion_id = $preternaturalAssertionID",
    'preternatural_function', 'preternaturalFunction',
    $hyperMode, 'Preternatural Function ', $HTMLBody);
}

function addPreternaturalFoodToHTML($preternaturalAssertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_preternaturalfood.id, data_preternaturalfood.preternatural_food
     FROM data_preternaturalfood INNER JOIN Preternaturalfood
     ON data_preternaturalfood.id = Preternaturalfood.preternaturalfood_id
     WHERE Preternaturalfood.magicalbeingassertion_id = $preternaturalAssertionID",
    'preternatural_food', 'preternaturalFedWith',
    $hyperMode, 'Preternatural Fed/Paid With ', $HTMLBody);
}

function addPreternaturalFormToHTML($preternaturalAssertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_perternaturalform.id, data_perternaturalform.preternatural_form
     FROM data_perternaturalform INNER JOIN Perternaturalform
     ON data_perternaturalform.id = Perternaturalform.perternaturalform_id
     WHERE Perternaturalform.magicalbeingassertion_id = $preternaturalAssertionID",
    'preternatural_form', 'preternaturalForm',
    $hyperMode, 'Preternatural Form ', $HTMLBody);
}

function addPreternaturalModeOfContactToHTML($preternaturalAssertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_preternaturalmodeofcontact.id, data_preternaturalmodeofcontact.mode_of_contact
     FROM data_preternaturalmodeofcontact INNER JOIN Preternaturalmodofcontact
     ON data_preternaturalmodeofcontact.id = Preternaturalmodofcontact.preternaturalmodeofcontact_id
     WHERE Preternaturalmodofcontact.magicalbeingassertion_id = $preternaturalAssertionID",
    'mode_of_contact', 'preternaturalMOC',
    $hyperMode, 'Preternatural Mode of Contact ', $HTMLBody);
}

function addPreternaturalInheritedFromToHTML($personID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT id, preferred_name FROM data_person WHERE id = $personID",
    'preferred_name', 'people',
    $hyperMode, 'Recieved or Inherited From: ', $HTMLBody);
}

function addPreternaturalOwnedUsedSharedWith($preternaturalAssertionID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_person.id, data_person.preferred_name
     FROM data_person INNER JOIN Preternatualassertionusedagainst
     ON data_person.id = Preternatualassertionusedagainst.person_id
     WHERE Preternatualassertionusedagainst.magicalbeingassertion_id = $preternaturalAssertionID
     ORDER BY data_person.preferred_name",
    'preferred_name', 'people',
    $hyperMode, 'Preternatural Used Against ',
    addToHTML(
      "SELECT data_person.id, data_person.preferred_name
       FROM data_person INNER JOIN Preternatualassertionsharedwith
       ON data_person.id = Preternatualassertionsharedwith.person_id
       WHERE Preternatualassertionsharedwith.magicalbeingassertion_id = $preternaturalAssertionID
       ORDER BY data_person.preferred_name",
      'preferred_name', 'people',
      $hyperMode, 'Preternatural Shared With ',
      addToHTML(
        "SELECT data_person.id, data_person.preferred_name
         FROM data_person INNER JOIN Preternatualassertionownedby
         ON data_person.id = Preternatualassertionownedby.person_id
         WHERE Preternatualassertionownedby.magicalbeingassertion_id = $preternaturalAssertionID
         ORDER BY data_person.preferred_name",
	'preferred_name', 'people',
	$hyperMode, 'Preternatural Owned By ', $HTMLBody)));
}

function addPersonLocationsToHTML($personID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_location.id, data_location.preferred_name
     FROM data_location INNER JOIN Personlocation
     ON data_location.id = Personlocation.location_id
     WHERE Personlocation.person_id = $personID",
    'preferred_name', 'locations',
    $hyperMode, "Person Location(s) ", $HTMLBody);
}

function addPreternaturalLocationsToHTML($preternaturalID, $HTMLBody, $hyperMode) {
  return addToHTML(
    "SELECT data_location.id, data_location.preferred_name
     FROM data_location INNER JOIN Preternatuallocation
     ON data_location.id = Preternatuallocation.location_id
     WHERE Preternatuallocation.magicalbeing_id = $preternaturalID",
    'preferred_name', 'locations',
    $hyperMode, "Preternatural Location(s) ", $HTMLBody);
}

function cleansFromStrangeCharacters($value) {
  $value = htmlentities($value);
  $value = preg_replace('/[^(\x20-\x7F)]*/','', $value);
  return $value;
}
?>
