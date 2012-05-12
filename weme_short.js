//////////////////////////////INIT FUNCTIONS////////////////////////////

var throwOrHTMLF;

var numberOfDecksOnTable = 15;
var lastHash = "-1";

jQuery(document).ready(
  function($) {
    $.history.init(
      function(hash) {
        if (lastHash == hash) return;
        if (document.getElementById("frontDeckPage")) closeOpenedDeck();
        if (document.getElementById("frontCardPage")) closeOpenedCard();
        if(hash == "") switchToReadingLeaves('Throw');
        else {
          var splittedHash = hash.split('-');
          switchToReadingLeaves(splittedHash[0]);
          if (splittedHash.length > 1) {
            if (splittedHash[1] != "") openDeck(splittedHash[1]);
            else showIndividualCard(splittedHash[2], splittedHash[3]);
          }
        }
      },
      { unescape: false });
  });

var throwingBonesInitialized = 0;
var readingLeavesInitialized = 0;
var mappingInitialized = 0;
var fullTextInitialized = 0;


function switchToReadingLeaves(visOption) {
  var elt;
  with (document) {
    getElementById('throwingBonesContainer').style.display = (visOption == 'Throw') ? '' : 'none'; 
    getElementById('readingLeavesContainer').style.display = (visOption == 'HTMLF') ? '' : 'none'; 
    getElementById('mappingContainer').style.display = (visOption == 'Mapping') ? '' : 'none'; 
    getElementById('fullTextContainer').style.display = (visOption == 'Fulltext') ? '' : 'none'; 
    elt = getElementById('throwingBonesReadingLeavesHeader');
  }
  if (visOption == 'Throw') {
    elt.innerHTML = "<a href=\"javascript:switchToReadingLeaves('Fulltext');\">Full Text</a>, \
<a href=\"javascript:switchToReadingLeaves('Mapping');\">Mapping Witches</a>, <a href=\"javascript:switchToReadingLeaves('HTMLF');\">Reading Leaves</a>, Throwing Bones";

    if (throwingBonesInitialized == 0) {
      throwingBonesInitialized = 1;
      throw_timecontentdiv(1560, 1570, "Throw");
      throw_timedecksdiv(1560, 1570, "Throw");
    }
  } else if (visOption == 'HTMLF') {
    elt.innerHTML = "<a href=\"javascript:switchToReadingLeaves('Fulltext');\">Full Text</a>, \
<a href=\"javascript:switchToReadingLeaves('Mapping');\">Mapping Witches</a>, Reading Leaves, <a href=\"javascript:switchToReadingLeaves('Throw');\">Throwing Bones</a>";

    if (readingLeavesInitialized == 0) {
      readingLeavesInitialized = 1;
      getgraph();
    }
  } else if (visOption == 'Mapping') {
    elt.innerHTML = "<a href=\"javascript:switchToReadingLeaves('Fulltext');\">Full Text</a>, Mapping Witches, \
<a href=\"javascript:switchToReadingLeaves('HTMLF');\">Reading Leaves</a>, <a href=\"javascript:switchToReadingLeaves('Throw');\"> Throwing Bones</a>";

    if (mappingInitialized == 0) {
      mappingInitialized = 1;
      throw_timedecksdiv(1530, 1690, "Mapping");
    }
  } else
    elt.innerHTML = "Full Text, <a href=\"javascript:switchToReadingLeaves('Mapping');\">Mapping Witches</a>, \
<a href=\"javascript:switchToReadingLeaves('HTMLF');\">Reading Leaves</a>, <a href=\"javascript:switchToReadingLeaves('Throw');\"> Throwing Bones</a>";

  throwOrHTMLF = visOption;
  lastHash = visOption;
  jQuery.history.load(visOption);
}


///////////////////////////////////////////////
///////////// Filters /////////////////////////


var numclickedfiltereventdiv = 0;
var numclickedfilterpeoplediv = 0;
var numclickedfilterpeopletypediv = 0;
var numclickedfilterpeoplelistdiv = 0;
var numclickedfilterpreternaturaldiv = 0;
var numclickedfilterpreternaturaltypediv = 0;
var numclickedfilterpreternaturalnamediv = 0;
var numclickedfilterlocationdiv = 0;
var numclickedfiltertextdiv = 0;
var numclickedfiltertextfirstauthordiv = 0;
var numclickedfiltertextfirstlistdiv = 0;
var numclickedfiltertextsecondauthordiv = 0;
var numclickedfiltertextsecondlistdiv = 0;
var numclickedfiltertextthirdauthordiv = 0;
var numclickedfiltertextthirdlistdiv = 0;

var numOffiltereventdiv = -1;
var numOffilterpreternaturaltypediv = -1;
var numOffilterpeopletypediv = -1;
var numOffilterpeoplelistdiv = -1;
var numOffilterpreternaturalnamediv = -1;
var numOffilterlocationdiv = -1;
var numOffiltertextfirstlistdiv = -1;
var numOffiltertextsecondlistdiv = -1;
var numOffiltertextthirdlistdiv = -1;
var numOffiltertextfirstauthordiv = -1;
var numOffiltertextsecondauthordiv = -1;
var numOffiltertextthirdauthordiv = -1;

var thisIsFirstTimeFiltersAreCalled = 1;
var filtersDOM;


function showFilters() {
  if (thisIsFirstTimeFiltersAreCalled == 1) {
    thisIsFirstTimeFiltersAreCalled = 0;
    var ajaxRequest;  	
    try {
      ajaxRequest = new XMLHttpRequest();
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
          alert("Browser Problem");
          return false;
        }
      }
    }
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) {
        document.getElementById('filterDIV').innerHTML = ajaxRequest.responseText;
        eval(document.getElementById('hiddenFilterDIVforNumOfs').innerHTML );
      }
    }
    ajaxRequest.open("GET", "gettopnav.html" , true);
    ajaxRequest.send(null); 		
  }
  document.getElementById('filterDIV').style.visibility = ''; 
}


function hideFilter() {
  document.getElementById('filterDIV').style.visibility = 'hidden'; 
}


function highlightfilterdiv(itemToBeVisible) {
  function expand(s) { return 'filter' + s + 'div'; }
  function makeVisible(s) {
    document.getElementById(s).style.display = (itemToBeVisible == s) ? '' : 'none';
  }
  function expandVisible(s) { makeVisible(expand(s)); }
  var i;
  switch(itemToBeVisible) {
    case 'filtereventdiv':
    case 'filterpeoplediv':
    case 'filterpreternaturaldiv':
    case 'filterlocationdiv':
    case 'filtertextdiv':
      ;['event', 'people', 'preternatural', 'location', 'text'].forEach(expandVisible);
      break;
    case 'filterpeopletypediv':
    case 'filterpeoplelistdiv':
      ;['peopletype', 'peoplelist'].forEach(expandVisible);
      break;
    case 'filterpreternaturaltypediv':
    case 'filterpreternaturalnamediv':
      ;['preternaturaltype', 'preternaturalname'].forEach(expandVisible);
      break;
    case 'filtertextfirstauthordiv':
    case 'filtertextfirstlistdiv':
    case 'filtertextsecondauthordiv':
    case 'filtertextsecondlistdiv':
    case 'filtertextthirdauthordiv':
    case 'filtertextthirdlistdiv':
      ;['textfirstauthor', 'textfirstlist', 'textsecondauthor', 'textsecondlist', 'textthirdauthor', 'textthirdlist'].forEach(expandVisible)
      break;
  }
}


function filterCheckBoxChange(clickedItem) {
  highlightfilterdiv(clickedItem);
  var elt = document.getElementById(clickedItem + 'ChBox'), thisChecked = elt.checked;
  elt.style.opacity = 1;
  function checkOpacity(s) {
    s = document.getElementById('filter' + s + 'divChBox');
    s.checked = thisChecked;
    s.style.opacity = 1;
  }
  switch(clickedItem) {
    case 'filtereventdiv':
      break;
    case 'filterpeoplediv':
      ;['peopletype', 'peoplelist'].forEach(checkOpacity);
      break;
    case 'filterpreternaturaldiv':
      ;['preternaturaltype', 'preternaturalname'].forEach(checkOpacity);
      break;
    case 'filtertextdiv':
      ;['textfirstauthor', 'textsecondauthor', 'textthirdauthor',
        'textfirstlist', 'textsecondlist', 'textthirdlist'].forEach(checkOpacity);
      break;
  }
  filterUpdateChildCheckBox(clickedItem);
  updateParentCheckboxStatus();
}


