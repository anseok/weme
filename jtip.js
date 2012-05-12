/*
 * JTip | For maps
 * Cody Lindley (http://www.codylindley.com) original author
 * Jason Guo (http://www.xing.net.au) rewritten by
 * Under an Attribution, Share Alike License
 * JTip is built on top of the very light weight jquery library.
 */

var thisIsFirstTimeJTisCalled = 1;

function fireUpToolTip(){

	var toolTipActive = false;
	$("area.jTip").hover(

		function() {
			var offsetX = 10;
			var offsetY = 0;
			var areaCoords = this.coords.split(',');

			var mapPosition = $('img#mapImage' + this.id.substr(7,this.id.indexOf('-') - 7) ).offset();			
			var tipTop = mapPosition.top + (areaCoords[1] * 1) + offsetY;;
			var tipLeft = mapPosition.left + (areaCoords[2] * 1) + offsetX;
			if (!toolTipActive)
				JT_show(this.id,this.id,this.alt,tipLeft,tipTop);
			toolTipActive = true;
		}, 
		function() {			
			JT_destroy();
			toolTipActive =false;
		}
	);
}



 
function JT_destroy(){
	$('div#JT').remove();
}


function JT_show(url,linkId,title,posX,posY){

//alert(linkId);
	if (throwOrHTMLF == "Throw"){
		if(title == false)title="&nbsp;";
		var de = document.documentElement;
		var w = self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
		var hasArea = w - getAbsoluteLeft(linkId);
		var clickElementy = posY; //set y position
		var queryString = url.replace(/^[^\?]+\??/,'');
		var params = parseQuery( queryString );
		if(params['width'] === undefined){params['width'] = 140};
		filesToBeDisplayed = url.substr(url.indexOf('-')+1).split('_');
		htmlForThumbsTitle = '';

		htmlForThumbs = "<td><img src='http://witching.org/throwing-bones/thumbsroot/level9/" + filesToBeDisplayed[0] + ".png'></img></td>";
		if (filesToBeDisplayed[3] != '')
			htmlForThumbsTitle += "<td><center>" + filesToBeDisplayed[3] + "</center></td>";

		if (filesToBeDisplayed[1] != ''){
			htmlForThumbs += "<td><img src='http://witching.org/throwing-bones/thumbsroot/level9/" + filesToBeDisplayed[1] + ".png'></img></td>";
			if (filesToBeDisplayed[4] != '')
				htmlForThumbsTitle += "<td><center>" + filesToBeDisplayed[4] + "</center></td>"
			params['width'] = 270;
		}
		if (filesToBeDisplayed[2] != ''){
			htmlForThumbs += "<td><img src='http://witching.org/throwing-bones/thumbsroot/level9/" + filesToBeDisplayed[2] + ".png'></img></td>";
			if (filesToBeDisplayed[5] != '')
				htmlForThumbsTitle += "<td><center>" + filesToBeDisplayed[5] + "</center></td>"
			params['width'] = 400;
		}

		finalizedHTML =  "<table cellspacing=0 cellpadding=15 border=0><tr>" + htmlForThumbsTitle + "</tr><tr>" + htmlForThumbs + "</tr></table>";

		if(hasArea>((params['width']*1)+75)){
			$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_left'></div><div id='JT_close_left'>"+title+"</div><div id='JT_copy'>"+finalizedHTML+"</div></div>");//right side
			var arrowOffset = getElementWidth(linkId) + 11;
			var clickElementx = posX; 
		}else{
			$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_right' style='left:"+((params['width']*1)+1)+"px'></div><div id='JT_close_right'>"+title+"</div><div id='JT_copy'><div class='JT_loader'><div></div></div>");//left side
			var clickElementx = getAbsoluteLeft(linkId) - ((params['width']*1) + 15); //set x position
		}
		$('#JT').css({left: clickElementx+20+"px", top: clickElementy-10+"px"});
		$('#JT').show();
	}else{

		if(title == false)title="&nbsp;";
		var de = document.documentElement;
		var w = self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
		var hasArea = w - 0;//getAbsoluteLeft(linkId);
		var clickElementy = posY; //set y position
		var queryString = url.replace(/^[^\?]+\??/,'');
		var params = parseQuery( queryString );
		if(params['width'] === undefined){params['width'] = 165};
		if (thisIsFirstTimeJTisCalled == 1){
			thisIsFirstTimeJTisCalled = 0;
			var ajaxRequest;  	
			try{
				ajaxRequest = new XMLHttpRequest();
			} catch (e){
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
						alert("Browser Problem");
						return false;
					}
				}
			}
			var allTitles = "";
			for (var cardTitle in cardarray)
				allTitles += cardTitle + "_";

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){
					interArray =  ajaxRequest.responseText.split("__");

					j = 0;
					for (var i in interArray){
						finalArray =  interArray[i].split("_");
						cardarrayDescription[finalArray[0]] = finalArray[1] + "_" + finalArray[2];
						j++;
					}

					finalArray =  cardarrayDescription[title].split("_");

					if(hasArea>((params['width']*1)+75)){
						$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_left'></div><div id='JT_close_left'>"+finalArray[0]+"</div><div id='JT_copy'>"+finalArray[1]+"</div></div>");//right side
						var arrowOffset = 100; //getElementWidth(linkId) + 11;
						var clickElementx = posX; //set x position
					}else{
						$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_right' style='left:"+((params['width']*1)+1)+"px'></div><div id='JT_close_right'>"+title+"</div><div id='JT_copy'><div class='JT_loader'><div></div></div>");//left side
						var clickElementx = getAbsoluteLeft(linkId) - ((params['width']*1) + 15); //set x position
					}
					$('#JT').css({left: clickElementx+20+"px", top: clickElementy-10+"px"});
					$('#JT').show();
				}
			}
			ajaxRequest.open("GET", "getinfo.php?id=" + allTitles , true);
			ajaxRequest.send(null); 
		}else{

					finalArray =  cardarrayDescription[title].split("_");

					if(hasArea>((params['width']*1)+75)){
						$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_left'></div><div id='JT_close_left'>"+finalArray[0]+"</div><div id='JT_copy'>"+finalArray[1]+"</div></div>");//right side
						var arrowOffset = 100; //getElementWidth(linkId) + 11;
						var clickElementx = posX; //set x position
					}else{
						$("body").append("<div id='JT' style='width:"+params['width']*1+"px'><div id='JT_arrow_right' style='left:"+((params['width']*1)+1)+"px'></div><div id='JT_close_right'>"+title+"</div><div id='JT_copy'><div class='JT_loader'><div></div></div>");//left side
						var clickElementx = getAbsoluteLeft(linkId) - ((params['width']*1) + 15); //set x position
					}
					$('#JT').css({left: clickElementx+20+"px", top: clickElementy-10+"px"});
					$('#JT').show();
		}
	}
}

function getElementWidth(objectId) {

	x = document.getElementById(objectId);

	return x.offsetWidth;

}

function getAbsoluteLeft(objectId) {
	o = document.getElementById(objectId);
	oLeft = o.offsetLeft;
	return oLeft;

}



function getAbsoluteTop(objectId) {
	o = document.getElementById(objectId);
	oTop = o.offsetTop;

	while(o.offsetParent!=null) { 
		oParent = o.offsetParent;
		oTop += oParent.offsetTop;
		o = oParent;
	}
	return oTop;
}

function parseQuery ( query ) {
   var Params = new Object ();
   if ( ! query ) return Params;
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) continue;
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;

}

function blockEvents(evt) {
              if(evt.target){
              evt.preventDefault();
              }else{
              evt.returnValue = false;
              }
}
