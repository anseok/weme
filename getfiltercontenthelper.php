<?php
// So we can invoke whatever's requiring this file with either GET or POST.  We copy each arg into a global variable with a similar name, or set a default value if an arg was not
// passed.
foreach (array('0' => array('startYear' => 'startYear',
			    'endYear' => 'endYear'),
	       'No' => array('tieToTimeline' => 'tieToTimeline'),
	       'NO' => array('witchlistAnonym' => 'witchlistAnonym'),
	       'OR' => array('andorchice' => 'andorchice',
			     'mbeinglistAnonym' => 'mbeinglistAnonym',
			     'firstauthorlistAnonym' => 'firstauthorlistAnonym'),
	       '-1_' => array('filterText' => 'firsttitleList',
			      'filterSecondaryText' => 'secondtitleList',
			      'filterThirdText' => 'thirdtitleList',
			      'filterWitch' => 'witchlist',
			      'filterMbeing' => 'mbeinglist',
			      'filterLocation' => 'locationlist',
			      'filterEvent' => 'eventTypelist',
			      'filterAuthor' => 'firstauthorlist',
			      'filterAuthorSec' => 'secondauthorlist',
			      'filterAuthorThird' => 'thirdauthorlist',
			      'filterPersonType' => 'personTypelist',
			      'filterPreterType' => 'preternaturalTypelist'),
	       'Throw' => array('throwOrHTMLF' => 'throwOrHTMLF'),
	       'kjhiputyihjhfuytio987jk' => array('keywordsearch' => 'keywordsearch'))
	 as $default => $a)
  foreach ($a as $lhs => $rhs)
    $GLOBALS[$lhs] = isset($_REQUEST[$rhs]) ? $_REQUEST[$rhs] : (string) $default;

require_once("/home/weme-dev/public_html/connectdb.php");
require_once("throwingbones.php");

$keywordsearch = mysql_real_escape_string($keywordsearch);

foreach (array('Text', 'SecondaryText', 'ThirdText', 'Witch', 'Mbeing',
	       'Location', 'Event', 'Author', 'AuthorSec',
	       'AuthorThird', 'PersonType', 'PreterType') as $s) {
  $p = 'filter' . $s;
  $GLOBALS[$p . 'Array'] = explode('_', $GLOBALS[$p]);
}

foreach (array('Text', 'SecText', 'ThirdText', 'Witch', 'Mbeing',
	       'Location', 'Event', 'Author', 'AuthorSec',
	       'AuthorThird', 'PersonType', 'PreterType', 'Keyword1',
	       'Keyword2', 'Keyword3', 'Keyword4', 'ALL',
	       'KeywordResult', 'Result', 'NewKeyword', 'PersonAnon',
	       'MbeingAnon', 'FirstAuthAnon') as $s)
  $GLOBALS['assertion' . $s . 'Array'] = array();

$i = 0;

$textSQLWhereClause = "";
if ((count($filterTextArray) > 1) && ($filterTextArray[0] != -1))
  for ($i = 0; $i < count($filterTextArray)-1 ; $i++){
    if ($i > 0) $textSQLWhereClause .= " OR ";
    $textSQLWhereClause .= "(in_source_id = " .  $filterTextArray[$i] . ")";
  }
else
  $textSQLWhereClause = "1 = 1";
