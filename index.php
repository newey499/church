<?php
/******************************

Modification History
====================

Date			Programmer				Description
12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php


/*****************
require_once('epd_cookie.class.php');

$epd = new epd_cookie();

$epd->verify_epd_cookie('epd_cookie_check.php');
*****************************/
?>
<?php

	require_once("dbconnectparms.php");
	require_once("globals.php");
	require_once("genlib.php");
	require_once("mysql.php");
	require_once("dumpquerytotable.php");
	require_once("class.cdnmail.php");
	require_once("mysqldatetime.php");
	require_once('config_google_maps_api_key.php');

  // How to force a redirect
  //Header("Status: 302");
  //Header("Location: http://www.google.co.uk");

	// No Caching
	//Header("Cache-Control: no-cache");
	//Header("Cache-Control: no-store");
	//Header("Cache-Control: must-revalidate");


	/******************************
		Start a session. This must be the very first thing done on the page.
    The session objects constructor also connects to the database.
	**********************************/
	require_once("session.php");
	$oSession = new session();

	$_SESSION['siteMainPage'] = "index.php";

	print('<!DOCTYPE html>');

?>
<!-- Required for Google Plus - Update your html tag to include the itemscope and itemtype attributes -->
<html itemscope itemtype="http://schema.org/Organization">

<head>

<?php

//print("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" >" . "\n");
//print('<meta http-equiv="Content-Style-Type" content="text/css" >' . "\n");

?>

  <title>Christ Church Lye &amp; Stambermill</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" >
  
  <meta charset="utf-8" />
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" />  -->



  <meta name="keywords" content="christ, church, lye, cofe, evangelical, england" >

  <!-- Start Open Graph Metadate for Facebook -->
  <meta property="og:title" content="Christ Church, Lye" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="http://christchurchlye.org.uk" />
  <meta property="og:image" content="http://christchurchlye.org.uk/jpgs/church003small.jpg" />
  <meta property="og:site_name" content="Christ Church, Lye" />
  <meta property="fb:admins" content="100003814322398" />
  <!-- End Open Graph Metadate for Facebook -->


  <!-- Start Google Plus meta data -->
  <meta itemprop="name" content="Christ Church, Lye">
  <meta itemprop="description" content="The website of Christ Church Lye">
	<meta itemprop="image" content="http://www.christchurchlye.org.uk/jpgs/church003small.jpg">
  <!-- End Google Plus meta data -->

  <!-- RSS Feed -->
  <link rel="alternate" type="application/rss+xml"
   href="http://www.christchurchlye.org.uk/rss.xml" title="Christ Church Events" >
  <!-- End RSS Feed -->


  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" >
  <link rel="stylesheet" type="text/css" href="css/church.css" >
  <link rel="stylesheet" type="text/css" href="css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="css/print.css" >
  <link rel="stylesheet" type="text/css" href="css/cssdropdownmenu.css" />
  <!-- End CSS Includes -->

  <!-- Javascript Includes -->
  <!--   <script type="text/javascript" src="jscript/chromejs/chrome.js" ></script> -->
  <!--   <script type="text/javascript" src="jscript/javaxhtml1-0.js" ></script> -->
  <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>


  <script type="text/javascript" src="jscript/jquery-1.7.2-min.js" ></script>
  <script type="text/javascript" src="jscript/jquery.scrollTo-1.4.2-min.js" ></script>

  <script type="text/javascript" src="jscript/genlib.js" ></script>
  <script type="text/javascript" src="jscript/ajax.js" ></script>
  <script type="text/javascript" src="jscript/church.js" ></script>
  <script type="text/javascript" src="jscript/slideshow.js" ></script>
  <script type="text/javascript" src="jscript/tooltip.js" ></script>
  <script type="text/javascript" src="jscript/socialmedia.js" ></script>


  <!-- Start Google Maps -->
  <script type="text/javascript" src="jscript/googlemaps.js" ></script>

  <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print(GOOGLE_MAPS_API_KEY); ?>"
		type="text/javascript">
  </script>
  <!-- End Google Maps -->

  <script type="text/javascript" src="jscript/googlemaps.js" >
	/*******
	Register this function to run every time the window loads
	*****************/
	window.onload = function()
	{
		var googleMapDiv = document.getElementById('location_map_canvas');
		if (googleMapDiv)
		{
			alert("Google Map Div found");
			loadGoogleMapApiVer2();
		}
		else
		{
			alert("Google Map Div NOT found");
		}
	}

	window.onunload= function()
	{
		var googleMapDiv = document.getElementById("map");
		if (googleMapDiv)
		{
			alert("Google Map Div found");
			GUnload();
		}
	}
  </script>


  <!-- <script type="text/javascript" src="jscript/changestyle.js"></script> -->
  <!-- End Javascript Includes -->



