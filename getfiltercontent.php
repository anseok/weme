<?php

	require_once('getfiltercontenthelper.php');


	$i = 0;

	if ($throwOrHTMLF != "Fulltext"){

		if (count($assertionResultArray) > 0)
 			generateDecks($assertionResultArrayUnique, count($assertionResultArrayUnique));

	}else{

		if ($keywordsearch != ""){

			$PDFArray = array();
			$sourceArray = array();

			$searchdatasource = "SELECT data_source . * , data_author.first_name, data_author.last_name FROM data_source
								LEFT JOIN data_author ON data_source.author_id = data_author.id"; 
			$resultdatasource = mysql_query($searchdatasource);

			while ($resultdatarow = mysql_fetch_object($resultdatasource)){

				$sourceArray[$resultdatarow->text_id] =  "<B>Title:</B> " . $resultdatarow->short_title . "<BR>". "<B>Author:</B> " . $resultdatarow->first_name . " " . $resultdatarow->last_name . "<BR>"; 

			}


			$keywordsearchSplitted = split('"', stripslashes(strtolower($keywordsearch)));
			$keyWordClause = "";

			for ($i = 0; $i < count($keywordsearchSplitted); $i++)
				if ($i % 2){
					$trimmedkeyword = trim($keywordsearchSplitted[$i]);
					if ($trimmedkeyword != ""){
						$keyWordClause .= ($keyWordClause == "")? "": "OR";
						$keyWordClause .= "(textlookuplines.textline like '%$trimmedkeyword%')";
					}
				}else{
					$trimmedsplitkeyword = split(" ", $keywordsearchSplitted[$i]);
					for ($j = 0; $j < count($trimmedsplitkeyword); $j++){
						$trimmedkeyword = trim($trimmedsplitkeyword[$j]);
						if ($trimmedkeyword != ""){
							$keyWordClause .= ($keyWordClause == "")? "": "OR";
							$keyWordClause .= "(textlookuplines.textline like '%$trimmedkeyword%')";
						}
					}
				}


			$newKeywordSearchQuery 		= "	SELECT textlookup.filename , selectedLines.textline FROM textlookup,  
									(SELECT textline, lineID
									FROM textlookuplines
									WHERE ($keyWordClause)) as selectedLines
								WHERE textlookup.lineID = selectedLines.lineID
								GROUP BY textlookup.lineID"; 



			$textSearchResult 	= mysql_query($newKeywordSearchQuery);
			$i = 0;
			while ($textSearchRow = mysql_fetch_object($textSearchResult)){

				if (isset($sourceArray[substr($textSearchRow->filename, 0, 6) . ".xml"]))
					$authorTitleText = $sourceArray[substr($textSearchRow->filename, 0, 6) . ".xml"];
				else
					$authorTitleText = "";
				$PDFArray[$i++] = array('File' => ($textSearchRow->filename), 'Text' => ($textSearchRow->textline), 'AuthorTitle' => $authorTitleText) ; 

			}

		}

		if ($i != 0)
	 		generatePDFs($PDFArray, count($PDFArray));

	}

	if ($i == 0 && (count($assertionResultArray) < 2))
		echo "<BR><H1>No results!</H1><BR>There is no record in our database that fulfills all speficied constraints.<BR>Please try a different combination.";

?>
