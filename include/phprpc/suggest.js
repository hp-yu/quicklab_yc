//create phprpc client object rpc
var rpc_suggest = new PHPRPC_Client('suggest.php', ['get_suggestions']);
var dom = (document.getElementById) ? true : false;
var ns5 = (!document.all && dom || window.opera) ? true: false;
/* the keyword for which an HTTP request has been initiated */
var httpRequestKeyword = "";
/* the last keyword for which suggests have been requested */
var userKeyword = "";
/* number of suggestions received as results for the keyword */
var suggestions = 0;
/* the maximum number of characters to be displayed for a suggestion */
var suggestionMaxLength = 30;
/* flag that indicates if the up or down arrow keys were pressed
   the last time a keyup event occurred  */
var isKeyUpDownPressed = false;
/* the last suggestion that has been used for autocompleting the keyword */
var autocompletedKeyword = "";
/* flag that indicates if there are results for the current requested keyword*/
var hasResults = false;
/* the identifier used to cancel the evaluation with the clearTimeout method. */
var timeoutId = -1;
/* the currently selected suggestion (by arrow keys or mouse)*/
var position = -1;
/* cache object containing the retrieved suggestions for different keywords */
var oCache = new Object();
/* the minimum and maximum position of the visible suggestions */
var minVisiblePosition = 0;
var maxVisiblePosition = 9;
// when set to true, display detailed error messages
var debugMode = false;
/* the onload event is handled by our init function */
//window.onload = init;
/*what i modified: 2008-12-13 hpyu*/
/*autocomplete(-)*/
/*key event(-)*/
/*match pattern "like %keyword%"*/
/*ignore case*/
/*add the 2 variables below*/
var table = "";
var field = "";
var start_length = "";

/* function that adds to a keyword an array of values */
function addToCache(keyword, values)
{
  // create a new array entry in the cache
  oCache[keyword] = new Array();
  // add all the values to the keyword's entry in the cache
  for(i=0; i<values.length; i++)
    oCache[keyword][i] = values[i];
}

/*
   function that checks to see if the keyword specified as parameter is in
   the cache or tries to find the longest matching prefixes in the cache
   and adds them in the cache for the current keyword parameter
*/
function checkCache(keyword)
{
  // check to see if the keyword is already in the cache
  if(oCache[keyword]) {
  	return true;
  }
  // try to find the biggest prefixes
  for(i=keyword.length-2; i>=0; i--)
  {
    // compute the current prefix keyword
    var currentKeyword = keyword.substring(0, i+1);
    // check to see if we have the current prefix keyword in the cache
    if(oCache[currentKeyword])
    {
      // the current keyword's results already in the cache
      var cacheResults = oCache[currentKeyword];
      // the results matching the keyword in the current cache results
      var keywordResults = new Array();
      var keywordResultsSize = 0;
      // try to find all matching results starting with the current prefix
      for(j=0;j<cacheResults.length;j++)
      {
      	//ignore case
        if(cacheResults[j].toLowerCase().indexOf(keyword) >= 0) {
          keywordResults[keywordResultsSize] = cacheResults[j];
          keywordResultsSize++;
        }
      }
      // add all the keyword's prefix results to the cache
      addToCache(keyword, keywordResults);
      return true;
    }
  }

  // no match found
  return false;
}

/* initiate HTTP request to retrieve suggestions for the current keyword */
function getSuggestions(keyword)
{
  /* continue if keyword isn't null and the last pressed key wasn't up or
     down */
  if(keyword != "" && !isKeyUpDownPressed)
  {
    // check to see if the keyword is in the cache
    isInCache = checkCache(keyword);
    // if keyword is in cache...
    if(isInCache == true)
    {
      // retrieve the results from the cache
      httpRequestKeyword=keyword;
      userKeyword=keyword;
      // display the results in the cache
      displayResults(keyword, oCache[keyword]);
    }
    // if the keyword isn't in cache, make an HTTP request
    else
    {
    	httpRequestKeyword = keyword;
      userKeyword = keyword;
    	rpc_suggest.get_suggestions(table, field, keyword, function (result) {
    		updateSuggestions(result);
    	});
    }
  }
}

/* function that processes the server's response */
function updateSuggestions(response)
{
  // check to see if other keywords are already being searched for
  if(httpRequestKeyword == userKeyword)
  {
    // display the results array
    displayResults(httpRequestKeyword, response);
  }
  else
  {
    // add the results to the cache
    // we don't need to display the results since they are no longer useful
    addToCache(httpRequestKeyword, response);
  }
}