function filterChildCheckBoxChange(clickedItem, clickedItemID) {
  var thisChecked = document.getElementById(clickedItem + clickedItemID).checked;
  function doFilter(s) {
    var numclickedName = 'numclickedfilter' + s + 'div',
        numclicked = eval(numclickedName),
        cond = numclicked == 0 || numclicked == eval('numOffilter' + s + 'div');
    // numclickedfilter*div += thisChecked ? 1 : -1
    eval(numclickedName + ' += ' + (thisChecked ? '' : '-') + '1');
    s = document.getElementById('filter' + s + 'divChBox');
    s.checked = cond ? thisChecked : 'true';
    s.style.opacity = cond ? 1 : 0.33;
  }
  switch(clickedItem) {
    case 'eventTypelist':
      doFilter('event');
      break;
    case 'personTypelist':
      doFilter('peopletype');
      break;
    case 'witchlist':
      doFilter('peoplelist');
      break;
    case 'preternaturalTypelist':
      doFilter('preternaturaltype');
      break;
    case 'mbeinglist':
      doFilter('preternaturalname');
      break;
    case 'locationlist':
      doFilter('location');
      break;
    case 'firstauthorlist':
      doFilter('textfirstauthor');
      break;
    case 'firsttitlelist':
      doFilter('textfirstlist');
      break;
    case 'secondauthorlist':
      doFilter('textsecondauthor');
      break;
    case 'thirdtitlelist':
      doFilter('textthirdlist');
      break;
  }
  updateParentCheckboxStatus();
}


function updateParentCheckboxStatus() {
  function fullname(s) { return 'filter' + s + 'divChBox'; }
  function doFilter(cond, s, sList) {
    with (document) {
      s = getElementById(s);
      s.checked = cond ? 'true' : getElementById(fullname(sList));
      s.style.opacity = cond ? 0.33 : 1;
    }
  }
  function noMatch() {
    var elt, i = 0;
    with (document) {
      var checked = getElementById(fullname(arguments[0])).checked;
      for ( ; i < arguments.length; ++i) {
        elt = getElementById(fullname(arguments[i]));
        if (elt.style.opacity != 1 || elt.checked != checked) return true;
      }
    }
    return false;
  }
  doFilter(noMatch('peopletype', 'peoplelist'), 'people', 'peoplelist');
  doFilter(noMatch('preternaturaltype', 'preternaturalname'), 'preternatural', 'preternaturalname');
  doFilter(noMatch('textfirstauthor', 'textsecondauthor', 'textthirdauthor', 'textfirstlist', 'textsecondlist', 'textthirdlist'), 'text', 'textthirdlist');
}


function filterUpdateChildCheckBox(clickedItem) {
  var thisChecked = document.getElementById(clickedItem + 'ChBox').checked;
  function fullname(type, name) {
    return 'num' + type + 'filter' + name + 'div';
  }
  function setChecked(filterName, listName) {
    for (var i = 0, n = eval(fullname('Of', filterName)); i < n; ++i)
      document.getElementById(listName + i).checked = thisChecked;
  }
  function setNumclicked(s) {
    eval(fullname('clicked', s) + ' = ' + (thisChecked ? eval(fullname('Of', s)) : 0));
  }

  // e.g. filtereventdiv => event
  clickedItem = clickedItem(6, clickedItem.length -3);
  var flag;
  switch (clickedItem) {
    case 'event':
      setChecked('event', 'eventTypelist');
      setNumclicked('event');
      break;
    case 'people': case 'peopletype': case 'peoplelist':
      flag = clickedItem == 'peopletype' ? 2 : clickedItem == 'peoplelist' ? 3 : 6;
      if (flag % 2 == 0) setChecked('peopletype', 'personTypelist');
      if (flag % 3 == 0) setChecked('peoplelist', 'witchlist');
      ;['peopletype', 'peoplelist'].forEach(setNumclicked);
      break;
    case 'preternatural': case 'preternaturaltype': case 'preternaturalname':
      flag = clickedItem == 'preternaturaltype' ? 2 : clickedItem == 'preternaturalname' ? 3 : 6;
      if (flag % 2 == 0) setChecked('preternaturaltype', 'preternaturalTypelist');
      if (flag % 3 == 0) setChecked('preternaturalname', 'mbeinglist');
      ;['preternaturaltype', 'preternaturalname'].forEach(setNumclicked);
      break;
    case 'location':
      setChecked('location', 'locationlist');
      setNumclicked('location');
      break;
    case 'text': case 'textfirstauthor': case 'textfirstlist': case 'textsecondauthor':
    case 'textsecondlist': case 'textthirdauthor': case 'textthirdlist':
      flag = clickedItem == 'textfirstauthor' ? 2 : clickedItem == 'textfirstlist' ? 3 :
             clickedItem == 'textsecondauthor' ? 5 : clickedItem == 'textsecondlist' ? 7 :
             clickedItem == 'textthirdauthor' ? 11 : clickedItem == 'textthirdlist' ? 13 : 30030;
      if (flag % 2 == 0) setChecked('textfirstauthor', 'firstauthorlist');
      if (flag % 3 == 0) setChecked('textfirstlist', 'firsttitleList');
      if (flag % 5 == 0) setChecked('textsecondauthor', 'secondauthorlist');
      if (flag % 7 == 0) setChecked('textsecondlist', 'secondtitleList');
      if (flag % 11 == 0) setChecked('textthirdauthor', 'thirdauthorlist');
      if (flag % 13 == 0) setChecked('textthirdlist', 'thirdtitleList');
      ;['textfirstauthor', 'textfirstlist', 'textsecondauthor', 'textsecondlist',
        'textthirdauthor', 'textthirdlist'].forEach(setNumclicked);
  }
}


function resetFilter() {
  function fullname(type, name) {
    return 'num' + type + 'filter' + name + 'div';
  }
  function decNumclicked(filterName, listName) {
    for (var numOf = eval(fullname('Of', filterName)),
             clickedName = fullname('clicked', filterName),
             numclicked = eval(clickedName),
             elt,
             i = 0; i < numOf && numclicked != 0; ++i) {
      elt = document.getElementById(listName + i);
      if (elt.checked) {
        elt.checked = '';
        --numclicked;
      }
    }
    eval(clickedName + ' = ' + numclicked);
  }
  ;[['event', 'eventTypelist'], ['peopletype', 'personTypelist'], ['peoplelist', 'witchlist'],
    ['preternaturaltype', 'preternaturalTypelist'], ['preternaturalname', 'mbeinglist'],
    ['location', 'locationlist'], ['textfirstauthor', 'firstauthorlist'], ['textfirstlist', 'firsttitleList'],
    ['textsecondauthor', 'secondauthorlist'], ['textsecondlist', 'secondtitleList'], ['textthirdauthor', 'thirdauthorlist'],
    ['textthirdlist', 'thirdtitleList']].forEach(function (a) { decNumclicked(a[0], a[1]); });
  var lst = ['event', 'people', 'peopletype', 'peoplelist', 'preternatural', 'preternaturaltype', 'preternaturalname', 'location',
             'text', 'textfirstauthor', 'textfirstlist', 'textsecondauthor', 'textsecondlist', 'textthirdauthor', 'textthirdlist'];
  lst.forEach(function (s) {
      var elt = document.getElementById('filter' + s + 'divChBox')
      elt.checked = '';
      elt.style.opacity = 1;
    });
  lst.forEach(function (s) { eval('numclickedfilter' + s + 'div = 0'); });
}

///////////////////////////////////////////////
//////////////LEGEND///////////////////////////


var thisIsFirstTimeLegendIsCalled = 1;

function openLegend() {
  var elt = document.getElementById('legendDIV');
  if (thisIsFirstTimeLegendIsCalled == 1) {
    thisIsFirstTimeLegendIsCalled = 0;
    var ajaxRequest;
    try {
      ajaxRequest = new XMLHttpRequest();
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
          alert("Browser Problem");
          return false;
        }
      }
    }
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) {
        elt.innerHTML = ajaxRequest.responseText;
      }
    }
    ajaxRequest.open("GET", "getlegend.html" , true);
    ajaxRequest.send(null); 		
  }
  elt.style.visibility = ''; 
}


function hideLegend() {
  document.getElementById('legendDIV').style.visibility = 'hidden'; 
}