<!-- Javascript for Twitter Follow Button -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>


<!-- JavaScript for Google+ Like Place this render call where appropriate -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

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

		print("<a href= \"http://www." . WEBSITE_DOMAIN . "/index.php?displaypage=login.php&amp;loginstatus=2\">");
		print("Register");
		print("</a>");

		print("<br />\n");
	}

	print("</p>\n");
}

?>

<!-- Start Google analytics  -->
<!-- Google wants this placed just before </head>  -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6085466-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- End Google analytics  -->
</head>


<body>

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<!-- ========================================================================== -->
<!--                             Start Top Section                              -->
<!-- ========================================================================== -->

<!-- Top Banner for site -->
<?php
	// 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php
	include_once("topbanner.php");
?>

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
<span class="error">
Either your browser does not support Javascript or Javascript has been disabled.
<br />
This site does not work correctly without Javascript.
</span>
</em>
</p>
</noscript>

<!--[if lt IE 8]>
<p>
<em>
<span class="error">
Microsoft recommends that Internet Explorer users should upgrade to the
<a href="http://www.microsoft.com/windows/internet-explorer" >latest version - IE8.</a>
</span>
</em>
</p>
<![endif]-->

<!-- ========================================================================== -->
<!--                             End Browser Whinging                           -->
<!-- ========================================================================== -->



<div class="menutop">
<?php
	// 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php
	//buildLinksFromTable('',array('MENU_ITEM_DROP'));
?>
</div>

<!-- ============================================
<div class="churchcenter" >

<img	class="churchcenterimg" src="jpgs/churchfrontwarmem.jpg"
            alt="Picture of Christ Church - Click for full size picture"
			onclick="location.href='http://www.christchurchlye.org.uk/jpgs/churchfrontwarmem.jpg'"
			onmouseover="this.style.cursor='pointer'"
			title="Picture of Christ Church - Click for full size picture" />

</div>
==================================================== -->


<div class="churchcenter" >

<img	class="churchcenterimg" src="jpgs/church003small.jpg"
            alt="Picture of Christ Church - Click for full size picture"
			onclick="location.href='http://www.christchurchlye.org.uk/jpgs/church003.jpg'"
			onmouseover="this.style.cursor='pointer'"
			title="Picture of Christ Church - Click for full size picture" />


</div>




<?php


print("<div id='ajaxcentercontent'>\n");

/*******************
If the displaypage requested indicates internal content then that content is pulled from the database.

If a relative path to a file is given then that file is included.

If no displaypage is given then the home page is loaded.
*****************************/
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
			/***************
			print("<script type='text/javascript'> \n");
			print("loadXMLDoc('" . $_GET['displaypage'] . "', 'ajaxcentercontent' ); \n");
			print("</script> \n");
			******************/
			//loadXMLDoc($_GET['displaypage'], 'ajaxcentercontent');
		}
		else
		{
			// Whoops something is screwed up
			print("<br /><h4>Requested file - [" . $_GET['displaypage'] . "] does not exist</h4>");
		}
	}
}
else
{
	// displaypage not passed so default to the file included below
	include_once('home.php');
	//print("<script type='text/javascript'> \n");
	//print("loadXMLDoc('home.php', 'ajaxcentercontent' ); \n");
	//print("</script> \n");
	//loadXMLDoc('home.php', 'ajaxcentercontent');
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
<!-- 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php - left column no longer used -->
<!-- ========================================================================== -->
<!-- =============================================================
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

=============================================================== -->
<?php

/*************
$oSysconf = new Sysconf();

if ($oSysconf->isPageMenuEnabled() )
{
	// 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php
	// buildLinksFromTable('LEFT');
}
********************/
?>

<!-- <br /> -->

<!-- </div> -->  <!-- END <div class="innertube"> -->
<!-- </div> -->  <!-- END <div id="leftcolumn"> -->
<!-- ========================================================================== -->
<!--                            End Left Column Content Section                 -->
<!-- 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php - left column no longer used -->
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
	// 12/06/2012	CDN			Changed to use CSS Based Horizontal Bar Menu - see topbanner.php
	// buildLinksFromTable('RIGHT');
}

?>