/* populates the list with the current suggestions */
function displayResults(keyword, results_array)
{
  // if the searched for keyword is not in the cache then add it to the cache
  if(!oCache[keyword] && keyword) {
  	addToCache(keyword, results_array);
  }
  // start building the HTML table containing the results
  var div = "<table>";
  // get the number of results from the cache
  suggestions = oCache[keyword].length;
  // loop through all the results and generate the HTML list of results
  for (var i=0; i<oCache[keyword].length; i++)
  {
  	// retrieve the current function
  	crtName = oCache[keyword][i];
  	div += "<tr id='tr" + i +
  	"' onmouseover='handleOnMouseOver(this);' " +
  	"onmouseout='handleOnMouseOut(this);' "+
  	"onclick='handleOnClick(this);'>" +
  	"<td align='left'>";
  	// check to see if the current function name length exceeds the maximum
  	// number of characters that can be displayed for a function name

  	//ignore case
  	crtName = crtName.replace(
  	new RegExp(httpRequestKeyword, 'ig'),
  	function(httpRequestKeyword) { return '<span style="font-weight: bold;">' + httpRequestKeyword + '</span>' }
  	) ;
  	div += crtName + "</td></tr>";
  }
  // end building the HTML table
  div += "</table>";
  // retrieve the suggest and scroll object
  var oSuggest = document.getElementById("suggest");
  var oScroll = document.getElementById("scroll");
  if(results_array.length == 0)
  {
  	hasResults = false;
  	// update the suggestions list and make it hidden
    oSuggest.innerHTML = div;
    oScroll.style.visibility = "hidden";
  }
  else
  {
  	// resets the index of the currently selected suggestion
    position = -1;
    // resets the flag indicating whether the up or down key has been pressed
    isKeyUpDownPressed = false;
  	// scroll to the top of the list
    oScroll.scrollTop = 0;
    hasResults = true;
  	// update the suggestions list and make it hidden
    oSuggest.innerHTML = div;
    oScroll.style.visibility = "visible";
  }

  // if we had results we apply the type ahead for the current keyword
  //if(results_array.length > 0)
  //  autocompleteKeyword();
}

/* function that periodically checks to see if the typed keyword has changed */
function checkForChanges()
{
  // retrieve the keyword object

  var keyword = document.getElementById("keyword").value;
  keyword=keyword.toLowerCase();
  // check to see if the keyword is empty
  if(keyword.length<start_length)
  {
    // hide the suggestions
    hideSuggestions();
    // reset the keywords
    userKeyword="";
    httpRequestKeyword="";
  }
  // set the timer for a new check
  setTimeout("checkForChanges()", 500);
  // check to see if there are any changes
  if((userKeyword != keyword) &&
     (autocompletedKeyword != keyword) &&
     (!isKeyUpDownPressed)&&(keyword.length>=start_length))
    // update the suggestions
    getSuggestions(keyword);
}

/* function that handles the keys that are pressed */
function handleKeyUp(e)
{
  // get the event
  e = (!e) ? window.event : e;
  // get the event's target
  target = (!e.target) ? e.srcElement : e.target;
  if (target.nodeType == 3)
    target = target.parentNode;
  // get the character code of the pressed button
  code = (e.charCode) ? e.charCode :
       ((e.keyCode) ? e.keyCode :
       ((e.which) ? e.which : 0));
  // check to see if the event was keyup
  if (e.type == "keyup")
  {
    isKeyUpDownPressed =false;
    // check to see we if are interested in the current character
    if ((code < 13 && code != 8) ||
        (code >=14 && code < 32) ||
        (code >= 33 && code <= 46 && code != 38 && code != 40) ||
        (code >= 112 && code <= 123))
    {
      // simply ignore non-interesting characters
    }
    else
    /* if Enter is pressed we jump to the PHP help page of the current
       function */
    if(code == 13)
    {
      // check to see if any function is currently selected
      if(position>=0)
      {
        location.href = document.getElementById("a" + position).href;
      }
    }
    else
    // if the down arrow is pressed we go to the next suggestion
      if(code == 40)
      {
        newTR=document.getElementById("tr"+(++position));
        oldTR=document.getElementById("tr"+(--position));
        // deselect the old selected suggestion
        if(position>=0 && position<suggestions-1)
          oldTR.className = "";

        // select the new suggestion and update the keyword
        if(position < suggestions - 1)
        {
          newTR.className = "highlightrow";
          updateKeywordValue(newTR);
          position++;
        }
        e.cancelBubble = true;
        e.returnValue = false;
        isKeyUpDownPressed = true;
        // scroll down if the current window is no longer valid
        if(position > maxVisiblePosition)
        {
          oScroll = document.getElementById("scroll");
          oScroll.scrollTop += 18;
          maxVisiblePosition += 1;
          minVisiblePosition += 1;
        }
      }
      else
      // if the up arrow is pressed we go to the previous suggestion
      if(code == 38)
      {
        newTR=document.getElementById("tr"+(--position));
        oldTR=document.getElementById("tr"+(++position));
        // deselect the old selected position
        if(position>=0 && position <= suggestions - 1)
        {
          oldTR.className = "";
        }
        // select the new suggestion and update the keyword
        if(position > 0)
        {
          newTR.className = "highlightrow";
          updateKeywordValue(newTR);
          position--;
          // scroll up if the current window is no longer valid
          if(position<minVisiblePosition)
          {
            oScroll = document.getElementById("scroll");
            oScroll.scrollTop -= 18;
            maxVisiblePosition -= 1;
            minVisiblePosition -= 1;
          }
        }
        else
          if(position == 0)
            position--;
        e.cancelBubble = true;
        e.returnValue = false;
        isKeyUpDownPressed = true;
      }
  }
}