function highlightlegenddiv(clickedLink) {
  ;['event', 'people', 'preternatural', 'other'].forEach(function (s) {
      s = 'legenddiv' + s;
      document.getElementById(s).style.display = clickedLink == s ? '' : 'none';
    });
}

///////////////////////////////////////////////
/////////////THROWING BONES////////////////////

var myBasketCard = new Array(); 
var myBasketDeck = new Array();
var sizeOfBasket = 100;
var darkWindowIsOn = 0;
var lastQuerySent = "";

for (i = 0; i < sizeOfBasket; i++) {
  myBasketCard[i] = -1;
  myBasketDeck[i] = -1;
}


function throw_initialize() {
  throw_timedecksdiv(1560, 1570, "Throw");
}


function newAjaxRequest() {
  var ajaxRequest;
  try { ajaxRequest = new XMLHttpRequest(); } catch (e) {
    try { ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {
      try { ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  return ajaxRequest;
}


function throw_timecontentdiv(startYear, endYear, throwOrHTMLF) {
  if (document.getElementById('tieToTimeline').checked) {
    lastQuerySent = lastQuerySent + "&tieToTimeline=Yes" + "&startYear=" + startYear + "&endYear=" + endYear;
    throw_fileterOntimecontentdiv(lastQuerySent);
  } else {
    var ajaxRequest = newAjaxRequest();
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) {
        document.getElementById('timecontentdiv').innerHTML = ajaxRequest.responseText;
      }
    }
    ajaxRequest.open("GET", "gettimenav.php?id=" + startYear + "&endYear=" + endYear, true);
    ajaxRequest.send(null); 
  }
}


function throw_timedecksdiv(startYear, endYear, throwOrHTMLF) {
  var ajaxRequest = newAjaxRequest();
  function onreadystatechange() {
    if (ajaxRequest.readyState == 4) {
      document.getElementById('deckcontentdiv').innerHTML = ajaxRequest.responseText.substr(10, ajaxRequest.responseText.length - 10);
      setTimeNavHeight(ajaxRequest.responseText.substr(0, 10));
      fireUpToolTip();
    }
  }
  if (throwOrHTMLF == "Throw") {
    if (document.getElementById('tieToTimeline').checked) {
      lastQuerySent = lastQuerySent + "&tieToTimeline=Yes" + "&startYear=" + startYear + "&endYear=" + endYear;
      ajaxRequest.onreadystatechange = onreadystatechange;
      ajaxRequest.open("GET", "getfiltercontent.php?" + lastQuerySent, true);
      ajaxRequest.send(null); 
    } else {
      ajaxRequest.onreadystatechange = onreadystatechange;
      ajaxRequest.open("GET", "gettablecontent.php?id=" + startYear + "&endYear=" + endYear, true);
      ajaxRequest.send(null); 
    }
  } else if(throwOrHTMLF == "HTMLF") {
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) {
        init(ajaxRequest.responseText);
      }
    }
    ajaxRequest.open("GET", "getgraph.php?&startYear=" + startYear + "&endYear=" + endYear, true);
    ajaxRequest.send(null); 
  } else {
    loadFilter("&startYear=" + startYear + "&endYear=" + endYear, "timeline");
  }
}


function generateFilterDecks() {
  var foundSomething;
  var itemCounter;

  if (thisIsFirstTimeFiltersAreCalled != 1) {
    ;['firsttitleList', 'secondtitleList', 'thirdtitleList', 'firstauthorlist',
      'secondauthorlist', 'thirdauthorlist', 'witchlist', 'locationlist',
      'mbeinglist', 'personTypelist', 'preternaturalTypelist',
      'eventTypelist'].forEach(function (s) {
        myquerydata = '&' + s + '=';
        itemCounter = 0;
        foundSomething = 0;
        var elt;
        while (true) {
          elt = document.getElementById(s + itemCounter);
          if (!elt) break;
          if (elt.checked) {
            myquerydata += elt.value + '_';
            foundSomething = 1;
          }
          ++itemCounter;
        }
        if (foundSomething == 0) myquerydata += '-1_';
      });
    ;['witch', 'mbeing', 'firstauthor'].forEach(function (s) {
        s += 'listAnonym';
        if (document.getElementById(s).checked)
          myquerydata += '&' + s + '=Yes';
      });
  } else {
    myquerydata = "&firsttitleList=-1_&secondtitleList=-1_&thirdtitleList=-1_&firstauthorlist=-1_&secondauthorlist=-1_&thirdauthorlist=-1_&witchlist=-1_&locationlist=-1_&mbeinglist=-1_&personTypelist=-1_&preternaturalTypelist=-1_&eventTypelist=-1_";
  }

  if (document.getElementById('tieToTimeline').checked)
    myquerydata += "&tieToTimeline=Yes";

  if (document.getElementById('keywordsearch').value != "")
    myquerydata += "&keywordsearch=" + document.getElementById('keywordsearch').value;

  myquerydata += "&throwOrHTMLF=" + throwOrHTMLF;

  lastQuerySent = myquerydata;

  generateDeckSendQuery(lastQuerySent);
  hideFilter();
}


function generateDeckSendQuery(myquerydata) {
  var ajaxRequest = newAjaxRequest();
  if (throwOrHTMLF == "Throw" || throwOrHTMLF == "Fulltext") {
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) {
        if (throwOrHTMLF == "Throw") {
          document.getElementById('deckcontentdiv').innerHTML = ajaxRequest.responseText.substr(10,ajaxRequest.responseText.length - 10);
          setTimeNavHeight(ajaxRequest.responseText.substr(0,10));
          fireUpToolTip();
        } else {
          document.getElementById('searchcontentdiv').innerHTML = ajaxRequest.responseText;
        }
      }
    }
    ajaxRequest.open("GET", "getfiltercontent.php?" + myquerydata, true);
    ajaxRequest.send(null); 
    if (throwOrHTMLF == "Throw")
      throw_fileterOntimecontentdiv(myquerydata);
  } else if (throwOrHTMLF == "HTMLF") {
    ajaxRequest.onreadystatechange = function() {
      if(ajaxRequest.readyState == 4) init(ajaxRequest.responseText);
    }
    ajaxRequest.open("GET", "getgraphusingfilters.php?" + myquerydata, true);
    ajaxRequest.send(null); 
  } else {
    loadFilter(myquerydata, "filter");
  }
}


function throw_fileterOntimecontentdiv(query) {
  var ajaxRequest;  
  try {
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4) {
      document.getElementById('timecontentdiv').innerHTML = ajaxRequest.responseText;
    }
  }
  ajaxRequest.open("GET", "getfiltercontentForTimeLine.php?" + query, true);
  ajaxRequest.send(null); 
}


function openDeck(assertionID) {
  jumpToTop();
  grayOut(true, {'zindex':'50', 'bgcolor':'#000000', 'opacity':'80'});
  openUpTheDeck(assertionID);

  lastHash = throwOrHTMLF + '-' + assertionID;
  jQuery.history.load(lastHash);
}


function showIndividualCard(assertionType, assertionID) {
  jumpToTop();
  grayOut(true, {'zindex':'55', 'bgcolor':'#000000', 'opacity':'80'});
  openUpTheCard(assertionType, assertionID);

  lastHash = throwOrHTMLF + '--' + assertionType + '-' + assertionID;
  jQuery.history.load(lastHash);
}


function bodyResized() {
  if (darkWindowIsOn == 1) {
    grayOut(false, {'zindex':'50', 'bgcolor':'#000000', 'opacity':'80'});
    grayOut(true, {'zindex':'50', 'bgcolor':'#000000', 'opacity':'80'});

    document.getElementById('frontDeckPage').style.width = getBrowserWidth() + "px"; 
  }
}