$textQuery = "SELECT data_eventassertion.id FROM data_eventassertion WHERE ($textSQLWhereClause) ORDER BY data_eventassertion.id ASC";


	$textSQLWhereClause = "";
	if ((count($filterSecondaryTextArray) > 1) && ($filterSecondaryTextArray[0] != -1))
		for ($i = 0; $i < count($filterSecondaryTextArray)-1 ; $i++){
			if ($i > 0) $textSQLWhereClause .= " OR ";
			$textSQLWhereClause .= "(in_source_id = " .  $filterSecondaryTextArray[$i] . ")";
			$textSQLWhereClause .= " OR (in_source2_id = " .  $filterSecondaryTextArray[$i] . ")";
			$textSQLWhereClause .= " OR (in_source3_id = " .  $filterSecondaryTextArray[$i] . ")";
		}
	else
		$textSQLWhereClause = "1 = 1";
	$sectextQuery = "SELECT data_eventassertion.id FROM data_eventassertion WHERE ($textSQLWhereClause) ORDER BY data_eventassertion.id ASC";


	$textSQLWhereClause = "";
	if ((count($filterThirdTextArray) > 1) && ($filterThirdTextArray[0] != -1))
		for ($i = 0; $i < count($filterThirdTextArray)-1 ; $i++){
			if ($i > 0) $textSQLWhereClause .= " OR ";
			$textSQLWhereClause .= "(in_source_id = " .  $filterThirdTextArray[$i] . ")";
			$textSQLWhereClause .= " OR (in_source2_id = " .  $filterThirdTextArray[$i] . ")";
			$textSQLWhereClause .= " OR (in_source3_id = " .  $filterThirdTextArray[$i] . ")";
		}
	else
		$textSQLWhereClause = "1 = 1";
	$thirdtextQuery = "SELECT data_eventassertion.id FROM data_eventassertion WHERE ($textSQLWhereClause) ORDER BY data_eventassertion.id ASC";


	$witchSQLWhereClause = "";
	if ((count($filterWitchArray) > 1) && ($filterWitchArray[0] != -1))
		for ($i = 0; $i < count($filterWitchArray)-1 ; $i++){
			if ($i > 0) $witchSQLWhereClause .= " OR ";
			$witchSQLWhereClause .= "(Participates.person_id = " .  $filterWitchArray[$i] . ")";
		}
	else
		$witchSQLWhereClause = "1 = 1";
	$witchQuery = "SELECT data_eventassertion.id FROM data_eventassertion JOIN Participates ON data_eventassertion.id = Participates.eventassertion_id WHERE 
		($witchSQLWhereClause) ORDER BY data_eventassertion.id ASC";


	$mbeingSQLWhereClause = "";
	if ((count($filterMbeingArray) > 1) && ($filterMbeingArray[0] != -1))
		for ($i = 0; $i < count($filterMbeingArray)-1 ; $i++){
			if ($i > 0) $mbeingSQLWhereClause .= " OR ";
			$mbeingSQLWhereClause .= "(magicalbeing_id = " .  $filterMbeingArray[$i] . ")";
		}
	else
		$mbeingSQLWhereClause = "1 = 1";
	$mbeingQuery = "SELECT data_eventassertion.id FROM data_eventassertion JOIN Intervenes ON data_eventassertion.id = Intervenes.eventassertion_id WHERE 
		($mbeingSQLWhereClause) ORDER BY data_eventassertion.id ASC";

	$locationSQLWhereClause = "";
	if ((count($filterLocationArray) > 1) && ($filterLocationArray[0] != -1))
		for ($i = 0; $i < count($filterLocationArray)-1 ; $i++){
			if ($i > 0) $locationSQLWhereClause .= " OR ";
			$locationSQLWhereClause .= "(location_id = " .  $filterLocationArray[$i] . ")";
		}
	else
		$locationSQLWhereClause = "1 = 1";
	$locationQuery = "SELECT data_eventassertion.id FROM data_eventassertion WHERE ($locationSQLWhereClause) ORDER BY data_eventassertion.id ASC";

	$eventSQLWhereClause = "";
	if ((count($filterEventArray) > 1) && ($filterEventArray[0] != -1))
		for ($i = 0; $i < count($filterEventArray)-1 ; $i++){
			if ($i > 0) $eventSQLWhereClause .= " OR ";
			$eventSQLWhereClause .= "(eventtype_id = '" .  $filterEventArray[$i] . "')";
		}
	else
		$eventSQLWhereClause = "1 = 1";
	$eventQuery = "SELECT data_eventassertion.id FROM data_eventassertion,Eventtype WHERE ((Eventtype.eventassertion_id=data_eventassertion.id)AND($eventSQLWhereClause)) ORDER BY data_eventassertion.id ASC";

	$authorSQLWhereClause = "";
	if ((count($filterAuthorArray) > 1) && ($filterAuthorArray[0] != -1))
		for ($i = 0; $i < count($filterAuthorArray)-1 ; $i++){
			if ($i > 0) $authorSQLWhereClause .= " OR ";
			$authorSQLWhereClause .= "(data_source.author_id = '" .  $filterAuthorArray[$i] . "')";
		}
	else
		$authorSQLWhereClause = "1 = 1";
	$authorQuery = "SELECT data_eventassertion.id FROM data_eventassertion JOIN data_source ON data_source.id = data_eventassertion.in_source_id WHERE 
		($authorSQLWhereClause)";


	$authorSQLWhereClause = "";
	if ((count($filterAuthorSecArray) > 1) && ($filterAuthorSecArray[0] != -1))
		for ($i = 0; $i < count($filterAuthorSecArray)-1 ; $i++){
			if ($i > 0) $authorSQLWhereClause .= " OR ";
			$authorSQLWhereClause .= "(data_source.author_id = '" .  $filterAuthorSecArray[$i] . "')";
		}
	else
		$authorSQLWhereClause = "1 = 1";
	$authorSecQuery = "SELECT data_eventassertion.id FROM data_eventassertion JOIN data_source ON data_source.id = data_eventassertion.in_source_id WHERE 
		($authorSQLWhereClause)";


	$authorSQLWhereClause = "";
	if ((count($filterAuthorThirdArray) > 1) && ($filterAuthorThirdArray[0] != -1))
		for ($i = 0; $i < count($filterAuthorThirdArray)-1 ; $i++){
			if ($i > 0) $authorSQLWhereClause .= " OR ";
			$authorSQLWhereClause .= "(data_source.author_id = '" .  $filterAuthorThirdArray[$i] . "')";
		}
	else
		$authorSQLWhereClause = "1 = 1";
	$authorThirdQuery = "SELECT data_eventassertion.id FROM data_eventassertion JOIN data_source ON data_source.id = data_eventassertion.in_source_id WHERE 
		($authorSQLWhereClause)";


	$personTypeSQLWhereClause = "";
	if ((count($filterPersonTypeArray) > 1) && ($filterPersonTypeArray[0] != -1))
		for ($i = 0; $i < count($filterPersonTypeArray)-1 ; $i++){
			if ($i > 0) $personTypeSQLWhereClause .= " OR ";
			$personTypeSQLWhereClause .= "(Persontype.persontype_id = '" .  $filterPersonTypeArray[$i] . "')";
		}
	else
		$personTypeSQLWhereClause = "1 = 1";
	$personTypeQuery = "
				SELECT Participates.eventassertion_id
				FROM Participates,data_personassertion,Persontype
				WHERE(($personTypeSQLWhereClause) AND(Persontype.personassertion_id=data_personassertion.id)AND(Participates.person_id = data_personassertion.refers_to_id))
				ORDER BY Participates.eventassertion_id ASC
				";



	$preterTypeSQLWhereClause = "";
	if ((count($filterPreterTypeArray) > 1) && ($filterPreterTypeArray[0] != -1))
		for ($i = 0; $i < count($filterPreterTypeArray)-1 ; $i++){
			if ($i > 0) $preterTypeSQLWhereClause .= " OR ";
			$preterTypeSQLWhereClause .= "(Magicalbeingtype.magicalbeingtype_id = '" .  $filterPreterTypeArray[$i] . "')";
		}
	else
		$preterTypeSQLWhereClause = "1 = 1";

	$preterTypeQuery = "
				SELECT Intervenes.eventassertion_id 
				FROM Intervenes,Magicalbeingtype
				WHERE(($preterTypeSQLWhereClause) AND(Magicalbeingtype.magicalbeing_id=Intervenes.magicalbeing_id))
				ORDER BY Intervenes.eventassertion_id ASC
				";



	$keywordsearchSplitted = split('"', stripslashes(strtolower($keywordsearch)));
	$keyWordClause = "";
	$i = 0;
	for ($i = 0; $i < count($keywordsearchSplitted); $i++)
		if ($i % 2){
			$trimmedkeyword = trim($keywordsearchSplitted[$i]);
			if ($trimmedkeyword != ""){
				$keyWordClause .= ($keyWordClause == "")? "": "OR";
				$keyWordClause .= "(textbody like '%$trimmedkeyword%')";
			}
		}else{
			$trimmedsplitkeyword = split(" ", $keywordsearchSplitted[$i]);
			for ($j = 0; $j < count($trimmedsplitkeyword); $j++){
				$trimmedkeyword = trim($trimmedsplitkeyword[$j]);
				if ($trimmedkeyword != ""){
					$keyWordClause .= ($keyWordClause == "")? "": "OR";
					$keyWordClause .= "(textbody like '%$trimmedkeyword%')";
				}
			}
		}
	$newKeywordSearchQuery	= "SELECT eventsearchindex.eventassertion_id FROM eventsearchindex WHERE $keyWordClause;";


	if ($witchlistAnonym == "Yes")
		$personAnonymQuery = "	SELECT Participates.eventassertion_id FROM Participates, 
						(SELECT `data_person`.id FROM  `data_person` WHERE preferred_name LIKE  '%Anonymous%') as anonymtable
					WHERE(Participates.person_id = anonymtable.id)
					ORDER BY Participates.eventassertion_id ASC
					";
	else
		$personAnonymQuery = "	SELECT data_eventassertion.id FROM data_eventassertion WHERE 0=1";


	if ($mbeinglistAnonym == "Yes")
		$mbeingAnonymQuery = "	SELECT data_eventassertion.id FROM data_eventassertion, Intervenes,
						(SELECT * FROM  `data_magicalbeing` WHERE name LIKE  '%Anonymous%') as anonymtable
 					WHERE ((data_eventassertion.id = Intervenes.eventassertion_id) AND (anonymtable.id = Intervenes.magicalbeing_id))
 					ORDER BY data_eventassertion.id ASC
					";
	else
		$mbeingAnonymQuery = "	SELECT data_eventassertion.id FROM data_eventassertion WHERE 0=1";

	if ($firstauthorlistAnonym == "Yes")
		$firstAuthorAnonymQuery = "	
					SELECT data_eventassertion.id FROM data_eventassertion, data_source, data_author
					WHERE ((data_source.source_item_type = 'p') AND (data_author.id = data_source.author_id) AND 
						((data_eventassertion.in_source_id = data_source.id) OR
						(data_eventassertion.in_source2_id = data_source.id) OR
						(data_eventassertion.in_source3_id = data_source.id) OR 
						(data_eventassertion.in_source4_id = data_source.id) OR 
						(data_eventassertion.in_source5_id = data_source.id) OR 
						(data_eventassertion.in_source6_id = data_source.id) OR 
						(data_eventassertion.in_source7_id = data_source.id) OR 
						(data_eventassertion.in_source8_id = data_source.id) OR 
						(data_eventassertion.in_source9_id = data_source.id) OR 
						(data_eventassertion.in_source10_id = data_source.id) OR 
						(data_eventassertion.in_source11_id = data_source.id) OR 
						(data_eventassertion.in_source12_id = data_source.id) OR 
						(data_eventassertion.in_source13_id = data_source.id))
						AND (data_author.last_name like '%Anonymous%'))
 					ORDER BY data_eventassertion.id ASC
					";
	else
		$firstAuthorAnonymQuery = "	SELECT data_eventassertion.id FROM data_eventassertion WHERE 0=1";


	$allQuery = "SELECT data_eventassertion.id FROM data_eventassertion ORDER BY data_eventassertion.id ASC";

	$textResult 		= mysql_query($textQuery);
	$sectextResult 		= mysql_query($sectextQuery);
	$thirdtextResult 	= mysql_query($thirdtextQuery);
	$witchResult 		= mysql_query($witchQuery);
	$mbeingResult 		= mysql_query($mbeingQuery);
	$locatResult 		= mysql_query($locationQuery);
	$eventResult 		= mysql_query($eventQuery);
	$authorResult 		= mysql_query($authorQuery);
	$authorSecResult 	= mysql_query($authorSecQuery);
	$authorThirdResult 	= mysql_query($authorThirdQuery);
	$personTypeResult 	= mysql_query($personTypeQuery);
	$preterTypeResult 	= mysql_query($preterTypeQuery);
	$newkeywordResult 	= mysql_query($newKeywordSearchQuery);
	$personAnonymResult	= mysql_query($personAnonymQuery);
	$mbeingAnonymResult	= mysql_query($mbeingAnonymQuery);
	$firstAuthAnonymResult	= mysql_query($firstAuthorAnonymQuery);
	$allResult 		= mysql_query($allQuery);


	$i = 0;
	while ($textRow = mysql_fetch_object($textResult)){
		$assertionTextArray[$i++] = $textRow->id ; 
	}

	$i = 0;
	while ($sectextRow = mysql_fetch_object($sectextResult)){
		$assertionSecTextArray[$i++] = $sectextRow->id ; 
	}

	$i = 0;
	while ($thirdtextRow = mysql_fetch_object($thirdtextResult)){
		$assertionThirdTextArray[$i++] = $thirdtextRow->id ; 
	}

	$i = 0;
	while ($witchRow = mysql_fetch_object($witchResult)){
		$assertionWitchArray[$i++] = $witchRow->id ; 
	}

	$i = 0;
	while ($mbeingRow = mysql_fetch_object($mbeingResult)){
		$assertionMbeingArray[$i++] = $mbeingRow->id ; 
	}

	$i = 0;
	while ($localRow = mysql_fetch_object($locatResult)){
		$assertionLocationArray[$i++] = $localRow->id ; 
	}

	$i = 0;
	while ($eventRow = mysql_fetch_object($eventResult)){
		$assertionEventArray[$i++] = $eventRow->id ; 
	}

	$i = 0;
	while ($authorRow = mysql_fetch_object($authorResult)){
		$assertionAuthorArray[$i++] = $authorRow->id ; 
	}

	$i = 0;
	while ($authorRow = mysql_fetch_object($authorSecResult)){
		$assertionAuthorSecArray[$i++] = $authorRow->id ; 
	}

	$i = 0;
	while ($authorRow = mysql_fetch_object($authorThirdResult)){
		$assertionAuthorThirdArray[$i++] = $authorRow->id ; 
	}

	$i = 0;
	while ($eventRow = mysql_fetch_object($personTypeResult)){
		$assertionPersonTypeArray[$i++] = $eventRow->eventassertion_id ; 
	}

	$i = 0;
	while ($eventRow = mysql_fetch_object($preterTypeResult)){
		$assertionPreterTypeArray[$i++] = $eventRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($newkeywordResult)){
		$assertionNewKeywordArray[$i++] = $allRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($personAnonymResult)){
		$assertionPersonAnonArray[$i++] = $allRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($mbeingAnonymResult)){
		$assertionMbeingAnonArray[$i++] = $allRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($firstAuthAnonymResult)){
		$assertionFirstAuthAnonArray[$i++] = $allRow->eventassertion_id ; 
	}

