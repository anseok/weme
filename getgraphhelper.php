<?php

/*
 set up initial node velocities to (0,0)
 set up initial node positions randomly // make sure no 2 nodes are in exactly the same position
 loop
     total_kinetic_energy := 0 // running sum of total kinetic energy over all particles
     for each node
         net-force := (0, 0) // running sum of total force on this particular node
         
         for each other node
             net-force := net-force + Coulomb_repulsion( this_node, other_node )
         next node
         
         for each spring connected to this node
             net-force := net-force + Hooke_attraction( this_node, spring )
         next spring
         
         // without damping, it moves forever
         this_node.velocity := (this_node.velocity + timestep * net-force) * damping
         this_node.position := this_node.position + timestep * this_node.velocity
         total_kinetic_energy := total_kinetic_energy + this_node.mass * (this_node.velocity)^2
     next node
 until total_kinetic_energy is less than some small number  // the simulation has stopped moving

*/

	include "/home/weme-dev/bin/thumbsettings.php";


	$canvasWidth			= 1150;
	$canvasHeigth			= 700;
	$canvasPadding			= 10;

	$forceDrivenGraphThreshold	= 0;

	$timeStep 			= 0.1;
	$dumping			= 0.9;
	$kineticThresh			= 100;
	$maxIterations 			= 30;
	$curvinesFactor			= 100;
	$idealNumberOfCardsInEachRow	= 10;

	$initialzoomlevel 		= 13;
 	$zoomLowerLimit 		= 25;
	$zoomUpperLimit 		= 1;


	$eventwhereclause = "(0=1)";
	for ($i = 0; $i < count($eventObject); $i++){
		$thisID = $eventObject[$i]['ID'];
		if ($eventwhereclause != "") $eventwhereclause .= "OR";
			$eventwhereclause .= "(eventassertion_id=". $thisID .")" ;
	}



	$personQuery = "SELECT Participates.person_id,Participates.eventassertion_id FROM Participates WHERE ($eventwhereclause)";
	$resultpersons = mysql_query($personQuery);
	$personwhereclause = "(0=1)";
	$i = 0;
	while ($rowItemsInDB = mysql_fetch_object($resultpersons)){
		$personEdgeObject[$i++] = array( 'Source' => $rowItemsInDB->eventassertion_id, 'Target' => $rowItemsInDB->person_id);
		if ($personwhereclause != "") $personwhereclause .= "OR";
		$personwhereclause .= "(data_person.id=".$rowItemsInDB->person_id.")" ;
	}



	$personQuery = "SELECT Intervenes.eventassertion_id,Intervenes.magicalbeing_id FROM Intervenes WHERE ($eventwhereclause)";
	$resultpersons = mysql_query($personQuery);
	$preterwhereclause = "(0=1)";
	$i = 0;
	while ($rowItemsInDB = mysql_fetch_object($resultpersons)){
		$preterEdgeObject[$i++] = array( 'Source' => $rowItemsInDB->eventassertion_id, 'Target' => $rowItemsInDB->magicalbeing_id);
		if ($preterwhereclause != "") $preterwhereclause .= "OR";
		$preterwhereclause .= "(data_magicalbeing.id=".$rowItemsInDB->magicalbeing_id.")" ;
	}


	$peopleQuery = "
				SELECT data_person.id,data_person.preferred_name,Persontype.persontype_id
				FROM data_person,data_personassertion,Persontype
				WHERE((data_person.id = data_personassertion.refers_to_id)AND(Persontype.personassertion_id=data_personassertion.id) AND ($personwhereclause))
				ORDER BY data_person.id ASC
				";


	$resultpeople = mysql_query($peopleQuery);
	$i = 0;
	$lastPersonID = -1;
	while ($rowItemsInDB = mysql_fetch_object($resultpeople)){
		if ($lastPersonID != $rowItemsInDB->id){
			$witchObject[$i] = array( 'ID' => $rowItemsInDB->id, 'Name' => $rowItemsInDB->preferred_name, 'Type' => $rowItemsInDB->persontype_id , 'xPosi' => 0, 'yPosi'=> 0);
			$witchObjectIndex[$rowItemsInDB->id] = $i;
			$lastPersonID = $rowItemsInDB->id;
			$i++;
		}
	}



	$preterQuery = "
				SELECT data_magicalbeing.id,data_magicalbeing.name,Magicalbeingtype.magicalbeingtype_id 
				FROM data_magicalbeing,Magicalbeingtype WHERE ((data_magicalbeing.id=Magicalbeingtype.magicalbeing_id) AND ($preterwhereclause))
				ORDER BY data_magicalbeing.id ASC
				";
	$resultpreter = mysql_query($preterQuery);




	$i = 0;
	$previousID = -1;
	while ($rowItemsInDB = mysql_fetch_object($resultpreter )){
		if ($previousID != $rowItemsInDB->id){
			$preterObject[$i] = array( 'ID' => $rowItemsInDB->id, 'Name' => $rowItemsInDB->name, 'Type' => $rowItemsInDB->magicalbeingtype_id , 'xPosi' => 0, 'yPosi'=> 0);
			$preterObjectIndex[$rowItemsInDB->id] = $i;
			$i++;
			$previousID = $rowItemsInDB->id;
		}
	}


	$totalNumberOfCards = count($eventObject) + count($witchObject) + count($preterObject);
	$numOfCardsInEachRow = sqrt($totalNumberOfCards);

	$zoomlevel = $initialzoomlevel + ceil(log($numOfCardsInEachRow / $idealNumberOfCardsInEachRow , $shrinkageFactor));
	$zoomlevel = min($zoomlevel, $zoomLowerLimit);
	$zoomlevel = max($zoomlevel, $zoomUpperLimit);

	$expansionFactor =  pow($shrinkageFactor, ceil(log($numOfCardsInEachRow / $idealNumberOfCardsInEachRow , $shrinkageFactor)));

	$cardWidthAtThisZoom = floor($initWidth / (pow($shrinkageFactor, $zoomlevel)));
	$cardHeigthAtThisZoom = $cardWidthAtThisZoom * $heightDevidedByWidth;

	$verticalshiftFactor = $cardWidthAtThisZoom / 2 + $canvasPadding;
	$horizontashiftFactor = $cardHeigthAtThisZoom / 2 + $canvasPadding;

	for ($i = 0; $i < count($eventObject); $i++){
		$eventObject[$i]['xPosi'] = round(mt_rand($verticalshiftFactor, $canvasWidth-$verticalshiftFactor)*$expansionFactor ,2) ;
		$eventObject[$i]['yPosi'] = round(mt_rand($horizontashiftFactor, $canvasHeigth-$horizontashiftFactor)*$expansionFactor ,2) ;
	}

	for ($i = 0; $i < count($witchObject); $i++){
		$witchObject[$i]['xPosi'] = round(mt_rand($verticalshiftFactor, $canvasWidth-$verticalshiftFactor)*$expansionFactor ,2) ;
		$witchObject[$i]['yPosi'] = round(mt_rand($horizontashiftFactor, $canvasHeigth-$horizontashiftFactor)*$expansionFactor ,2) ;
	}

	for ($i = 0; $i < count($preterObject); $i++){
		$preterObject[$i]['xPosi'] = round(mt_rand($verticalshiftFactor, $canvasWidth-$verticalshiftFactor)*$expansionFactor ,2) ;
		$preterObject[$i]['yPosi'] = round(mt_rand($horizontashiftFactor, $canvasHeigth-$horizontashiftFactor)*$expansionFactor ,2) ;
	}


	$finalRespond = "zoomlevel=$zoomlevel;zoomlevelMini=$zoomlevel;";


	for ($i = 0; $i < count($eventObject); $i++){
		$thisID = $eventObject[$i]['ID'];
		$thisTitle = $eventObject[$i]['ID'];
		$thisFile = $eventObject[$i]['File'];
		$thisX = $eventObject[$i]['xPosi'] ;
		$thisY = $eventObject[$i]['yPosi'] ;

$finalRespond .= <<<MSGEVENT
Anchor($thisX,$thisY,"e$thisTitle","$thisFile.png");
MSGEVENT;
	}



	for ($i = 0; $i < count($witchObject); $i++){
		$thisID = $witchObject[$i]['ID'];
		$thisTitle = $witchObject[$i]['ID'];
		$thisFile = $witchObject[$i]['Type'];
		$thisX = $witchObject[$i]['xPosi'] ;
		$thisY = $witchObject[$i]['yPosi'] ;

$finalRespond .= <<<MSGWITCH
Anchor($thisX,$thisY,"p$thisTitle","p$thisFile.png");
MSGWITCH;
	}



	for ($i = 0; $i < count($preterObject); $i++){
		$thisID = $preterObject[$i]['ID'];
		$thisTitle = $preterObject[$i]['ID'];
		$thisFile = $preterObject[$i]['Type'];
		$thisX = $preterObject[$i]['xPosi'] ;
		$thisY = $preterObject[$i]['yPosi'] ;

$finalRespond .= <<<MSGPRETER
Anchor($thisX,$thisY,"d$thisTitle","d$thisFile.png");
MSGPRETER;
	}




	for ($i = 0; $i < count($personEdgeObject); $i++){
		$thisSource = $personEdgeObject[$i]['Source'];
		$thisTarget = $personEdgeObject[$i]['Target'];
		$edgeName = 'E' . $thisSource . 'W' . $thisTarget;

$finalRespond .= <<<MSGPERSONEDGE
Edge("e$thisSource","p$thisTarget","$edgeName");
MSGPERSONEDGE;


	}


	for ($i = 0; $i < count($preterEdgeObject); $i++){
		$thisSource = $preterEdgeObject[$i]['Source'];
		$thisTarget = $preterEdgeObject[$i]['Target'];
		$edgeName = 'E' . $thisSource . 'P' . $thisTarget;

$finalRespond .= <<<MSGPERSONEDGE
Edge("e$thisSource","d$thisTarget","$edgeName");
MSGPERSONEDGE;
	}

