/**************************************

ajax.js

CDN 26/05/2010

AJAX Specific Odds and Sods javascript functions

26/05/10	CDN						Created by moving AJAX specific functions from genlib.js
***************************************/

// NOTE recentHash and recentPage are global  
var recentHash;  
var recentPage;


/*******
Register this function to run every time the window loads
*****************/
/********************
window.onload = function() 
{
	var intervalTimeMilliSeconds = 1000;
	
	recentHash = parseIdHashLink();  
	recentPage = window.location.href;	
	
	// Set up page on load
	initialiseStateFromURL();	
	// every <intervalTimeMilliSeconds> check whether url has been changed by user hitting the browser back button
	setInterval(initialiseStateFromURL, intervalTimeMilliSeconds);
}
****************************/


/************************
Firefox, Chrome, Safari, IE 8 etc 
This function is called on window open and from a timer and is used to detect when the user presses the back button
and reload the AJAX content. 
This approach doesn't work with IE versions < 8 - IFRAMES are used to get these versions of IE to behave
****************************/
function initialiseStateFromURL() 
{
	if (window.location.hash == "")
	{
		var frm = parsedisplayForm();
		// have we got displaypage= : if so load the file	
		//loadXMLDoc(window.location, "ajaxcentercontent" );		
		if (frm != "")
		{
			loadXMLDoc(frm, "ajaxcentercontent" );			
		}
		else
		{
			if (window.location.href == recentPage)
			{
				return; // Nothing's changed since last polled.
			}

			recentPage = window.location.href;
			window.location.href = recentPage;
		}
	
	}
	else
	{

		if (window.location.hash == recentHash) 
		//if (window.location == recentHash) 	
		{
			return; // Nothing's changed since last polled.
		}

		recentHash = window.location.hash;
		//recentHash = window.location;
		recentPage = window.location.href;
					
		var rowId = parseIdHashLink();
	
		if (rowId != "")
		{
			loadXMLDocInternal("ajaxgetinternal.php",rowId, "ajaxcentercontent" );
		}

	}
}


function hasDisplayPage()
{
	var tmp = window.location.toLowerCase();
	var result = tmp.indexOf("displaypage=", 0);
	
	return ! (result == -1);
}


/******
Extracts "19" from the below url or returns an empty string
http://church/index.php#id=19
****************/
function parseIdHashLink()
{
	var rowId = "";

	rowId = window.location.hash.toLowerCase();
	
	if (rowId.substr(0,4) == "#id=")
	{
		rowId = rowId.substr(4);
	}
	else
	{
		rowId = "";
	}

	return rowId;
}


/******
Extracts "home.php" from the below url or returns an empty string
http://church/index.php?displaypage=home.php
****************/
function parsedisplayForm()
{
	var url = window.location.search;
	
	if (url.substr(0,13) == "?displaypage=")
	{
		url = url.substr(13);
	}
	else
	{
		url = "";
	}

	return url;
}






/*******
16/03/10 CDN Load file into requested HTML element (normally a DIV) with given id 

args:
	url 				-	The php file (ajaxgetinternal.php) that loads the <content> column from the table row with
								the requested primary key of <rowId>
	rowId				- The primary key of the row on the menus table whose <content> column contents
								is to be stash in the <targetId>
	targetId		-	The id of the HTML obect whose innerHTML is to be replaced with the contents of <url> (normally a div)

The readyState property

The readyState property holds the status of the server's response.

Possible values for the readyState property:

State	Description

0	- The request is not initialized
1	- The request has been set up
2	- The request has been sent
3	- The request is in process
4	- The request is complete
********************/
function loadXMLDocExternal(url, rowId, targetId )
{
var xmlhttp = getXMLHttpRequestObject();

  
// anonymous function used to handle asynchronous request  
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {document.getElementById(targetId).innerHTML= xmlhttp.responseText }
}  

var target = url + "?id=" + rowId;


xmlhttp.open("GET", target ,true);  // true = asynchronous  
//xmlhttp.open("GET",target,false);  // false = synchronous (blocks until request completes)
xmlhttp.send(null);
//document.getElementById(targetId).innerHTML= xmlhttp.responseText;  // synchronous
}



/*******
16/03/10 CDN Load file into requested HTML element (normally a DIV) with given id 

args:
	url 				-	The file to load
	rowId				- The primary key of the row on the menus table whose <content> column contents
								is to be stash in the <targetId>
	targetId		-	The id of the HTML obect whose innerHTML is to be replaced with the contents of <url>

The readyState property

The readyState property holds the status of the server's response.

Possible values for the readyState property:

State	Description

0	- The request is not initialized
1	- The request has been set up
2	- The request has been sent
3	- The request is in process
4	- The request is complete
********************/
function loadXMLDocInternal(url, rowId, targetId )
{
var xmlhttp = getXMLHttpRequestObject();

  
// anonymous function used to handle asynchronous request  
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {document.getElementById(targetId).innerHTML= xmlhttp.responseText }
}  

var hashlink = "id=" + rowId;
var target = url + "?" + hashlink;


xmlhttp.open("GET", target ,true);  // true = asynchronous  
//xmlhttp.open("GET",target,false);  // false = synchronous (blocks until request completes)
xmlhttp.send(null);
//document.getElementById(targetId).innerHTML= xmlhttp.responseText;  // synchronous

// Set the # link to indicate the rowId - changing window.location.hash changes the url in the browsers url field
// Eg. http://church/index.php#id=19
window.location.hash = hashlink;

}