function grayOut(vis, options) {
  if (vis) darkWindowIsOn = 1;
  else darkWindowIsOn = 0;
  // Pass true to gray out screen, false to ungray
  // options are optional.  This is a JSON object with the following (optional) properties
  // opacity:0-100	// Lower number = less grayout higher = more of a blackout 
  // zindex: #	    // HTML elements with a higher zindex appear on top of the gray out
  // bgcolor: (#xxxxxx)    // Standard RGB Hex color code
  // grayOut(true, {'zindex':'50', 'bgcolor':'#0000FF', 'opacity':'70'});
  // Because options is JSON opacity/zindex/bgcolor are all optional and can appear
  // in any order.  Pass only the properties you need to set.
  var options = options || {}; 
  var zindex = options.zindex || 50;
  var opacity = options.opacity || 70;
  var opaque = (opacity / 100);
  var bgcolor = options.bgcolor || '#000000';
  var dark=document.getElementById('darkenScreenObject' + zindex);
  if (!dark) {
    // The dark layer doesn't exist, it's never been created.  So we'll
    // create it here and apply some basic styles.
    // If you are getting errors in IE see: http://support.microsoft.com/default.aspx/kb/927917
    var tbody = document.getElementsByTagName("body")[0];
    var tnode = document.createElement('div');	  // Create the layer.
        tnode.style.position='absolute';	        // Position absolutely
        tnode.style.top='0px';			// In the top
        tnode.style.left='0px';		        // Left corner of the page
        tnode.style.overflow='hidden';		 // Try to avoid making scroll bars	   
        tnode.style.display='none';		    // Start out Hidden
        tnode.id='darkenScreenObject'+ zindex;		 // Name it so we can find it later
    tbody.appendChild(tnode);			 // Add it to the web page
    dark=document.getElementById('darkenScreenObject' + zindex);  // Get the object.
  }
  if (vis) {
    // Calculate the page width and height 
    if( document.body && ( document.body.scrollWidth || document.body.scrollHeight ) ) {
        var pageWidth = document.body.scrollWidth+'px';
        var pageHeight = document.body.scrollHeight+'px';
    } else if( document.body.offsetWidth ) {
      var pageWidth = document.body.offsetWidth+'px';
      var pageHeight = document.body.offsetHeight+'px';
    } else {

       var pageWidth='100%';
       var pageHeight='100%';
    }   
    pageWidth = getBrowserWidth() + "px";  

    if (divObj = document.getElementById("timecontentdiv"))
      timecontentdivHeight = document.getElementById('timecontentdiv').style.height.substr(0,document.getElementById('timecontentdiv').style.height.length-2);
    else 
      timecontentdivHeight = 0;

    var maxHeight = (parseInt(getBrowserHeight()) > parseInt(timecontentdivHeight))? parseInt(getBrowserHeight()): parseInt(timecontentdivHeight);
    maxHeight += 600;
    maxHeight = (maxHeight > 1200)? maxHeight: 1200;
    pageHeight = maxHeight + "px";  

    //set the shader to cover the entire page and make it visible.
    dark.style.opacity=opaque;		    
    dark.style.MozOpacity=opaque;		 
    dark.style.filter='alpha(opacity='+opacity+')'; 
    dark.style.zIndex=zindex;        
    dark.style.backgroundColor=bgcolor;  
    dark.style.width= pageWidth;
    dark.style.height= pageHeight;
    dark.style.display='block';		        

  } else {
    dark.style.display='none';
  }
}


function openUpTheDeck(newID) {
  var ajaxRequest;  
  try {
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  var timecontentdivHeight;
  with (document) {
    if (getElementById("container"))
      timecontentdivHeight = getElementById('timecontentdiv').style.height.substr(0, getElementById('timecontentdiv').style.height.length-2);
    else
      var timecontentdivHeight = 0;
  }

  var maxHeight = (parseInt(getBrowserHeight()) > parseInt(timecontentdivHeight))? parseInt(getBrowserHeight()): parseInt(timecontentdivHeight);
  maxHeight += 600;
  maxHeight = (maxHeight > 1200)? maxHeight: 1200;
  var pageHeight = maxHeight + "px";  

  var divTag = document.createElement("div"); 
  divTag.id = "frontDeckPage";
  divTag.style.zIndex= 51;
  divTag.style.position = 'absolute';  
  divTag.style.left = "0px";  
  divTag.style.top = "0px";  
  divTag.style.height = pageHeight;  
  divTag.style.width = getBrowserWidth() + "px";  
  divTag.style.overflow = 'auto';

  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4) {
      divTag.innerHTML = ajaxRequest.responseText;

      if (typeof newID === typeof 1)
        loadContent('events', newID, '-1');
      else {
        if (newID.substr(0,1) == "p") 
          loadContent('eventforpeople', newID.substr(1), '-1');
        else if ( newID.substr(0,1) == "d") 
          loadContent('eventformagical', newID.substr(1), '-1');
        else 
          loadContent('events', newID.substr(1), '-1');
      }
      initializePopUp();
      for (i = 0; i < 50; i++) {
        var theHandle = document.getElementById('handleCard'+i);
        var theRoot   = document.getElementById('rootCard'+i);
        Drag.init(theHandle, theRoot);
      }
    }
  }

  ajaxRequest.open("GET", "../throwing-bones/getopenedDeck.php?&id=" + newID, true);
  ajaxRequest.send(null); 

  document.body.appendChild(divTag);
}


function openUpTheCard(assertionType, assertionID) {
  var ajaxRequest;  
  try {
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  var maxHeight = 1200;
  var pageHeight = maxHeight + "px";  

  var frontCardPageDiv=document.getElementById('frontCardPage');
  if (!frontCardPageDiv) {
    var divTag = document.createElement("div"); 
    divTag.id = "frontCardPage";
    divTag.style.zIndex= 56;
    divTag.style.position = 'absolute';  
    divTag.style.left = "0px";  
    divTag.style.top = "0px";  
    divTag.style.height = pageHeight;  
    divTag.style.width = getBrowserWidth() + "px";  
    divTag.style.overflow = 'auto';
    document.body.appendChild(divTag);
  }

  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4) {
      document.getElementById("frontCardPage").innerHTML = "<div style='position: absolute; top: 30px; left: 20px; width: 1000px; height: 680px; margin: 10px; background: white; border-radius: 20px; -moz-border-radius: 20px;'> \
        </div> \
        <div id='cardContentDiv' style='position: absolute; height: 640px; width: 990px; top: 55px; left: 35px; overflow: auto'>" + ajaxRequest.responseText + "</div> \
        <div style='position: absolute; top: 40px; left: 950px;	height: 80px; overflow: auto;'> \
        <A HREF='javascript:closeOpenedCard()'><img width=60 heigth=60 src='http://witching.org/throwing-bones/images/close.png' border='none' /></A> \
        </div>";
    }
  }
  ajaxRequest.open("GET", "offlinecontent/" + assertionType + "-" + assertionID + ".xml" , true);

  ajaxRequest.send(null); 
}


function closeOpenedDeck() {
  with (document) { body.removeChild(getElementById("frontDeckPage")); }
  //obj = document.getElementById("frontDeckPage");
  //document.body.removeChild(obj);
  grayOut(false, {'zindex':'50', 'bgcolor':'#000000', 'opacity':'80'});
  window.onscroll = null;
  window.onmousewheel = null;

  lastHash = throwOrHTMLF;
  jQuery.history.load(lastHash);
}


function closeOpenedCard() {
  with (document) { body.removeChild(getElementById("frontCardPage")); }
  //obj = document.getElementById("frontCardPage");
  //document.body.removeChild(obj);
  grayOut(false, {'zindex':'55', 'bgcolor':'#000000', 'opacity':'80'});
  window.onscroll = null;
  window.onmousewheel = null;

///???????????????????
}


function loadContent(category, assertionid, picturefile) {
  var ajaxRequest;  
  try {
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4) {
      document.getElementById('cardContentDiv').innerHTML = "<table border=0 cellpadding=15><tr><td>" + ajaxRequest.responseText + "</td></tr></table>";
    }
  }
  ajaxRequest.open("GET", "offlinecontent/" + category + "-" +assertionid + ".xml" , true);
  ajaxRequest.send(null); 

  if (picturefile != "-1")
    document.getElementById('myWaterMarkDiv').innerHTML = "<img border=\"none\" src=\"../throwing-bones/newcards/" + picturefile +".png\" />";
}


function addToBasket(divID, assertionID, cardType) {
  myBasketDeck[divID] = assertionID;
  myBasketCard[divID] = cardType;

  document.getElementById('handleCard'+divID).innerHTML = document.getElementById('hiddenSmallDIV'+divID).innerHTML;
}


function removeFromBasket(divID, assertionID, cardType) {
  myBasketDeck[divID] = -1;
  myBasketCard[divID] = -1;

  document.getElementById('handleCard'+divID).innerHTML = document.getElementById('hiddenBigDIV'+divID).innerHTML;
}