echo $finalRespond ; 




function calculate_Coulomb_Repulsion($eventObject, $witchObject, $preterObject, $personEdgeObject, $preterEdgeObject, $nodeType, $arrayIndex ,$nodeID, &$netForceX, &$netForceY, $eventObjectIndex, $witchObjectIndex, $preterObjectIndex){

	$netForceX = 0;
	$netForceY = 0;
	$attracConst = 1;
	$repulsConst = 100;
	$repulsRange = 200;

	if ($nodeType == "Event"){
		$thisNodeXpos = $eventObject[$arrayIndex]['xPosi'];
		$thisNodeYpos = $eventObject[$arrayIndex]['yPosi'];
	}elseif($nodeType == "Witch"){
		$thisNodeXpos = $witchObject[$arrayIndex]['xPosi'];
		$thisNodeYpos = $witchObject[$arrayIndex]['yPosi'];
	}else{
		$thisNodeXpos = $preterObject[$arrayIndex]['xPosi'];
		$thisNodeYpos = $preterObject[$arrayIndex]['yPosi'];
	}

	/*There we go with attraction now!*/
	$thisCardisIsolated = 1;
	if ($nodeType == "Witch"){
		for ($i = 0; $i < count($personEdgeObject); $i++){
			if ($nodeID == $personEdgeObject[$i]['Target']){
				$thisCardisIsolated = 0;
				$myXOne = $eventObject[ $eventObjectIndex[$personEdgeObject[$i]['Source']] ]['xPosi'];
				$myYOne = $eventObject[ $eventObjectIndex[$personEdgeObject[$i]['Source']] ]['yPosi'];
				$netForceX += ( $myXOne - $thisNodeXpos) * $attracConst ;
				$netForceY += ( $myYOne - $thisNodeYpos) * $attracConst ;
			}
		}
	}

	if ($nodeType == "Preternatural"){
		for ($i = 0; $i < count($preterEdgeObject); $i++){
			if ($nodeID == $preterEdgeObject[$i]['Target']){
				$thisCardisIsolated = 0;
				$myXOne = $eventObject[ $eventObjectIndex[$preterEdgeObject[$i]['Source']] ]['xPosi'];
				$myYOne = $eventObject[ $eventObjectIndex[$preterEdgeObject[$i]['Source']] ]['yPosi'];
				$netForceX += ( $myXOne - $thisNodeXpos) * $attracConst ;
				$netForceY += ( $myYOne - $thisNodeYpos) * $attracConst ;
			}
		}
	}


	if ($nodeType == "Event"){

		for ($i = 0; $i < count($personEdgeObject); $i++){
			if ($nodeID == $personEdgeObject[$i]['Source']){
				$thisCardisIsolated = 0;
				$myXOne = $witchObject[$witchObjectIndex[$personEdgeObject[$i]['Target']] ]['xPosi'];
				$myYOne = $witchObject[$witchObjectIndex[$personEdgeObject[$i]['Target']] ]['yPosi'];
				$netForceX += ( $myXOne - $thisNodeXpos) * $attracConst ;
				$netForceY += ( $myYOne - $thisNodeYpos) * $attracConst ;
			}
		}

		for ($i = 0; $i < count($preterEdgeObject); $i++){
			if ($nodeID == $preterEdgeObject[$i]['Source']){
				$thisCardisIsolated = 0;
				$myXOne = $preterObject[ $preterObjectIndex[$preterEdgeObject[$i]['Target']] ]['xPosi'];
				$myYOne = $preterObject[ $preterObjectIndex[$preterEdgeObject[$i]['Target']] ]['yPosi'];
				$netForceX += ( $myXOne - $thisNodeXpos) * $attracConst ;
				$netForceY += ( $myYOne - $thisNodeYpos) * $attracConst ;
			}
		}
	}

	/*Calculate Repulsion Here*/
	for ($i = 0; $i < count($eventObject); $i++){
		if ($nodeType == "Event" && $arrayIndex == $i) continue;
		$distantBetweenNodes =  max((pow($eventObject[$i]['xPosi']-$thisNodeXpos,2) + pow($eventObject[$i]['yPosi']-$thisNodeYpos,2)), 1);
		if ($distantBetweenNodes > pow($repulsRange,2)) continue;
		$columbusF = 1 / $distantBetweenNodes;
		$netForceX += (($thisNodeXpos - $eventObject[$i]['xPosi']) * $columbusF) * $repulsConst;
		$netForceY += (($thisNodeYpos - $eventObject[$i]['yPosi']) * $columbusF) * $repulsConst;
	}

	for ($i = 0; $i < count($witchObject); $i++){
		if ($nodeType == "Witch" && $arrayIndex == $i) continue;
		$distantBetweenNodes =  max((pow($witchObject[$i]['xPosi']-$thisNodeXpos,2) + pow($witchObject[$i]['yPosi']-$thisNodeYpos,2)), 1);
		if ($distantBetweenNodes > pow($repulsRange,2)) continue;
		$columbusF = 1 / $distantBetweenNodes;
		$netForceX += (($thisNodeXpos - $witchObject[$i]['xPosi']) * $columbusF) * $repulsConst;
		$netForceY += (($thisNodeYpos - $witchObject[$i]['yPosi']) * $columbusF) * $repulsConst;
	}

	for ($i = 0; $i < count($preterObject); $i++){
		if ($nodeType == "Preternatural" && $arrayIndex == $i) continue;
		$distantBetweenNodes =  max((pow($preterObject[$i]['xPosi']-$thisNodeXpos,2) + pow($preterObject[$i]['yPosi']-$thisNodeYpos,2)), 1);
		if ($distantBetweenNodes > pow($repulsRange,2)) continue;
		$columbusF = 1 / $distantBetweenNodes;
		$netForceX += (($thisNodeXpos - $preterObject[$i]['xPosi']) * $columbusF) * $repulsConst;
		$netForceY += (($thisNodeYpos - $preterObject[$i]['yPosi']) * $columbusF) * $repulsConst;
	}
}

	// $MaxShrink = max(($mostX - $leastX)*$expandFactor / $canvasWidth, ($mostY - $leastY)*$expandFactor / $canvasHeigth);



