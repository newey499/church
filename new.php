<?php

	require_once("dbconnectparms.php");
	require_once("globals.php");
	require_once("genlib.php");
	require_once("mysql.php");
	require_once("dumpquerytotable.php");
	require_once("class.cdnmail.php");
	require_once("mysqldatetime.php");

 // How to force a redirect
 //Header("Status: 302");
 //Header("Location: http://www.google.co.uk"); 

	/***********
	Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
	Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
	****************/
	$internetExplorer = isInternetExplorer();
	//$internetExplorer = TRUE;
	if ($internetExplorer)
	{
		// Force a redirect
		//Header("Status: 302");
		//Header("Location: indexie.php"); 
	}

	// No Caching
	//Header("Cache-Control: no-cache");
	//Header("Cache-Control: no-store");
	//Header("Cache-Control: must-revalidate");
	//Header("Cache-control: private"); 		// Needed to work around a bug in IE 5	

	/******************************
		Start a session. This must be the very first thing done on the page.
	**********************************/
	require_once("session.php");
	$oSession = new session();

	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	

	
	/***********
	Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
	Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
	****************/
	$internetExplorer = isInternetExplorer();
	//$internetExplorer = TRUE;
	if ($internetExplorer)
	{
		print("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\ " .
	        "\"http://www.w3.org/TR/html4/loose.dtd\">");
	}
	else
	{
		print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
		print("<?xml-stylesheet type=\"text/xsl\" href=\"copy.xsl\"?>\n");
		print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' .
				  '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	}
?>


<?php


/***********
Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
****************/
if ($internetExplorer)
{
	print("<html>\n");
}
else
{
	print('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
}
?>


<head>

<?php

if ($internetExplorer)
{
	//print("<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n");
	//print("<meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\" />\n");
	//print("<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n");

	//print("<meta http-equiv=\"Content-type\" content=\"text/html;charset=UTF-8\" />\n");
	print("  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n");
	print("  <meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\" />\n");
}
else
{
	//print("<meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\" />\n");
	print("  <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\" />\n");

	//print('<meta http-equiv="content-type" content="text/html;charset=utf-8" />' . "\n");
	print('<meta http-equiv="Content-Style-Type" content="text/css" />' . "\n");

}

?>

  <title>Christ Church, Lye, West Midlands UK</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />	

  <meta name="keywords" content="christ, church, lye, cofe, evangelical, england" />	

  <!-- RSS Feed -->
  <link rel="alternate" type="application/rss+xml" 
   href="http://www.christchurchlye.org.uk/rss.xml" title="Christ Church Events" />
  <!-- End RSS Feed -->


  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/css/layout.css" />
  <link rel="stylesheet" type="text/css" href="css/css/church.css" />		
  <link rel="stylesheet" type="text/css" href="css/css/slideshow.css" />	
  <link rel="stylesheet" type="text/css" media="print" href="css/css/print.css" />	
  <!-- End CSS Includes -->

  <!-- Javascript Includes -->
  <!--   <script type="text/javascript" src="jscript/chromejs/chrome.js" ></script> -->
  <!--   <script type="text/javascript" src="jscript/javaxhtml1-0.js" ></script> -->
  <script type="text/javascript" src="jscript/jquery-1.2.6.min.js" ></script>
  <script type="text/javascript" src="jscript/church.js" ></script>
  <script type="text/javascript" src="jscript/genlib.js" ></script>
  <script type="text/javascript" src="jscript/slideshow.js" ></script>
	<!-- <script type="text/javascript" src="jscript/changestyle.js"></script> -->
  <!-- End Javascript Includes -->



<?php

// Default to this option when loading this page without an opcode
//if (!isset($_GET['opcode'])) {
//	$_GET['opcode'] = FORTH_EVENTS;	
//}


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


</head>


<body> 

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<!-- ========================================================================== -->
<!--                             Start Top Section                              --> 
<!-- ========================================================================== -->
<div id="topsection">
<div class="innertube">

<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>

</div>  <!-- End <div class="innertube"> -->
</div>  <!-- End <div id="topsection"> -->

<!-- This extra pointless div is needed to get IE out of the crap -->
<!-- Without it the top banner is not displayed                   -->
<!--[if IE ]>
	<div>
	</div>
<![endif]-->


<!-- ========================================================================== -->
<!--                               End Top Section                              --> 
<!-- ========================================================================== -->




<!-- ========================================================================== -->
<!--                          Start Center Content Section                      --> 
<!-- ========================================================================== -->
<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">

<!-- ========================================================================== -->
<!--                               Browser Whinging                             --> 
<!-- ========================================================================== -->
<noscript> 
<p>
<em>
Either your browser does not support JavaScript or Javascript has been disabled.
</em>
</p>
</noscript> 


<!-- ========================================================================== -->
<!--   Check for obsolete Microsoft crap as opposed to current Microsoft crap   -->
<!-- ========================================================================== -->
<!--[if IE 4 ]>
  <h4>Internet Explorer 4 is obsolete and should be upgraded.</h4>
<![endif]-->
<!--[if IE 5 ]>
  <h4>Internet Explorer 5 is obsolete and should be upgraded.</h4>
<![endif]-->
<!--[if IE 6 ]>
  <h4>Internet Explorer 6 is obsolete and should be upgraded.</h4>
<![endif]-->
<!-- ========================================================================== -->
<!--                            End Browser Whinging                            --> 
<!-- ========================================================================== -->

<div class="menutop">
<?php
	buildLinksFromTable('',array('MENU_ITEM_DROP'));
?>
</div>

<div class="churchcenter" > 

<img	class="churchcenterimg" src="jpgs/churchfrontwarmem.jpg" 
            alt="Picture of Christ Church - Click for full size picture" 
			onclick="location.href='http://www.christchurchlye.org.uk/jpgs/churchfrontwarmem.jpg'"
			onmouseover="this.style.cursor='pointer'"
			title="Picture of Christ Church - Click for full size picture" />

</div> 

<!--[if IE ]>
  <br />
<![endif]-->

<?php

print("<div>\n");

if (isset($_GET['displaypage'])) 
{
	if ($_GET['displaypage'] == INTERNAL_CONTENT_FLAG) 
	{
		echo getInternalContent($_GET['rowid']);
	}
	else 
	{
		// The link that bought us here passes the name of the required source
		if (file_exists($_GET['displaypage'])) 
		{
			include_once($_GET['displaypage']);
		}
		else 
		{
			// assume the target is an external file 'cos it ain't local
			print("<h1>Requested file - " . $_GET['displaypage'] . " does not exist");
		}
	}
}
else 
{
	// displaypage not passed so default to the file included below
	include_once('home.php');	
}

print("</div>\n");

?>


</div>  <!-- END <div class="innertube"> -->
</div>  <!-- END <div class="contentcolumn"> -->
</div>  <!-- END <div class="contentwrapper"> -->
<!-- ========================================================================== -->
<!--                            End Center Content Section                      --> 
<!-- ========================================================================== -->




<!-- Test CSS Menu -->
<?php

/***************
	$oSysconf = new Sysconf();

	if ($oSysconf->isDropMenuEnabled() )
	{
		include_once('dropdownmenu.php');
	}
*******************/
?>


<!-- ========================================================================== -->
<!--                          Start Left Column Content Section                 --> 
<!-- ========================================================================== -->
<div id="leftcolumn">
<div class="innertube">



<div>

<img	class="churchtopleft" src="jpgs/church003small.jpg" 
            alt="Picture of Christ Church - Click for full size picture" 
			onclick="location.href='http://www.christchurchlye.org.uk/jpgs/church003.jpg'"
			onmouseover="this.style.cursor='pointer'"
			title="Picture of Christ Church - Click for full size picture" />

</div>

<br />


<?php

$oSysconf = new Sysconf();

if ($oSysconf->isPageMenuEnabled() )
{
	buildLinksFromTable('LEFT');
}

?>


</div>  <!-- END <div class="innertube"> --> 
</div>  <!-- END <div id="leftcolumn"> -->
<!-- ========================================================================== -->
<!--                            End Left Column Content Section                 --> 
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                          Start Right Column Content Section                --> 
<!-- ========================================================================== -->
<div id="rightcolumn">
<div class="innertube">


<?php
	// logInOut();
?>


<?php

$oSysconf = new Sysconf();

$tmp = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'FIREFOX');
if ($tmp === FALSE || $tmp == 0)
{
	$isFirefox = FALSE;
}
else
{
	$isFirefox = TRUE;
	// only needed when the Google search box is working
	//print("<br />\n");
}	 

if ($oSysconf->isPageMenuEnabled() )
{	
	buildLinksFromTable('RIGHT');
}

?> 	


<br />


<a href="http://www.christchurchlye.org.uk/rss.xml">
<!-- Forthcoming Events -->
RSS Feed
</a>

<img	src="jpgs/feed-icon-14x14.png" 
            alt="RSS Feed of Christ Church Forthcoming Events" 
			onclick="location.href='http://www.christchurchlye.org.uk/rss.xml'"
			onmouseover="this.style.cursor='pointer'"
			title="RSS Feed of Christ Church Forthcoming Events"			
			width="14" height="14" />

<br />
<br />

<!-- ========================================================================== -->
<!-- Start Google Search Data entry form                                        -->
<!-- ========================================================================== --> 
<form action="http://www.google.com/cse" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="003266539997362591666:bizbcosok3s" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="15" />
    <input type="submit" name="sa" value="Search" />
  </div>
</form>

<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script>
<!-- ========================================================================== -->
<!-- End Google Search Data entry form                                          -->
<!-- ========================================================================== -->

<br />

<div class="surroundbox" >

<div class="lastupdated">
	<?php
		print "Last Updated <br />\n";
		print getLastUpdateTimestamp();
  ?>
</div>		

</div> <!-- class="surroundbox -->





</div> <!-- END <div class="innertube"> -->
</div> <!-- END <div id="rightcolumn"> -->
<!-- ========================================================================== -->
<!--                            End Right Column Content Section                --> 
<!-- ========================================================================== -->


<!-- ========================================================================== -->
<!--                          Start Footer Content Section                      --> 
<!-- ========================================================================== -->
<div id="footer" class="footer">
<div class="innertube">

Copyright &copy; 2007 - 2009 Christ Church Parish Council
<br />

<a href="index.php?displaypage=mailform.php&mailTo=Office@christchurchlye.org.uk" >Church Office</a>
|
<a href="index.php?displaypage=mailform.php&mailTo=Simon.Falshaw@christchurchlye.org.uk" >Priest in Charge</a>
|
<a href="index.php?displaypage=mailform.php&mailTo=keithstroyde@hotmail.co.uk" >Youth Coordinator</a>
|
<a href="index.php?displaypage=mailform.php&mailTo=Gloria.Burrows@christchurchlye.org.uk" >Church Secretary</a>
|
<a href="index.php?displaypage=mailform.php&mailTo=Webmaster@christchurchlye.org.uk" >Webmaster</a>

</div> <!-- END <div class="innertube"> -->
</div> <!-- END <div id="footer"> -->
<!-- ========================================================================== -->
<!--                            End Footer Content Section                      --> 
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->
</div>  <!-- END <div id="maincontainer"> -->



</body>

</html>