function printEmailTheBasket() {
  var divId = createNewWindow(340,240,100,100);
  document.getElementById('windowContent' + divId).style.overflow = 'auto';
  document.getElementById('windowContent' + divId).innerHTML = "<H2>Basket</H2>Please enter your email address in the box below, if you wish to recieve an email with the contents of the basket:<BR><BR><input name=\"useremail\" id=\"useremail\" type=\"Text\" size=\"30\" ><BR><BR><input type=BUTTON value=\"Email me the Basket\" onClick=\"emailBasket('email');\"><BR><BR>Or download the PDF version of the content of the basket:<BR><BR><input type=BUTTON value=\"Download PDF\" onClick=\"emailBasket(1)\">";
}


function emailBasket(emailOrDownload) {
  if (emailOrDownload == 1)
    emailOrDownload = "download";

  var ajaxRequest;  
  try {
    ajaxRequest = new XMLHttpRequest();
  } catch (e) {
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Browser Problem");
        return false;
      }
    }
  }
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4) {
      if (emailOrDownload == "email")
        alert("An email was sent to your account");
      else {
        downloadFileName = 'basketpdf/basket' + jsrandnum + '.pdf';
        window.open(downloadFileName,'mywindow');
        //alert("Please make sure you have enabled Pop up windows (Download will start in 3 seconds after pressing OK button).");
        //setTimeout("window.open('" + downloadFileName + "')", 3000);
      }
    }
  }
  query = "&cardType=";
  for(i = 0; i < sizeOfBasket; i++) {
    if (myBasketCard[i] != -1) query += myBasketCard[i] + "_";
  }

  query += "&assertionID=";
  for(i = 0; i < sizeOfBasket; i++) {
    if (myBasketCard[i] != -1) query += myBasketDeck[i] + "_";
  }

  query += "&useremail=" + document.getElementById('useremail').value;
  if (emailOrDownload == "email")
    query += "&emailOrDownload=email";
  else
    query += "&emailOrDownload=download";

  var jsrandnum =  Math.floor(Math.random()*1001);
  query += "&jsrandnum=" + jsrandnum;

  ajaxRequest.open("GET", "emailtheBasket.php?" + query, true);
  ajaxRequest.send(null); 
}


function getBrowserHeight() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  return myHeight;
}


function getBrowserWidth() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  return myWidth;
}


function jumpToTop() {
  window.scrollTo(0,0);
}


function setTimeNavHeight(decksOnTable) {
  document.getElementById('timecontentdiv').style.height = Math.max((( Math.ceil(decksOnTable/6))*180), 500) + "px";
}


function initializePopUp() {	
  //select all the a tag with name equal to modal
  $('a[name=modal]').click(function(e) {
      //Cancel the link behavior
      e.preventDefault();
		
      //Get the A tag
      var id = $(this).attr('href');
	
      //Get the screen height and width
      var maskHeight = $(document).height();
      var maskWidth = $(window).width();
	
      //Set heigth and width to mask to fill up the whole screen
      $('#mask').css({'width':maskWidth,'height':maskHeight});
		
      //transition effect		
      $('#mask').fadeIn(1000);	
      $('#mask').fadeTo("slow",0.8);	
	
      //Get the window height and width
      var winH = $(window).height();
      var winW = $(window).width();
	     
      //Set the popup window to center
      $(id).css('top',  winH/2-$(id).height()/2);
      $(id).css('left', winW/2-$(id).width()/2);
	
      //transition effect
      $(id).fadeIn(2000); 
  });
	
  //if close button is clicked
  $('.window .close').click(function (e) {
      //Cancel the link behavior
      e.preventDefault();
		
      $('#mask').hide();
      $('.window').hide();
  });		
	
  //if mask is clicked
  $('#mask').click(function () {
      $(this).hide();
      $('.window').hide();
  });			
}


///////////////////////////////////////////////////////////////////
////////// READING-LEAVES functions ///////////////////////////////
///////////////////////////////////////////////////////////////////


//var throwOrHTMLF = "HTMLF";
//window.onload = getgraph;

var canvas;
var context;
var edgeCanvas;
var edgeContext;
var shadowCanvas;
var shadowContext;
var miniatureCanvas;
var miniatureContext;

var rightmostNode;
var leftmostNode;
var bottommostNode;
var topmostNode;

var miniPadding = 0;
var initialzoomlevel = 13;
var zoomlevel = initialzoomlevel;
var zoomlevelMini = initialzoomlevel;
var zoomLowerLimit = 25;
var zoomUpperLimit = 1;
var shrinkageFactor = 1.2;

var pageOffsetStep = 100;
var pageOffsetX;
var pageOffsetY;

var cardarray;
var edgeArray;
var shadowToCardMap;
var cardarrayDescription;
var UniqueColor = {
  takenColors: {},
  formatColor: function(red, green, blue) {
    return '#' + ('0' + red.toString(16)).slice(-2) + ('0' + green.toString(16)).slice(-2) + ('0' + blue.toString(16)).slice(-2);
  },
  reserveUniqueColor: function() {
    var r = Math.random() * 255;
    var g = Math.random() * 255;
    var b = Math.random() * 255;
    var color = this.formatColor(r, g, b);
    if (this.takenColors[color] || (r + g + b) == 0)
      return this.reserveUniqueColor();
    return color;
  },
  releaseUniqueColor: function(color) {
    delete this.takenColors[color];
  }
};


