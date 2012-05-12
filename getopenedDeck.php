<?php


	if 		(isset($_GET['id'])) 		$tempID = $_GET['id'];
	elseif 		(isset($_POST['id'])) 		$tempID = $_POST['id'];
	else 		$tempID = "1" ;


	if (is_numeric($tempID)){
		$thisID = $tempID;
		$thisCatType = "E";
	}else{
		$thisID = substr($tempID,1);
		$thisCatType = substr($tempID,0,1);
	}

	include "/home/weme-dev/public_html/connectdb.php";
	include "throwingbones.php";


	$resultArray = array();
	$i = 0;
	$j = 0;

	$shrinkageFactor = 0.50;
	$cardWidth = 284 * $shrinkageFactor;
	$cardHeight = 370* $shrinkageFactor;

	$smallShrinkageFactor = 0.20;
	$smallCardWidth = 284 * $smallShrinkageFactor;
	$smallCardHeight = 370* $smallShrinkageFactor;

	generateDeckObject($thisID, $resultArray, $thisCatType);

	$hiddenDIVBody = "";

	$basketTop  = (ceil( count($resultArray) /3) * $cardHeight) . "px";
	$basketLeft = 2 * $cardWidth . "px";

	$HTMLBody .= 	"<div id=\"basketDiv\" style=\"position: absolute; top: $basketTop; left: $basketLeft;\" title=\"Drop your cards in your basket and click to take it home.\">
				<A href=\"#dialog2\" name=\"modal\"><img width=\"90\" heigth=\"90\" src=\"http://witching.org/throwing-bones/images/shopping_cart.png\" border=\"none\" /></A>
			</div>";

	$bumberofCards = count($resultArray);
	for($i = 0; $i < $bumberofCards; $i++, $j++){

		if ($j == 3) {
			$j = 0;
		}
		$filename = $resultArray[$i]["File"];
		$onClick = "loadContent('" .  $resultArray[$i]["Type"] . "'," .  $resultArray[$i]["ID"] . ",'" . $filename . "')";

		$cartTop  = (floor($i/3) * $cardHeight) . "px";
		$cartLeft = ($i%3) * $cardWidth . "px";

		$thisCardType = $resultArray[$i]["Type"];
		$thisCardAssretiponID = $resultArray[$i]["ID"];

$HTMLBody .= <<<MSGCART

			<div id="rootCard$i" style="position: absolute; top: $cartTop; left: $cartLeft;">
				<div id="handleCard$i" title="Want to take this information with you? Drag and drop it into your basket">
					<div id="handleCard$i-credentials" style="visibility:hidden">$i-$thisCardAssretiponID-$thisCardType</div>
					<img src="http://witching.org/throwing-bones/thumbsroot/level9/$filename.png" alt="Assertion Deck" onclick="$onClick;"/>
				</div>
			</div>

MSGCART;

$hiddenDIVBody .= <<<MSGJS

			<div id="hiddenSmallDIV$i" style="visibility:hidden">
				<div id="handleCard$i-credentials" style="visibility:hidden">$i-$thisCardAssretiponID-$thisCardType</div>
				<img src="http://witching.org/throwing-bones/thumbsroot/level15/$filename.png" alt="Assertion Deck" onclick="$onClick;"/>
			</div>
			<div id="hiddenBigDIV$i" style="visibility:hidden">
				<div id="handleCard$i-credentials" style="visibility:hidden">$i-$thisCardAssretiponID-$thisCardType</div>
				<img src="http://witching.org/throwing-bones/thumbsroot/level9/$filename.png" alt="Assertion Deck" onclick="$onClick;"/>
			</div>
MSGJS;

	}
	
	$waterMarkPicture = $resultArray[0]["File"];

$HTMLBody .= <<<MSGBOX

	<div style="position: absolute; top: 10px; left: 450px; width: 600px; height: 720px; margin: 10px; background: white; border-radius: 20px; -moz-border-radius: 20px;">
	</div>

$hiddenDIVBody

MSGBOX;



	$HTMLBody .= "<div id=\"myWaterMarkDiv\" name=\"myWaterMarkDiv\" style=\"position: absolute; top: -95px; left: 740px; overflow: auto;\"
			class=\"watermark\"><img border=\"none\" src=\"http://witching.org/throwing-bones/newcards/$waterMarkPicture.png\" /></div>";


	$HTMLBody .= "<div id=\"cardContentDiv\" style=\"position: absolute; height: 690px; width: 590px; top: 35px; left: 465px; overflow: auto\"></div>";




	$HTMLBody .= "<div style=\"position: absolute; top: 20px; left: 980px;	height: 80px; overflow: auto;\">
		<A HREF=\"javascript:closeOpenedDeck()\"><img width=60 heigth=60 src=\"http://witching.org/throwing-bones/images/close.png\" border=\"none\" /></A>
		</div>";

	echo $HTMLBody;


?>













