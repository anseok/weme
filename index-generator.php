<?php 
	include "../mygooglekey.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Throwing Bones + Witches in Early Modern England</title>

<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $mygooglekey;?>" type="text/javascript"></script>
<link rel="stylesheet" href="css/floating-window.css" media="screen" type="text/css">



<link href="wemestyle.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" media="all" href="jtip.css" />
	<script src="jtip.js" type="text/javascript"></script>

	<link type="text/css" 		href="jquery/development-bundle/themes/base/jquery.ui.all.css" rel="stylesheet" /> 
	<script type="text/javascript" 	src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script> 
	<script type="text/javascript" 	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script> 


	<link rel="stylesheet" href="../brimstone/css/style.css" type="text/css" media="print, projection, screen" />
  	<script type="text/javascript" src="../brimstone/js/jquery.tablesorter.min.js"></script>

	<script type="text/javascript" src="../brimstone/js/jquery.bgiframe-2.1.2.js"></script>
	<script type="text/javascript" src="../brimstone/js/jquery-ui-i18n.min.js"></script>

	<script type="text/javascript" src="js/jquery.history.js"></script>


<script type="text/javascript"> 

var startYearTimeLine = 1550;
var endYearTimeLine = 1560;
var legendItemClicked = 'legend-magics';

var timelineValuesStart = 1560;
var timelineValuesEnd = 1570;


	$(function() {
		$("#slider-range").slider({
			range: true,
			min: 1530,
			max: 1690,
			values: [1560, 1570],
			slide: function(event, ui) {
				$("#amount").val('From:' + ui.values[0] + ' - To: ' + ui.values[1]);
			},
			stop: function(event, ui) {
				if (throwOrHTMLF == "Throw"){
					timelineValuesStart = ui.values[0];
					timelineValuesEnd = ui.values[1];
					throw_timecontentdiv(ui.values[0],ui.values[1], "Throw");
			 		throw_timedecksdiv(ui.values[0],ui.values[1], "Throw");
				}else if (throwOrHTMLF == "HTMLF"){
					timelineValuesStart = ui.values[0];
					timelineValuesEnd = ui.values[1];
			 		throw_timedecksdiv(ui.values[0],ui.values[1], "HTMLF");
				}else{
					startYearTimeLine = (1690-parseInt(ui.values[1])+1530);
					endYearTimeLine = (1690-parseInt(ui.values[0])+1530);
					loadFilter("&startYear=" + startYearTimeLine + "&endYear=" + endYearTimeLine , "timeline");
				}
			},
		});
		$("#amount").val('From: ' + $("#slider-range").slider("values", 0) + ' - To: ' + $("#slider-range").slider("values", 1));

	});
</script> 

<script type="text/javascript" src="js/floating-window.js">
		/************************************************************************************************************
		(C) www.dhtmlgoodies.com, September 2005
	
		This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
		Terms of use:
		You are free to use this script as long as the copyright message is kept intact. However, you may not
		redistribute, sell or repost it without our permission.
	
		Thank you!
	
		www.dhtmlgoodies.com
		Alf Magne Kalleland
	
		************************************************************************************************************/
	
</script>

<script type="text/javascript" src="weme.js"></script>
<script type="text/javascript" src="js/dom-drag.js"></script>


</head>
<body onResize="bodyResized();" onunload="GUnload()">
			<DIV id="header">
					<H1>
					<a href="javascript:window.parent.location.href='http://witching.org/'">Witches in Early Modern England</a></H1>
					<H2><div id="throwingBonesReadingLeavesHeader">
						<a href="javascript:switchToReadingLeaves('Fulltext');">Full Text</a>, 
						<a href="javascript:switchToReadingLeaves('Mapping');">Mapping Witches</a>, 
						<a href="javascript:switchToReadingLeaves('HTMLF');">Reading Leaves</a>, 
						Throwing Bones</div></H2>
			</DIV>
<table border=0 cellpadding=0 cellspacing=0>
<tr valign=top><td>

