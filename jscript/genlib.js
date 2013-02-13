/**************************************

genlib.js

CDN 20/01/2009

Odds and Sods javascript functions

16/03/10	CDN						Add AJAX function to asynchronously load content
26/05/10  	CDN						Move AJAX specific functions out to ajax.js
31/05/12	CDN						Add function to insert text into div (uses jscript)

***************************************/

// Add function to insert text into div (uses jscript)
function insertText(targetId, txt)
{
	var oElement = document.getElementById(targetId);
	if (oElement)
	{
		oElement.innerHTML = txt;
	}
}


// Pops up an email window
function mailPopUp(emailAddress)
{
	var top = 10;
	var left = 10;
	var height = 560;
	var width = 570;

	popUp("mailform.php?" + emailAddress, width, height, top, left);
}


// Create a popup browser window
function popUp(URL, width, height, top, left)
{
if (width == null)
{
	width = 510;
}
if (height == null)
{
	height = 525;
}
if (top == null)
{
	top = 10;
}
if (left == null)
{
	left = 10;
}

day = new Date();
id = day.getTime();

options = "'toolbar=0,scrollbars=1,location=1,statusbar=1,menubar=0,resizable=1,width=" +
           width + ",height=" + height + ",left=" + left + ",top=" + top + "'";


window.open(URL, _blank, options);

}

// closes the window from which it is called
function closePopUp()
{
	document.window.close();
}




