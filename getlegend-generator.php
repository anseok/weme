<?php 

	include "/home/weme-dev/public_html/connectdb.php";

	$numColumns = 6;

	$queryForSearch	 = "SELECT data_eventtype.*, data_eventtypeclassification.eventtype_classification FROM data_eventtype, data_eventtypeclassification 
				WHERE data_eventtypeclassification.id = data_eventtype.event_type_class ORDER BY eventtype_classification,event_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$legendEventBody = "";
	$j = 0;
	$widthString = "width='140px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		if ($currentClassification != $rowItemsInDB->eventtype_classification){
			$currentClassification = $rowItemsInDB->eventtype_classification;
			if ($legendEventBody != "")  $legendEventBody .= "</tr></table></fieldset>";
			$legendEventBody .= "</br><fieldset><legend>" . $rowItemsInDB->eventtype_classification . "</legend><table><tr>";
			$j = 0;
		}
		$legendEventBody .= "<td $widthString ALIGN='center'><img src='../throwing-bones-contents/thumbsroot/level10/" . $rowItemsInDB->id . ".png'/><BR><B>" . $rowItemsInDB->event_type . "</B></td>"; 
		$j++;
		if (($j % $numColumns) == 0) {
			$legendEventBody .= "</tr><tr>";
		}
	}
	$legendEventBody .= "</tr></table></fieldset>";


	$queryForSearch	 = "SELECT * FROM data_persontype ORDER BY person_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$legendPeopleBody = "<table><tr>";
	$j = 0;
	$widthString = "width='140px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$legendPeopleBody .= "<td $widthString ALIGN='center'><img src='../throwing-bones-contents/thumbsroot/level10/p" . $rowItemsInDB->id . ".png'/><BR><B>" . $rowItemsInDB->person_type . "</B></td>"; 
		$j++;
		if (($j % $numColumns) == 0)
			$legendPeopleBody .= "</tr><tr>";
	}
	$legendPeopleBody .= "</tr></table>";


	$queryForSearch	 = "SELECT * FROM data_magicalbeingtype ORDER BY magicalbeing_type ASC";
	$resultForSearch = mysql_query($queryForSearch);
	$legendPreternaturalBody = "<table><tr>";
	$j = 0;
	$widthString = "width='140px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$legendPreternaturalBody .= "<td $widthString ALIGN='center'><img src='../throwing-bones-contents/thumbsroot/level10/d" . $rowItemsInDB->id . ".png'/><BR><B>" . $rowItemsInDB->magicalbeing_type . "</B></td>"; 
		$j++;
		if (($j % $numColumns) == 0) 
			$legendPreternaturalBody .= "</tr><tr>";
	}
	$legendPreternaturalBody .= "</tr></table>";


	$queryForSearch	 = "SELECT * FROM  `data_law` ORDER BY  `data_law`.`year` ASC";


	$resultForSearch = mysql_query($queryForSearch);
	$legendOtherBody = "<fieldset><legend>Law</legend><table><tr>";
	$j = 0;
	$widthString = "width='140px'";
	$currentClassification = "-1";
	while  ($rowItemsInDB = mysql_fetch_object($resultForSearch)){
		$legendOtherBody .= "<td $widthString ALIGN='center' valign='top'><img src='../throwing-bones-contents/thumbsroot/level10/l" . $rowItemsInDB->id . ".png'/><BR><B>" . $rowItemsInDB->shorttitle . "</B></td>"; 
		$j++;
		if (($j % $numColumns) == 0)
			$legendOtherBody .= "</tr><tr>";
	}
	$legendOtherBody .= "</tr></table></fieldset>";




?>
<div class="legendwidthwrepper">
	<div class="legendHeader">
		<H2><i>Legend:</i></H2><br>
	</div>


	<div class="legendNavbarWrapper">
		<table cellpadding=10><tr>
			<td><a href='javascript:highlightlegenddiv("legenddivevent")'>Events</a></td>
			<td><a href='javascript:highlightlegenddiv("legenddivpeople")'>People</a></td>
			<td><a href='javascript:highlightlegenddiv("legenddivpreternatural")'>Preternatural Beings</a></td>
			<td><a href='javascript:highlightlegenddiv("legenddivother")'>Other</a></td>
		</table></tr>
	</div>

	<div class="legendItemWrapper">
		<div id="legenddivevent" class="legendItem">
			<?php echo $legendEventBody ?>
		</div>
		<div id="legenddivpeople" class="legendItem" style="display : none;">
			<?php echo $legendPeopleBody ?>
		</div>
		<div id="legenddivpreternatural" class="legendItem" style="display : none;">
			<?php echo $legendPreternaturalBody ?>
		</div>
		<div id="legenddivother" class="legendItem" style="display : none;">
			<?php echo $legendOtherBody ?>
			<fieldset><legend>Other</legend>
				<table><tr>
					<td width='140px' ALIGN='center'>
						<img src='../throwing-bones-contents/thumbsroot/level10/SC.png'/><BR><B>Textual Source</B></td>
					</td>
					<td width='140px' ALIGN='center'>
						<img src='../throwing-bones-contents/thumbsroot/level10/LB.png'/><BR><B>Location</B></td>
					</td>
				</tr></table>
			</fieldset>
		</div>
	</div>

	<div class="legendCloseButton">
		<A HREF="javascript:hideLegend()"><img width=60 heigth=60 src="http://witching.org/throwing-bones/images/close.png" border="none" /></A>
	</div>
</div>