<table border=0 cellpadding=0 cellspacing=1 width="1100">

	<tr><td><div id="paddingOnLeftHandSide" style="width: 20px"></div></td>
		<td colspan=2><div id="topnavdiv" >



	<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td>
			<table border="0">
				<tr>
					<td>Search Database:</td><td><input type="text" id="keywordsearch" name="keywordsearch" size=26/></td>
					<td><input type="checkbox" id="tieToTimeline" name="tieToTimeline"> Tie to Timeline</td>
					<td><input type=BUTTON value="Advance Filter" name="myFilter" onClick="showFilters()"></td>
					<td><input type=BUTTON value="Go ..." name="mySubmit" onClick="generateFilterDecks()" style="width: 81px"></td>
					<td><a href="javascript:openLegend()">Legend</a></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>


		</div></td>
	</tr>
	<tr>
		<td>
		</td>
		<td colspan=2>
			<BR>
			<center><input type="text" size="30" id="amount" style="border:0; color:#000000; font-weight:bold;" /> </center>
 

			<div id="slider-range" style="width: 1110px"></div> <BR>
 		</td>
	</tr>

	<tr>
		<td>
		</td>
		<td colspan=2>
			<div>
				<div id="throwingBonesContainer">
					<table border=0 cellpadding=0 cellspacing=1 width="1100">
						<tr><td valign=top><div id="deckcontentdiv" style="width: 750px"></div></td>
						<td valign=top><div id="timecontentdiv" style="height: 500px; overflow: auto"></div></td></tr>
					</table>
				</div>
				<div id="readingLeavesContainer">
					<div style="width: 750px">
						<div id="testing"></div>
						<canvas id="canvas" class="readingleavescanvas" width="1150" height="700">
							<canvas id="shadow" class="readingleavesshadow" width="1150" height="700"></canvas>
							Your browser does not support HTML5 canvas!
						</canvas>
						<canvas id="canvasedges" class="readingleavescanvasedges" width="1150" height="700"></canvas>
						<canvas id="miniature" class="readingleavesminiature" width="230" height="140"></canvas>
						<canvas id="miniatureedges" class="readingleavesminiatureedges" width="230" height="140"></canvas>
					</div>
				</div>
				<div id="mappingContainer">
					<table border=0 cellpadding=0 cellspacing=1 width="1100">	
						<tr><td valign=top> <div id="map" style="width: 1100px; height: 860px"></div>  </td></tr>	
						<tr><td valign=top> <div id="mapcontent"></div>  </td></tr>	
					</table>
				</div>
				<div id="fullTextContainer">
					<table border=0 cellpadding=0 cellspacing=1 width="1100">	
						<tr><td valign=top><div id="searchcontentdiv" style="width: 1100px"><H1>No results</H1><BR>Please use the search box, timeline and advance filters to search results.</div></td>
					</table>

				</div>
			</div>
		</td>
	</tr>
</table>


</td>
</tr>
</table>

<div id="filterDIV" style="position: absolute; top: 50px; left: 50px; width: 1000px; height: 800px; background: white; z-index: 5; visibility: hidden" class="shadow"></div>
<div id="legendDIV" style="position: absolute; top: 50px; left: 50px; width: 1000px; height: 800px; background: white; z-index: 5; visibility: hidden" class="shadow"></div>

<div id="boxes">
<div id="dialog2" class="window">
 
<H2>Basket</H2>
Please enter your email address in the box below, if you wish to recieve an email with the contents of the basket:
<BR><BR>
<input name="useremail" id="useremail" type="Text" size="30" ><BR><BR>
<input type=BUTTON value="Email me the Basket" onClick="emailBasket('email');">
<BR><BR>Or download the PDF version of the content of the basket:<BR><BR>
<input type=BUTTON value="Download PDF" onClick="emailBasket(1)">

<input type="button" value="Close" class="close"/>
</div>
  <div id="mask"></div>
</div>

<script src="http://witching.org/modules/google_analytics/googleanalytics.js?6" type="text/javascript"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
var _gaq = _gaq || [];_gaq.push(["_setAccount", "UA-603868-7"]);_gaq.push(["_setVar", "authenticated user"]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = "/sites/witching.org/files/googleanalytics/ga.js?6";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();
//--><!]]>
//</script>

</body>
</html>