/*
	if ($forceDrivenGraphThreshold > (count($eventObject) + count($witchObject) + count($preterObject))){
		for ($i = 0; $i < count($eventObject); $i++)
			$origEventObject[$i] = array( xPosi => $eventObject[$i]['xPosi'], yPosi => $eventObject[$i]['yPosi']);
		for ($i = 0; $i < count($witchObject); $i++)
			$origWitchObject[$i] = array( xPosi => $witchObject[$i]['xPosi'], yPosi => $witchObject[$i]['yPosi']);
		for ($i = 0; $i < count($preterObject); $i++)
			$origPreterObject[$i] = array( xPosi => $preterObject[$i]['xPosi'], yPosi => $preterObject[$i]['yPosi']);


		for ($iterationCounter = 0; $iterationCounter < $maxIterations; $iterationCounter++){

			$totalKineticEnergy = 0;
			$netForceX = 0;
			$netForceY = 0;

			for ($i = 0; $i < count($eventObject); $i++){
				calculate_Coulomb_Repulsion($eventObject, $witchObject, $preterObject, $personEdgeObject, $preterEdgeObject, "Event", $i, $eventObject[$i]['ID'], $netForceX, $netForceY, $eventObjectIndex, $witchObjectIndex, $preterObjectIndex);
				$eventObject[$i]['nodeVelocityX'] = (0 + $netForceX * $timeStep) * $dumping;
				$eventObject[$i]['nodeVelocityY'] = (0  + $netForceY * $timeStep) * $dumping;
				$eventObject[$i]['xPosi'] = $eventObject[$i]['xPosi'] + ($eventObject[$i]['nodeVelocityX'] * $timeStep) * $dumping;
				$eventObject[$i]['yPosi'] = $eventObject[$i]['yPosi'] + ($eventObject[$i]['nodeVelocityY'] * $timeStep) * $dumping;
				$totalKineticEnergy +=  pow($eventObject[$i]['nodeVelocityX'], 2) + pow($eventObject[$i]['nodeVelocityY'], 2);
			}

			for ($i = 0; $i < count($witchObject); $i++){
				calculate_Coulomb_Repulsion($eventObject, $witchObject, $preterObject, $personEdgeObject, $preterEdgeObject, "Witch", $i, $eventObject[$i]['ID'], $netForceX, $netForceY, $eventObjectIndex, $witchObjectIndex, $preterObjectIndex);			
				$witchObject[$i]['nodeVelocityX'] = (0 + $netForceX * $timeStep) * $dumping;
				$witchObject[$i]['nodeVelocityY'] = (0 + $netForceY * $timeStep) * $dumping;
				$witchObject[$i]['xPosi'] = $witchObject[$i]['xPosi'] + ($witchObject[$i]['nodeVelocityX'] * $timeStep) * $dumping;
				$witchObject[$i]['yPosi'] = $witchObject[$i]['yPosi'] + ($witchObject[$i]['nodeVelocityY'] * $timeStep) * $dumping;
				$totalKineticEnergy +=  pow($witchObject[$i]['nodeVelocityX'], 2) + pow($witchObject[$i]['nodeVelocityY'], 2);
			}

			for ($i = 0; $i < count($preterObject); $i++){
				calculate_Coulomb_Repulsion($eventObject, $witchObject, $preterObject, $personEdgeObject, $preterEdgeObject, "Preternatural", $i, $eventObject[$i]['ID'], $netForceX, $netForceY, $eventObjectIndex, $witchObjectIndex, $preterObjectIndex);
				$preterObject[$i]['nodeVelocityX'] = (0 + $netForceX * $timeStep) * $dumping;
				$preterObject[$i]['nodeVelocityY'] = (0 + $netForceY * $timeStep) * $dumping;
				$preterObject[$i]['xPosi'] = $preterObject[$i]['xPosi'] + ($preterObject[$i]['nodeVelocityX'] * $timeStep) * $dumping;
				$preterObject[$i]['yPosi'] = $preterObject[$i]['yPosi'] + ($preterObject[$i]['nodeVelocityY'] * $timeStep) * $dumping;
				$totalKineticEnergy +=  pow($preterObject[$i]['nodeVelocityX'], 2) +  pow($preterObject[$i]['nodeVelocityY'], 2);
			}

			if ($iterationCounter != 0 && (($totalKineticEnergy > $lastKineticEnergy) || ($totalKineticEnergy < $kineticThresh))) break;
			$lastKineticEnergy = $totalKineticEnergy;
		}
	}



	$leastX = 'undefined';
	$mostX = 'undefined';
	$leastY = 'undefined';
	$mostY = 'undefined';

	for ($i = 0; $i < count($eventObject); $i++){

		if ($leastX == 'undefined') 			$leastX 	= $eventObject[$i]['xPosi'];
		if ($mostX == 'undefined') 			$mostX 		= $eventObject[$i]['xPosi'];
		if ($leastY == 'undefined') 			$leastY 	= $eventObject[$i]['yPosi'];
		if ($mostY == 'undefined') 			$mostY 		= $eventObject[$i]['yPosi'];

		if ($eventObject[$i]['xPosi'] < $leastX) 	$leastX 	= $eventObject[$i]['xPosi'];
		if ($eventObject[$i]['xPosi'] > $mostX) 	$mostX 		= $eventObject[$i]['xPosi'];
		if ($eventObject[$i]['yPosi'] < $leastY) 	$leastY 	= $eventObject[$i]['yPosi'];
		if ($eventObject[$i]['yPosi'] > $mostY) 	$mostY 		= $eventObject[$i]['yPosi'];
	}

	for ($i = 0; $i < count($witchObject); $i++){

		if ($leastX == 'undefined') 			$leastX 	= $witchObject[$i]['xPosi'];
		if ($mostX == 'undefined') 			$mostX 		= $witchObject[$i]['xPosi'];
		if ($leastY == 'undefined') 			$leastY 	= $witchObject[$i]['yPosi'];
		if ($mostY == 'undefined') 			$mostY 		= $witchObject[$i]['yPosi'];

		if ($witchObject[$i]['xPosi'] < $leastX) 	$leastX 	= $witchObject[$i]['xPosi'];
		if ($witchObject[$i]['xPosi'] > $mostX) 	$mostX 		= $witchObject[$i]['xPosi'];
		if ($witchObject[$i]['yPosi'] < $leastY) 	$leastY 	= $witchObject[$i]['yPosi'];
		if ($witchObject[$i]['yPosi'] > $mostY) 	$mostY 		= $witchObject[$i]['yPosi'];
	}

	for ($i = 0; $i < count($preterObject); $i++){

		if ($leastX == 'undefined') 			$leastX 	= $preterObject[$i]['xPosi'];
		if ($mostX == 'undefined') 			$mostX 		= $preterObject[$i]['xPosi'];
		if ($leastY == 'undefined') 			$leastY 	= $preterObject[$i]['yPosi'];
		if ($mostY == 'undefined') 			$mostY 		= $preterObject[$i]['yPosi'];

		if ($preterObject[$i]['xPosi'] < $leastX) 	$leastX 	= $preterObject[$i]['xPosi'];
		if ($preterObject[$i]['xPosi'] > $mostX) 	$mostX 		= $preterObject[$i]['xPosi'];
		if ($preterObject[$i]['yPosi'] < $leastY) 	$leastY 	= $preterObject[$i]['yPosi'];
		if ($preterObject[$i]['yPosi'] > $mostY) 	$mostY 		= $preterObject[$i]['yPosi'];
	}
*/


?>