<!-- ========================================================================== -->
<!-- Start Social Media Div                                                     -->
<!-- ========================================================================== -->
<div style="background:silver; margin-left:5px; padding:5px; border-radius:5px; border: 1px solid black;padding:3px;width: 98%;">


<!-- ========================================================================== -->
<!-- Start FaceBook Like                                                        -->
<!-- ========================================================================== -->
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fchristchurchlye.org.uk%2Fpage%2Fto%2Flike&amp;
		send=false&amp;
		layout=button_count&amp;
		width=200&amp;
		show_faces=false&amp;
		action=like&amp;
		colorscheme=light&amp;
		height=70"
		scrolling="no"
		frameborder="0"
		style="border:none; overflow:hidden; width:450px; height:35px;"
		allowTransparency="true">
</iframe>
<!-- ========================================================================== -->
<!-- End FaceBook Like                                                          -->
<!-- ========================================================================== -->



<!-- Start Google+1 like button                                  -->
<!-- Place this tag where you want the Google+1 button to render -->
<g:plusone annotation="inline" width="150"></g:plusone>
<!-- Start Google+1 like button                                  -->
<!-- Place this tag where you want the Google+1 button to render -->

<br />


<!-- Start Simon Falshaw Twitter Account - Follow Button -->
<a href="https://twitter.com/Revfalshaw"
   class="twitter-follow-button"
   data-show-count="false"
>
Follow @Revfalshaw
</a>
<!-- End Simon Falshaw Twitter Account - Follow Button -->

<br />

<!-- Start ChristChurchLye Twitter Account - Follow Button -->
<a href="https://twitter.com/ChristChurchLye"
	class="twitter-follow-button"
	data-width="300px"
	data-align="left"
	data-show-count="false" >
Follow @ChristChurchLye
</a>
<!-- End ChristChurchLye Twitter Account - Follow Button -->


<!-- Start Keith Stroyde's Twitter Account - Follow Button -->
<a href="https://twitter.com/Stroyde2006"
   class="twitter-follow-button"
   data-show-count="false"
	 alt="Keith Stroyde Youth and Community Worker"
	 title="Keith Stroyde Youth and Community Worker"
>
Follow @Stroyde2006
</a>
<!-- End Keith Stroyde Twitter Account - Follow Button -->

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

<!-- Forthcoming Events -->

<!-- REST Service for Events -->
<!--
<a href="http://rest.christchurchlye.org.uk/index.php">
XML Rest Server for Church Events
(Alpha)
</a>
<br />
<br />
-->
<!-- End REST Service for Events -->

<!-- ========================================================================== -->
<!-- Start Google Search Data entry form                                        -->
<!-- ========================================================================== -->
<form action="http://www.google.com/cse" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="003266539997362591666:bizbcosok3s" />
    <input type="hidden" name="ie" value="UTF-8" />
	<div style="padding-bottom:4px;">
		<input type="text" name="q" size="18" placeholder="Enter Search Text" />
	</div>
    <input type="submit" name="sa" value="Site Search" />
  </div>
</form>
<!-- ========================================================================== -->
<!-- End Google Search Data entry form                                          -->
<!-- ========================================================================== -->

<br />

<!-- ========================================================================== -->
<!-- End Social Median Div                                                    -->
<!-- ========================================================================== -->
</div>

<!-- ========================================================================== -->
<!-- Start Google Translate form                                                -->
<!-- ========================================================================== -->
<script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    floatPosition: google.translate.TranslateElement.FloatPosition.BOTTOM_RIGHT
  });
}

<!-- ========================================================================== -->
<!-- End Google Translate form                                                  -->
<!-- ========================================================================== -->

<!-- ========================================================================== -->
<!-- Start Google Translate form                                                -->
<!-- ========================================================================== -->
<div id="google_translate_element" style="width:40px;" ></div>
<script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en'
  }, 'google_translate_element');
}
</script>
<!-- ========================================================================== -->
<!-- End Google Translate form                                                  -->
<!-- ========================================================================== -->





<!-- =============================================
<br />

<div class="surroundbox" >

<div class="lastupdated">
	<?php
		//print "Last Updated <br />\n";
		//print getLastUpdateTimestamp();
	?>
</div>

</div>
====================================================== -->

<br />

<!-- ========================================================================== -->
<!--                            End Right Column Content Section                 -->
<!-- ========================================================================== -->
</div>  <!-- END <div class="innertube"> -->
</div>  <!-- END <div id="rightcolumn"> -->




<!-- </div> --> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


<div>
<?php
include_once("footer.php");
?>
</div>

</body>

</html>