function getNodeAt(x, y) {
  var data =shadowContext.getImageData(x, y, 1, 1).data;
  return shadowToCardMap[ UniqueColor.formatColor(data[0], data[1], data[2]) ];
}


	var CanvasEventHandler = {
		hoveredNode: null,
		dragableNode: null,
		dragStartX: null,
		dragStartY: null,
		mousedown: function(event) {
			canvas.style.cursor="move";
			this.dragableNode = getNodeAt(event.xScreen, event.yScreen);
			this.dragStartX = event.xScreen;
			this.dragStartY = event.yScreen;
			JT_destroy();
		},
		mousemove: function(event) {
			var returnedNode = getNodeAt(event.xScreen, event.yScreen);
			if (returnedNode == null && this.hoveredNode != null){
				JT_destroy();
				this.hoveredNode = null;
			}else if(returnedNode != null && returnedNode != this.hoveredNode){
				JT_destroy();
				var gameSpacePos = $('#canvas').offset();
				var mouseX = transformPointX(cardarray[returnedNode]["x"]) + gameSpacePos.left;
				var mouseY = transformPointY(cardarray[returnedNode]["y"]) + gameSpacePos.top; 
		 		JT_show("www.google.com","linkId",returnedNode,mouseX,mouseY);
				this.hoveredNode = returnedNode;
				drawCanvasEdges();
			}
		},
		mouseup: function(event) {
			canvas.style.cursor="";
			if ( Math.abs(event.xScreen - this.dragStartX)>20 || Math.abs(event.yScreen - this.dragStartY)>20 ){
				if (this.dragableNode != null){
					cardarray[this.dragableNode]["x"] += transformLength(event.xScreen - this.dragStartX)*shrinkageFactor;
					cardarray[this.dragableNode]["y"] += transformLength(event.yScreen - this.dragStartY)*shrinkageFactor;
				}else{
					pageOffsetX += event.xScreen - this.dragStartX;
					pageOffsetY += event.yScreen - this.dragStartY;
				}
				drawCanvas();
			}else if (this.hoveredNode != null)
				htmlfiveOpenCard( this.hoveredNode );

			this.focusedNode = null;
		},
		dblclick: function(event) {

			pageOffsetX -= (event.xScreen * (shrinkageFactor - 1)) * Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel));
			pageOffsetY -= (event.yScreen * (shrinkageFactor - 1)) * Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel));

			zoomFunction(-1);

		},
		DOMMouseScroll: function(event) {
			event.wheelDelta = event.detail * 120;
			this.mousewheel(event);
		},
		mousewheel: function(event) {
			if( event.wheelDelta > 0){
				pageOffsetX -= (event.xScreen * (shrinkageFactor - 1)) * Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel));
				pageOffsetY -= (event.yScreen * (shrinkageFactor - 1)) * Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel));
				zoomFunction(-1);
			}else{

				pageOffsetX -= (pageOffsetX-event.xScreen) * (shrinkageFactor - 1) * Math.pow(shrinkageFactor,(-initialzoomlevel+zoomlevel));
				pageOffsetY -= (pageOffsetY-event.yScreen) * (shrinkageFactor - 1) * Math.pow(shrinkageFactor,(-initialzoomlevel+zoomlevel));

				zoomFunction(+1);	
			}

		},
		handleEvent: function(event) {
			event.xScreen = event.clientX+ document.body.scrollLeft+ document.documentElement.scrollLeft- this.offsetLeft;
			event.yScreen = event.clientY+ document.body.scrollTop+ document.documentElement.scrollTop- this.offsetTop;
			if(this.CanvasEventHandler[event.type])
				this.CanvasEventHandler[event.type](event);
		}
	};


	function init(returnedgraph){

		canvas 		= document.getElementById('canvas');
		if (!canvas || !canvas.getContext) {alert("Your browser does not support HTML5!");return;}
		context 	= canvas.getContext('2d');
		edgeCanvas 	= document.getElementById('canvasedges');
		edgeContext 	= edgeCanvas.getContext('2d');
		shadowCanvas 	= document.getElementById('shadow');
		shadowContext 	= shadowCanvas.getContext('2d');
		miniatureCanvas = document.getElementById('miniature');
		miniatureContext = miniatureCanvas.getContext('2d');
		miniEdgeCanvas 	= document.getElementById('miniatureedges');
		miniEdgeContext	= miniEdgeCanvas.getContext('2d');


		canvas.onselectstart = function () { return false; }

		canvas.CanvasEventHandler = CanvasEventHandler;
		canvas.addEventListener('mousedown', CanvasEventHandler.handleEvent, false);
		canvas.addEventListener('mousemove', CanvasEventHandler.handleEvent, false);
		canvas.addEventListener('mouseup', CanvasEventHandler.handleEvent, false);
		canvas.addEventListener('dblclick', CanvasEventHandler.handleEvent, false);
		canvas.addEventListener('mousewheel', CanvasEventHandler.handleEvent, false); // Safari and Chrome
		canvas.addEventListener('DOMMouseScroll', CanvasEventHandler.handleEvent, false); // Firefox

		window.onkeydown = CanvasEventHandler.handleEvent;


		cardarray = null;
		edgeArray = null;
		shadowToCardMap = null;

		cardarray = new Array();
		edgeArray = new Array();
		shadowToCardMap = new Array();
		cardarrayDescription = new Array();

		pageOffsetY = 0;
		pageOffsetX = 0;
		thisIsFirstTimeJTisCalled = 1;
		//document.getElementById('testing').innerHTML = returnedgraph;

		eval (returnedgraph);
		drawCanvas();

	}

	function Anchor(thisx, thisy, thisname, thisfilename){

		cardarray[thisname] = new Array();
		cardarray[thisname]["x"] = thisx;
		cardarray[thisname]["y"] = thisy;
		cardarray[thisname]["filename"] = thisfilename;
		cardarray[thisname]["shadowcolor"] = UniqueColor.reserveUniqueColor();
		shadowToCardMap[cardarray[thisname]["shadowcolor"]] = thisname;

	}

	function Edge(thissource, thistarget, thisname){

		if (!((thissource in cardarray) && (thistarget in cardarray))) return;

		edgeArray[thisname] = new Array();
		edgeArray[thisname]["source"] = thissource;
		edgeArray[thisname]["target"] = thistarget;
		edgeArray[thisname]["controlX"] = (Math.random()*400 - 200);
		edgeArray[thisname]["controlY"] = (Math.random()*400 - 200);
	}

	function drawCanvas(){

		canvas.width = canvas.width;			/*Clear canvases*/
		shadowCanvas.width = shadowCanvas.width;
		miniatureCanvas.width = miniatureCanvas.width;
		miniEdgeCanvas.width = miniEdgeCanvas.width;

		for (key in cardarray)
			drawAnchor(cardarray[key]["x"], cardarray[key]["y"], cardarray[key]["filename"], cardarray[key]["shadowcolor"]);

		miniScale = document.getElementById('miniature').width / document.getElementById('canvas').width;
		miniatureContext.setTransform(miniScale, 0, 0, miniScale, miniScale * (miniPadding), miniScale * (miniPadding));

		for (key in cardarray)
			drawAnchorMini(cardarray[key]["x"], cardarray[key]["y"], cardarray[key]["filename"], cardarray[key]["shadowcolor"]);

		drawCanvasEdges();
	}

	function drawCanvasEdges(){

		miniScale = document.getElementById('miniature').width / document.getElementById('canvas').width;
		miniEdgeContext.setTransform(miniScale, 0, 0, miniScale, miniScale * (miniPadding), miniScale * (miniPadding));

		edgeCanvas.width = edgeCanvas.width;
		miniEdgeCanvas.width = miniEdgeCanvas.width;

		for (key in edgeArray){
			drawEdge(edgeArray[key]["source"],edgeArray[key]["target"],edgeArray[key]["controlX"],edgeArray[key]["controlY"]);
			drawEdgeMini(edgeArray[key]["source"],edgeArray[key]["target"],edgeArray[key]["controlX"],edgeArray[key]["controlY"]);
		}

		miniatureContext.strokeStyle = '#FF0000';
		miniatureContext.lineWidth = 3/miniScale;
		miniatureContext.strokeRect( transformLength(-pageOffsetX), transformLength(-pageOffsetY), transformLength(document.getElementById('canvas').width), transformLength(document.getElementById('canvas').height));
	}



	function drawAnchor(thisx, thisy, thisfilename, shadowcolor){
		var img = new Image();
		if (thisfilename.charAt(0) == 'd' || thisfilename.charAt(0) == 'p')	img.src = '../throwing-bones/thumbsroot/level' + zoomlevel + "/" + thisfilename;
		else									img.src = '../throwing-bones/thumbsroot/level' + Math.max(zoomlevel-1,zoomUpperLimit) + "/" + thisfilename;
		var zoomlevelAtTheTimeThisFunctionWasCalled = zoomlevel;				
		img.onload = function(){
			if (zoomlevelAtTheTimeThisFunctionWasCalled == zoomlevel){
				context.drawImage		(img,transformPointX(thisx) - this.height/2,transformPointY(thisy) - this.height/2);
				shadowContext.fillStyle = shadowcolor; 
				shadowContext.fillRect(transformPointX(thisx) - this.height/2, transformPointY(thisy) - this.height/2, this.height, this.height);
			}
		}
	}

	function drawAnchorMini(thisx, thisy, thisfilename, shadowcolor){
		var img = new Image();
		if (thisfilename.charAt(0) == 'd' || thisfilename.charAt(0) == 'p')	img.src = '../throwing-bones/thumbsroot/level' + zoomlevelMini + "/" + thisfilename;
		else									img.src = '../throwing-bones/thumbsroot/level' + Math.max(zoomlevelMini-1,zoomUpperLimit) + "/" + thisfilename;
		img.onload = function(){miniatureContext.drawImage (img,transformPointMini(thisx) - this.height/2,transformPointMini(thisy) - this.height/2);}
	}

	function drawEdge(thisSource, thisTraget, thisControlX, thisControlY){

		edgeContext.beginPath();
		edgeContext.moveTo(transformPointX(cardarray[thisSource]["x"]), transformPointY(cardarray[thisSource]["y"]));
		edgeContext.quadraticCurveTo(transformPointX(cardarray[thisSource]["x"]+thisControlX), transformPointY(cardarray[thisSource]["y"]+thisControlY),
			transformPointX(cardarray[thisTraget]["x"]), transformPointY(cardarray[thisTraget]["y"]));
		if (CanvasEventHandler.hoveredNode == thisSource || CanvasEventHandler.hoveredNode == thisTraget){
			edgeContext.strokeStyle = "#FF0000";
			edgeContext.lineWidth = 2;
		}else{
			edgeContext.strokeStyle = "#000000";
			edgeContext.lineWidth = 1;
		}
		edgeContext.stroke();

	}

	function drawEdgeMini(thisSource, thisTraget, thisControlX, thisControlY){

		miniScale = document.getElementById('miniature').width / document.getElementById('canvas').width;
		miniEdgeContext.setTransform(miniScale, 0, 0, miniScale, miniScale * (miniPadding), miniScale * (miniPadding));
		miniEdgeContext.beginPath();
		miniEdgeContext.moveTo(transformPointMini(cardarray[thisSource]["x"]), transformPointMini(cardarray[thisSource]["y"]));
		miniEdgeContext.quadraticCurveTo(transformPointMini(cardarray[thisSource]["x"]+thisControlX), transformPointMini(cardarray[thisSource]["y"]+thisControlY),
			transformPointMini(cardarray[thisTraget]["x"]), transformPointMini(cardarray[thisTraget]["y"]));
		if (CanvasEventHandler.hoveredNode == thisSource || CanvasEventHandler.hoveredNode == thisTraget){
			miniEdgeContext.strokeStyle = "#FF0000";
			miniEdgeContext.lineWidth = 2;
		}else{
			miniEdgeContext.strokeStyle = "#000000";
			miniEdgeContext.lineWidth = 1;
		}
		miniEdgeContext.stroke();
	}

	function zoomFunction(zoominzoomout){
		zoomlevel += zoominzoomout;
		if (zoomlevel < zoomUpperLimit )
			zoomlevel = zoomUpperLimit;
		else if(zoomlevel > zoomLowerLimit)
			zoomlevel = zoomLowerLimit;
		drawCanvas();
	}

	function transformPointX(value){
		return value*Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel))+pageOffsetX;
	}	
	function transformPointY(value){
		return value*Math.pow(shrinkageFactor,(initialzoomlevel-zoomlevel))+pageOffsetY;
	}

	function transformPointMini(value){
		return value*Math.pow(shrinkageFactor,(+initialzoomlevel-zoomlevelMini));
	}	

	function transformLength(value){
		return value*Math.pow(shrinkageFactor,(-zoomlevelMini+zoomlevel));
	}

	function moveFunction(direction){
		if (direction == 'l')	pageOffsetX -= pageOffsetStep;
		if (direction == 'r')	pageOffsetX += pageOffsetStep;
		if (direction == 'u')	pageOffsetY -= pageOffsetStep;
		if (direction == 'd')	pageOffsetY += pageOffsetStep;
		drawCanvas();
	}