/* function that removes the style from all suggestions*/
function deselectAll()
{
  for(i=0; i<suggestions; i++)
  {
    var oCrtTr = document.getElementById("tr" + i);
    oCrtTr.className = "";
  }
}

/* function that handles the mouse entering over a suggestion's area
   event */
function handleOnMouseOver(oTr)
{
  deselectAll();
  oTr.className = "highlightrow";
  position = oTr.id.substring(2, oTr.id.length);
}

/* function that handles the mouse exiting a suggestion's area event */
function handleOnMouseOut(oTr)
{
  oTr.className = "";
  position = -1;
}

function handleOnClick(oTr)
{
	var oKeyword = document.getElementById("keyword");
	oKeyword.value = (ns5)? oTr.textContent: oTr.innerText;
}

/* function that escapes a string */
function encode(uri)
{
  if (encodeURIComponent)
  {
    return encodeURIComponent(uri);
  }

  if (escape)
  {
    return escape(uri);
  }
}

/* function that hides the layer containing the suggestions */
function hideSuggestions()
{
  var oScroll = document.getElementById("scroll");
  oScroll.style.visibility = "hidden";
}

/* function that selects a range in the text object passed as parameter */
function selectRange(oText, start, length)
{
  // check to see if in IE or FF
  if (oText.createTextRange)
  {
    //IE
    var oRange = oText.createTextRange();
    oRange.moveStart("character", start);
    oRange.moveEnd("character", length - oText.value.length);
    oRange.select();

  }
  else
    // FF
    if (oText.setSelectionRange)
    {
      oText.setSelectionRange(start, length);
    }
  oText.focus();
}

/* function that autocompletes the typed keyword*/
function autocompleteKeyword()
{
  //retrieve the keyword object
  var oKeyword = document.getElementById("keyword");
  // reset the position of the selected suggestion
  position=0;
  // deselect all suggestions
  deselectAll();
  // highlight the selected suggestion
  document.getElementById("tr0").className="highlightrow";
  // update the keyword's value with the suggestion
  updateKeywordValue(document.getElementById("tr0"));
  // apply the type-ahead style
  selectRange(oKeyword,httpRequestKeyword.length,oKeyword.value.length);
  // set the autocompleted word to the keyword's value
  autocompletedKeyword=oKeyword.value;
}

/* function that displays an error message */
function displayError(message)
{
  // display error message, with more technical details if debugMode is true
  alert("Error accessing the server! "+
        (debugMode ? "\n" + message : ""));
}

window.onload = function ()
{
	var oKeyword = document.getElementById("keyword");
  var oSuggest = document.getElementById("suggest");
  var oScroll = document.getElementById("scroll");
  totaloffsetLeft = getposOffset (oKeyword , "left");
  totaloffsetTop = getposOffset (oKeyword , "top");
  oScroll.style.left=totaloffsetLeft;
  oScroll.style.top=totaloffsetTop + oKeyword.offsetHeight;
  oScroll.style.width=oKeyword.offsetWidth;
  // retrieve the input control for the keyword
  // prevent browser from starting the autofill function
  oKeyword.setAttribute("autocomplete", "off");
  // reset the content of the keyword and set the focus on it
  //oKeyword.value = "";
  oKeyword.focus();
  // set the timeout for checking updates in the keyword's value
  setTimeout("checkForChanges()", 500);
}

function getposOffset(what, offsettype){
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null){
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}