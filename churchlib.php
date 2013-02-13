/*****************************
	churchlib.php

	church specific php routines

04/02/09	CDN			Created

*********************************/


<?php

// Produces HTML for login/logout and register links
function logInOut()
{
	print("<p>\n");

	if ( isset($_SESSION['nickname']) )
	{
		print("<a href= \"http://www." . WEBSITE_DOMAIN . "/index.php?displaypage=login.php\">");
		print("Logout [ " . $_SESSION['nickname'] . " ]");
		print("</a>");
		print("<br />\n");
	}
	else
	{
		print("<a href= \"http://www." . WEBSITE_DOMAIN . "/index.php?displaypage=login.php\">");
		print("Login");
		print("</a>");

		print("&nbsp;&nbsp;");

		print("<a href= \"http://www." . WEBSITE_DOMAIN . "/index.php?displaypage=login.php&loginstatus=2\">");
		print("Register");
		print("</a>");

		print("<br />\n");
	}

	print("</p>\n");
}

?>	