function getgraph(){
	throw_timedecksdiv(1560, 1570, "HTMLF");
}


function htmlfiveOpenCard(assertionID){
	grayOut(true, {'zindex':'50', 'bgcolor':'#000000', 'opacity':'80'});
	openUpTheDeck(assertionID);
}


//////////////////////////////////////////////////////////////////////
////////////////////// Mapping ///////////////////////////////////////
//////////////////////////////////////////////////////////////////////



var openWindow = new Array();
var idWindowMap = new Array();


	var getCurrentMapTypeVar = G_NORMAL_MAP;

	var icon1 = new GIcon(); 
	icon1.image = 'http://witching.org/throwing-bones/newicon/map_magics_4361ac.png';
	icon1.iconSize = new GSize(20, 27);
	icon1.shadowSize = new GSize(12, 22);
	icon1.iconAnchor = new GPoint(20, 17);
	icon1.infoWindowAnchor = new GPoint(16, 20);

	var icon2 = new GIcon(); 
	icon2.image = 'http://witching.org/throwing-bones/newicon/supernatual_map.png';
	icon2.iconSize = new GSize(20, 27);
	icon2.shadowSize = new GSize(12, 22);
	icon2.iconAnchor = new GPoint(20, 17);
	icon2.infoWindowAnchor = new GPoint(16, 20);

	var icon3 = new GIcon(); 
	icon3.image = 'http://witching.org/throwing-bones/newicon/map_damage_5959a7.png';
	icon3.iconSize = new GSize(20, 27);
	icon3.shadowSize = new GSize(12, 22);
	icon3.iconAnchor = new GPoint(20, 17);
	icon3.infoWindowAnchor = new GPoint(16, 20);

	var icon4 = new GIcon(); 
	icon4.image = 'http://witching.org/throwing-bones/newicon/map_maleficium_3a5b6a.png';
	icon4.iconSize = new GSize(20, 27);
	icon4.shadowSize = new GSize(12, 22);
	icon4.iconAnchor = new GPoint(20, 17);
	icon4.infoWindowAnchor = new GPoint(16, 20);

	var icon5 = new GIcon(); 
	icon5.image = 'http://witching.org/throwing-bones/newicon/map_law_dbab29.png';
	icon5.iconSize = new GSize(20, 27);
	icon5.shadowSize = new GSize(12, 22);
	icon5.iconAnchor = new GPoint(20, 17);
	icon5.infoWindowAnchor = new GPoint(16, 20);

	var icon6 = new GIcon(); 
	icon6.image = 'http://witching.org/throwing-bones/newicon/map_testing_c76528.png';
	icon6.iconSize = new GSize(20, 27);
	icon6.shadowSize = new GSize(12, 22);
	icon6.iconAnchor = new GPoint(20, 17);
	icon6.infoWindowAnchor = new GPoint(16, 20);

	var icon7 = new GIcon(); 
	icon7.image = 'http://witching.org/throwing-bones/newicon/death_solid.png';
	icon7.iconSize = new GSize(20, 27);
	icon7.shadowSize = new GSize(12, 22);
	icon7.iconAnchor = new GPoint(20, 17);
	icon7.infoWindowAnchor = new GPoint(16, 20);

	var icon8 = new GIcon(); 
	icon8.image = 'http://witching.org/throwing-bones/newicon/healing_solid.png';
	icon8.iconSize = new GSize(20, 27);
	icon8.shadowSize = new GSize(12, 22);
	icon8.iconAnchor = new GPoint(20, 17);
	icon8.infoWindowAnchor = new GPoint(16, 20);

	var customIcons = [];
	customIcons["1"] = icon1;
	customIcons["2"] = icon2;
	customIcons["3"] = icon3;
	customIcons["4"] = icon4;
	customIcons["5"] = icon5;
	customIcons["6"] = icon6;
	customIcons["7"] = icon7;
	customIcons["8"] = icon8;






function CustomGetTileUrlMercator(a, b) {
	var z = 17 - b;
	return "historic/mapMercator.php?myx=" + (a.x - 30) + "&myy=" + (a.y - 18);
}


function CustomGetTileUrlJohnSpeedBE(a, b) {
	var z = 17 - b;
	return "historic/mapJSBE.php?myx=" + (a.x - 30) + "&myy=" + (a.y - 18);
}

function CustomGetTileUrlJohnSpeedE(a, b) {
	var z = 17 - b;
	return "historic/mapJSE.php?myx=" + (a.x - 30) + "&myy=" + (a.y - 18);
}


function CustomGetTileUrlOrtelius(a, b) {
	var z = 17 - b;
	return "historic/mapengland.php?myx=" + (a.x - 30) + "&myy=" + (a.y - 18);
}