//////////////////////////
	$i = 0;
	while ($allRow = mysql_fetch_object($newkeywordResult)){
		$assertionNewKeywordArray[$i++] = $allRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($newkeywordResult)){
		$assertionNewKeywordArray[$i++] = $allRow->eventassertion_id ; 
	}

	$i = 0;
	while ($allRow = mysql_fetch_object($newkeywordResult)){
		$assertionNewKeywordArray[$i++] = $allRow->eventassertion_id ; 
	}



	$i = 0;
	while ($allRow = mysql_fetch_object($allResult)){
		$assertionALLArray[$i++] = $allRow->id ; 
	}

	/*Starts with Event type and refine it further.*/
	$assertionResultArray[0] = -1;
	$assertionKeywordResultArray[0] = -1;

	if($andorchice == "OR"){

		if ((count($filterTextArray) > 1) && ($filterTextArray[0] != -1))
			orWithArray($assertionResultArray, $assertionTextArray);

		if ((count($filterSecondaryTextArray) > 1) && ($filterSecondaryTextArray[0] != -1))
			orWithArray($assertionResultArray, $assertionSecTextArray);

		if ((count($filterThirdTextArray) > 1) && ($filterThirdTextArray[0] != -1))
			orWithArray($assertionResultArray, $assertionThirdTextArray);

		if ((count($filterWitchArray) > 1) && ($filterWitchArray[0] != -1))
			orWithArray($assertionResultArray, $assertionWitchArray);

		if ((count($filterMbeingArray) > 1) && ($filterMbeingArray[0] != -1))
			orWithArray($assertionResultArray, $assertionMbeingArray);

		if ((count($filterLocationArray) > 1) && ($filterLocationArray[0] != -1))
			orWithArray($assertionResultArray, $assertionLocationArray);

		if ((count($filterEventArray) > 1) && ($filterEventArray[0] != -1))
			orWithArray($assertionResultArray, $assertionEventArray);

		if ((count($filterAuthorArray) > 1) && ($filterAuthorArray[0] != -1))
			orWithArray($assertionResultArray, $assertionAuthorArray);

		if ((count($filterAuthorSecArray) > 1) && ($filterAuthorSecArray[0] != -1))
			orWithArray($assertionResultArray, $assertionAuthorSecArray);

		if ((count($filterAuthorThirdArray) > 1) && ($filterAuthorThirdArray[0] != -1))
			orWithArray($assertionResultArray, $assertionAuthorThirdArray);

		if ((count($filterPersonTypeArray) > 1) && ($filterPersonTypeArray[0] != -1))
			orWithArray($assertionResultArray, $assertionPersonTypeArray);

		if ((count($filterPreterTypeArray) > 1) && ($filterPreterTypeArray[0] != -1))
			orWithArray($assertionResultArray, $assertionPreterTypeArray);

		if ((count($assertionNewKeywordArray) > 1) && ($assertionNewKeywordArray[0] != -1))
			orWithArray($assertionResultArray, $assertionNewKeywordArray);

		if (count($assertionPersonAnonArray) > 1)
			orWithArray($assertionResultArray, $assertionPersonAnonArray);

		if (count($assertionPersonAnonArray) > 1)
			orWithArray($assertionResultArray, $assertionMbeingAnonArray);

		if (count($assertionPersonAnonArray) > 1)
			orWithArray($assertionResultArray, $assertionFirstAuthAnonArray);

	}else{

		if ((count($filterTextArray) > 1) && ($filterTextArray[0] != -1))
			andWithArray($assertionResultArray, $assertionTextArray);

		if ((count($filterSecondaryTextArray) > 1) && ($filterSecondaryTextArray[0] != -1))
			andWithArray($assertionResultArray, $assertionSecTextArray);

		if ((count($filterThirdTextArray) > 1) && ($filterThirdTextArray[0] != -1))
			andWithArray($assertionResultArray, $assertionThirdTextArray);

		if ((count($filterWitchArray) > 1) && ($filterWitchArray[0] != -1))
			andWithArray($assertionResultArray, $assertionWitchArray);

		if ((count($filterMbeingArray) > 1) && ($filterMbeingArray[0] != -1))
			andWithArray($assertionResultArray, $assertionMbeingArray);

		if ((count($filterLocationArray) > 1) && ($filterLocationArray[0] != -1))
			andWithArray($assertionResultArray, $assertionLocationArray);

		if ((count($filterEventArray) > 1) && ($filterEventArray[0] != -1))
			andWithArray($assertionResultArray, $assertionEventArray);

		if ((count($filterAuthorArray) > 1) && ($filterAuthorArray[0] != -1))
			andWithArray($assertionResultArray, $assertionAuthorArray);

		if ((count($filterAuthorSecArray) > 1) && ($filterAuthorSecArray[0] != -1))
			andWithArray($assertionResultArray, $assertionAuthorSecArray);

		if ((count($filterAuthorThirdArray) > 1) && ($filterAuthorThirdArray[0] != -1))
			andWithArray($assertionResultArray, $assertionAuthorThirdArray);

		if ((count($filterPersonTypeArray) > 1) && ($filterPersonTypeArray[0] != -1))
			andWithArray($assertionResultArray, $assertionPersonTypeArray);

		if ((count($filterPreterTypeArray) > 1) && ($filterPreterTypeArray[0] != -1))
			andWithArray($assertionResultArray, $assertionPreterTypeArray);

		if ((count($assertionNewKeywordArray) > 1) && ($assertionNewKeywordArray[0] != -1))
			andWithArray($assertionResultArray, $assertionNewKeywordArray);

		if (count($assertionPersonAnonArray) > 1)
			andWithArray($assertionResultArray, $assertionPersonAnonArray);

		if (count($assertionPersonAnonArray) > 1)
			andWithArray($assertionResultArray, $assertionMbeingAnonArray);

		if (count($assertionPersonAnonArray) > 1)
			andWithArray($assertionResultArray, $assertionFirstAuthAnonArray);

	}

	if ($tieToTimeline == "Yes"){

		$query = "SELECT data_eventassertion.id FROM data_eventassertion
			WHERE ((data_eventassertion.start_date >=  '$startYear') AND 
			(data_eventassertion.start_date <=  '$endYear') AND (data_eventassertion.id <>  ''))
			ORDER BY data_eventassertion.start_date ASC";

		$yearResult 	= mysql_query($query);

		$i = 0;
		while ($yearRow = mysql_fetch_object($yearResult)){
			$assertionYearArray[$i++] = $yearRow->id ; 
		}

		if ((count($assertionYearArray) > 1) && ($assertionYearArray[0] != -1))
			andWithArray($assertionResultArray, $assertionYearArray);

	}

	$assertionResultArrayUnique = array();
	$assertionResultArrayUnique = array_unique($assertionResultArray);

	sort($assertionResultArray);
	if (count($assertionResultArray))
		$assertionResultArrayUnique[0] = $assertionResultArray[0];


	$j = 0;
	for($i = 1; $i < count($assertionResultArray); $i++){
		if ($assertionResultArray[$i] != $assertionResultArrayUnique[$j])
			$assertionResultArrayUnique[++$j] = $assertionResultArray[$i];
	}


function orWithArray(&$assertionResultArray, $assertionToBeOredArray){

	$i = 0;
	if ($assertionResultArray[0] == -1){
		for($i = 0; $i < count($assertionToBeOredArray); $i++){
			$assertionResultArray[$i] = $assertionToBeOredArray[$i];
		}
	}else{
		$assertionResultArray = array_unique(array_merge($assertionResultArray, $assertionToBeOredArray));
	}
}

function andWithArray(&$assertionResultArray, $assertionToBeOredArray){

	$i = 0;
	if ($assertionResultArray[0] == -1){
		for($i = 0; $i < count($assertionToBeOredArray); $i++){
			$assertionResultArray[$i] = $assertionToBeOredArray[$i];
		}
	}else{
		$assertionResultArray = array_unique(array_intersect($assertionResultArray, $assertionToBeOredArray));
	}
}

?>
