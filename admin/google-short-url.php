<html>
<?php
/*************************************

google-short-url.php

Does what it says

Date		Programmer		Description
01/05/2012		CDN			Created

****************************************/
?>

<head>
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Chris Newey">
	<meta name="generator" content="AceHTML 5 Freeware">

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <link rel="stylesheet" type="text/css" href="../css/cssdropdownmenu.css" />
  <!-- End CSS Includes -->

</head>

<body>


<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">



<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>


<br>
<h2>Shorten URL with Google API</h2>


<?php

require_once("../class.google.url.short.php");


if (isset($_POST['url-long']))
{
	$reply = "reply";

	$oUrlShort = new GoogleUrlShort();

	print("<h4>URL Shortened</h4>");

	print("<p>");
	if (empty($_POST['url-long']))
	{
		print("Cannot shorten an empty URL");
	}
	else
	{
		$reply = $oUrlShort->shortenUrl($_POST['url-long']);
		print("<h4>");
		print("<p>");
		print("Original URL [" . $_POST['url-long'] . "]");
		print("</p>");
		print("<p>");
		print("Shortened URL [" . $reply . "]");
		print("</p>");
		print("</h4>");

		/************
		print("<p>");
		$expand = $oUrlShort->expandUrl($reply);
		print("Expanded URL [" . $expand . "]");
		print("</p>");
		********************/
	}
	print("</p>");

}
else
{

?>

	<p>

	<form name="input" action="google-short-url.php" method="post" >

	URL to shorten:
	<br />
	<br />
	<input type="text" name="url-long" size="120" maxlength="140" value="" />
	<br />
	<br />
	<input type="submit" value="Submit" />

	</form>

	</p>

<?php
}
?>


<p>
<a href="index.php" title="admin">Admin</a>
&nbsp;
&nbsp;
<a href="../index.php" title="admin">Home</a>
</p>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>