function loadFilter(myquerydata, timelineOrFilter) {

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



	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));


		/*Mercator Map*/
		var copyCollection = new GCopyrightCollection('Mercator');
		var copyright = new GCopyright(1, new GLatLngBounds(new GLatLng(-53.894991, -3.2540285), new GLatLng(-55.894991, -5.2540285)), 0, "©2010 uszkalo.com");
		copyCollection.addCopyright(copyright);

		var tilelayers = [new GTileLayer(copyCollection, 7, 7)];
		tilelayers[0].getTileUrl = CustomGetTileUrlMercator;

		var custommap = new GMapType(tilelayers, new GMercatorProjection(18), "Mercator", {
			errorMessage: "No chart data available"
		});
		map.addMapType(custommap);

		/*John Speed BE Map*/
		var copyCollectionJSBE = new GCopyrightCollection('Speed');
		var copyrightJSBE = new GCopyright(1, new GLatLngBounds(new GLatLng(-53.894991, -3.2540285), new GLatLng(-55.894991, -5.2540285)), 0, "©2010 uszkalo.com");
		copyCollectionJSBE.addCopyright(copyrightJSBE);

		var tilelayersJSBE = [new GTileLayer(copyCollectionJSBE, 7, 7)];
		tilelayersJSBE[0].getTileUrl = CustomGetTileUrlJohnSpeedBE;

		var custommapJSBE = new GMapType(tilelayersJSBE, new GMercatorProjection(18), "Speed", {
			errorMessage: "No chart data available"
		});
		map.addMapType(custommapJSBE);


		/*Ortelius Map*/
		var copyCollectionOrtelius = new GCopyrightCollection('Speed');
		var copyrightOrtelius = new GCopyright(1, new GLatLngBounds(new GLatLng(-53.894991, -3.2540285), new GLatLng(-55.894991, -5.2540285)), 0, "©2010 uszkalo.com");
		copyCollectionOrtelius.addCopyright(copyrightOrtelius);

		var tilelayersOrtelius = [new GTileLayer(copyCollectionOrtelius, 7, 7)];
		tilelayersOrtelius[0].getTileUrl = CustomGetTileUrlOrtelius;

		var custommapOrtelius = new GMapType(tilelayersOrtelius, new GMercatorProjection(18), "Ortelius", {
			errorMessage: "No chart data available"
		});
		map.addMapType(custommapOrtelius);




		map.addControl(new GLargeMapControl3D());
		map.addControl(new GMapTypeControl());


		map.setCenter(new GLatLng(52.721899, -1.875642), 7);
		map.setZoom(7);


		map.setMapType(getCurrentMapTypeVar);
		GEvent.addListener(map, "maptypechanged", function () {
			getCurrentMapTypeVar = map.getCurrentMapType();
		});



		if (timelineOrFilter == "filter"){
			GDownloadUrl("phpsqlajax_genxml.php?" + myquerydata, function (data) {

				var xml = GXml.parse(data);
				var markers = xml.documentElement.getElementsByTagName("marker");
				for (var i = 0; i < markers.length; i++) {
					var name = markers[i].getAttribute("name");
					var desc = markers[i].getAttribute("desc");
					var eventtype = markers[i].getAttribute("eventtype");
					var eventdate = markers[i].getAttribute("eventdate");
					var eventtypeorig = markers[i].getAttribute("eventtypeorig");
					var type = markers[i].getAttribute("type");
					var sourcetitle = markers[i].getAttribute("sourcetitle");
					var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
					var marker = createMarker(point, name, desc, type, eventtype, eventdate, eventtypeorig, sourcetitle);
					map.addOverlay(marker);
				}
			});

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){

					document.getElementById('mapcontent').innerHTML = ajaxRequest.responseText;


					$(document).ready(function() 
					    { 
					 	$.tablesorter.defaults.widgets = ['zebra'];
						$.tablesorter.defaults.sortList = [[2,0]];
					  	$("#myTable").tablesorter();
						$.tablesorter.defaults.sortList = [[1,0]];
					 	$("#totals").tablesorter(); 
					    } 
					); 

				}
			}
			ajaxRequest.open("GET", "loadtable.php?" + myquerydata , true);
			ajaxRequest.send(null); 




		}else{

			GDownloadUrl("getmaptimeline.php?" + myquerydata, function (data) {

				var xml = GXml.parse(data);
				var markers = xml.documentElement.getElementsByTagName("marker");
				for (var i = 0; i < markers.length; i++) {
					var name = markers[i].getAttribute("name");
					var desc = markers[i].getAttribute("desc");
					var eventtype = markers[i].getAttribute("eventtype");
					var eventdate = markers[i].getAttribute("eventdate");
					var eventtypeorig = markers[i].getAttribute("eventtypeorig");
					var type = markers[i].getAttribute("type");
					var sourcetitle = markers[i].getAttribute("sourcetitle");
					var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
					var marker = createMarker(point, name, desc, type, eventtype, eventdate, eventtypeorig, sourcetitle);
					map.addOverlay(marker);
				}
			});



			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){

					document.getElementById('mapcontent').innerHTML = ajaxRequest.responseText;
		

					$(document).ready(function() 
					    { 
					 	$.tablesorter.defaults.widgets = ['zebra'];
						$.tablesorter.defaults.sortList = [[2,0]];
					  	$("#myTable").tablesorter();
						$.tablesorter.defaults.sortList = [[1,0]];
					 	$("#totals").tablesorter(); 
					    } 
					); 


				}
			}
			ajaxRequest.open("GET", "loadtabletime.php?" + myquerydata , true);
			ajaxRequest.send(null); 




		}



	}
}


function load() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));

		var copyCollection = new GCopyrightCollection('Mercator');
		var copyright = new GCopyright(1, new GLatLngBounds(new GLatLng(-53.894991, -3.2540285), new GLatLng(-55.894991, -5.2540285)), 0, "©2010 uszkalo.com");
		copyCollection.addCopyright(copyright);

		var tilelayers = [new GTileLayer(copyCollection, 6, 6)];
		tilelayers[0].getTileUrl = CustomGetTileUrlMercator;

		var custommap = new GMapType(tilelayers, new GMercatorProjection(18), "Mercator", {
			errorMessage: "No chart data available"
		});
		map.addMapType(custommap);

		map.addControl(new GLargeMapControl3D());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(52.721899, -1.875642), 7);
		map.setZoom(7);


		map.setMapType(getCurrentMapTypeVar);
		GEvent.addListener(map, "maptypechanged", function () {
			getCurrentMapTypeVar = map.getCurrentMapType();
		});


		GDownloadUrl("phpsqlajax_genxml.php", function (data) {
			var xml = GXml.parse(data);
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				var name = markers[i].getAttribute("name");
				var desc = markers[i].getAttribute("desc");
				var eventtype = markers[i].getAttribute("eventtype");
				var type = markers[i].getAttribute("type");
				var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
				var sourcetitle = markers[i].getAttribute("sourcetitle");
				var marker = createMarker(point, name, desc, type, eventtype, sourcetitle);
				map.addOverlay(marker);
			}
		});
	}
}

function slimizeme(longtext, lineSize) {
	var returnString = '';
	var i = 0;
	if (longtext.length == 0) return longtext;

	returnString += longtext[0];

	for (i = 1, j = 1; longtext.length > i; i++) {
		if ((j > lineSize) && longtext[i] == ' ') {
			returnString += "<BR>";
			j = 0;
		}
		j++;
		returnString += longtext[i];
	}

	return returnString;
}


function createMarker(point, name, address, type, eventtype, eventdate, eventtypeorig, sourcetitle) {
	var marker = new GMarker(point, customIcons[type]);
	var typenamearray = eventtypeorig.split('_');
	var eventtypearray = eventtype.split('_');

	var lineSize = 30;
	var picturesHTML = "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[0] + ".png'></center></td>";
	var eventHTML = "<td><center><B>Event Type 1<BR>" + eventtypearray[0] + "</B></center></td>";

	if (typeof typenamearray[1] != 'undefined') {
		picturesHTML += "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[1] + ".png'></center></td>";
		eventHTML += "<td><center><B>Event Type 2<BR>" + eventtypearray[1] + "</B></center></td>";
		lineSize = 50;
	}
	if (typeof typenamearray[2] != 'undefined') {
		picturesHTML += "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[2] + ".png'></center></td>";
		eventHTML += "<td><center><B>Event Type 3<BR>" + eventtypearray[2] + "</B></center></td>";
		lineSize = 60;
	}
	if (typeof typenamearray[3] != 'undefined') {
		picturesHTML += "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[3] + ".png'></center></td>";
		eventHTML += "<td><center><B>Event Type 4<BR>" + eventtypearray[3] + "</B></center></td>";
		lineSize = 70;
	}
	if (typeof typenamearray[4] != 'undefined') {
		picturesHTML += "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[4] + ".png'></center></td>";
		eventHTML += "<td><center><B>Event Type 5<BR>" + eventtypearray[4] + "</B></center></td>";
		lineSize = 80;
	}
	if (typeof typenamearray[5] != 'undefined') {
		picturesHTML += "<td><center><img src='../throwing-bones/thumbsroot/level9/" + typenamearray[5] + ".png'></center></td>";
		eventHTML += "<td><center><B>Event Type 6<BR>" + eventtypearray[5] + "</B></center></td>";
		lineSize = 90;
	}

	eventTableFinalized = "<center><table cellspacing=1 cellpadding=1 border=0><tr>" + eventHTML + "</tr><tr height=\"150\">" + picturesHTML + "</tr></table></center>";
	var html = "<b>" + name + " <BR>" + eventdate + "<BR>Source: " + slimizeme(sourcetitle, lineSize - 5) + "</b><BR>" + slimizeme(address, lineSize) + "<BR><BR>" + eventTableFinalized;
	GEvent.addListener(marker, 'click', function () {
		marker.openInfoWindowHtml(html, 2000, 2000);
	});
	return marker;
}


function loadMappingContent(category, key){
	var queryAppend = '';
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

	if (typeof openWindow[category + "-" +key] != "undefined") return;

	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){

			var yvar=Math.floor(Math.random()*150) + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
			var xvar=Math.floor(Math.random()*150);
			var divId = createNewWindow(700,500,200+xvar,100+yvar);
			openWindow[category + "-" +key] = -1;
			idWindowMap[divId] = category + "-" +key;
			document.getElementById('windowContent' + divId).style.overflow = 'auto';
			document.getElementById('windowContent' + divId).innerHTML = ajaxRequest.responseText;

		}
	}
	ajaxRequest.open("GET", "offlinecontent/events-" +key + ".xml" , true);
	ajaxRequest.send(null); 
}
