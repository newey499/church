/*************************

church.js

Christ Church website specific javascript

**************************/


/**********************
Used by calendar.php to switch the calendar display to a new month.

Intended to be called from the combo boxes on change event

args
	oCbx			-	The combobox

*******************************/
function changeCalendarMonth(newMonth, siteMainPage)
{
	//var newUrl = "http://" + location.hostname + "/index.php" + "?displaypage=calendar.php";
	var newUrl = "http://" + location.hostname + "/" + siteMainPage + "?displaypage=calendar.php";

	newUrl += "&" + "calmonth=" + newMonth + "#today";


	/***************
	alert("changeCalendarMonth\n" + 
        "new url [" + newUrl + "]\n" +
        "siteMainPage [" + siteMainPage + "]\n");
	***********************/

	window.location.replace(newUrl);
	
	return false;
}


/*******
16/03/10 CDN Load file into requested HTML element (normally a DIV) with given id 

args:
	url 				-	the file to load
	targetId		-	the id of the HTML obect whose innerHTML is to be replaced with the contents of <url>

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
function loadXMLDoc(url, targetId)
{

/************
alert(
		"loadXMLDoc args\n" + 
		"url [" + url + "]\n" +
		"targetId [" + targetId + "]"
	 );
*****************/

var xmlhttp = getXMLHttpRequestObject();


// Process asynchronous request  
/*************
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
  	document.getElementById(targetId).innerHTML= xmlhttp.responseText;
  }
}  

xmlhttp.open("GET",url,true);  // true = asynchronous  

xmlhttp.send(null);

******************/

// Process synchronous request  
xmlhttp.open("GET",url,false);  // false = synchronous (blocks until request completes)

/***********
xmlhttp.open("POST",url,false);  // false = synchronous (blocks until request completes)

if (argsPOST)
{
	xmlhttp.send(argsPOST);
}
else
{
	xmlhttp.send();
}
****************/

//alert("Response\n" + xmlhttp.responseText);
var reply = xmlhttp.responseText;  // synchronous
document.getElementById(targetId).innerHTML= reply;

}


/******
Returns an XMLHttpRequest Object regardless of browser - as normal IE goes its own sweet way
******************/
function getXMLHttpRequestObject()
{
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{ 
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{ 
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	return xmlhttp;
}
