<?php
/****************************************************************************

 rssbld.php

 04/12/2008  CDN		Created

******************************************************************************/

require_once("buildnewrssfeed.php");

require_once('session.php');

$oSession = new Session();

?>
<!doctype html">
<html>

<head>
<title>RSS Feed Builder</title>

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" >
  <link rel="stylesheet" type="text/css" href="css/church.css" >
  <link rel="stylesheet" type="text/css" href="css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="css/tooltip.css" >
  <link rel="stylesheet" type="text/css" href="css/useradmin.css" >
  <link rel="stylesheet" type="text/css" media="print" href="css/print.css" >
  <!-- End CSS Includes -->


</head>

<body style="margin-top:20px; margin-left:20px;" >

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<?php
	require_once('admin/topbanner.php');
?>

<br />

<h4>Building RSS Feed</h4>


<p>
<?php

	$msg = "";

	buildNewRssFeed($msg, FALSE);

?>
</p>

<p>
<center>RSS File Written [rss.xml]</center>
<hr>
</p>

<p>
Return to
<?php
	print("<a href='" . $_SERVER['HTTP_REFERER'] . "' >");
?>
Admin Menu
</a>

</p>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->

</body>

</html>



