<html>

<?php
/*******************************************************************

index.php

Main menu for Content Management System (CMS)

Date		Programmer		Description
02/05/2012		CDN			Use table for menu options

********************************************************************/
?>

<head>
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Chris Newey">
	<meta name="generator" content="AceHTML 5 Freeware">
	<link rel="stylesheet" type="text/css" href="../css/church.css" />

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <!-- End CSS Includes -->



</head>

<body style="margin-left:1em; margin-top:20px;" >

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">


<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>


<br>
<h2>Website Maintenance</h2>


<!--  -------------------------------
<p>
	<a href="borgs.php" title="borgs">Organisations</a>
</p>
---------------------------------- -->

<!-- -----------------------------
<p>
	<a href="bnewsletter.php" title="borgs">Newsletters</a>
</p>
--------------------------------- -->

<h4>Menus</h4>
<table>

	<tr >
		<a href="bmenus.php" title="bmenus">Menu Configuration</a>
		<br />
		<a href="renumbermenus.php" title="renumbermenus">Renumber Menus</a>

	</tr>

</table>

<h4>Events</h4>

<table>

	<tr>
		<a href="fcform.php" title="fcform">Add a new Forthcoming Event</a>
		<br />
		<a href="bforthevent.php" title="bforthevent">Forthcoming Events</a>
		<br />
		<a href="bsermonstalks.php" title="bsermonstalks">Sermons and Talks</a>
		<br />
		<a href="touchnewsermon.php" title="touchnewsermon">Touch menu items for new sermon</a>
		<br />
		<a href="create-christianity-explored-rows.php" title="create-ce">Create Christianity Explored Forthcoming Events</a>
		<br />
		<a href="bregevent.php" title="bregevent">Regular Events</a>
	</tr>

</table>

<table>
	<tr>
	<!----------------
		<a href="bcanyouhelp.php" title="bcanyouhelp">Can You Help Entries</a>
		<br />
		<a href="bblog.php" title="bblog">Blog Entries</a>
		<br />
	--------------------- -->
		<a href="../rssbld.php" title="rssbld">Rebuild RSS File</a>
	</tr>
</table>

<h4>Global System Settings</h4>
<table>

	<tr>
		<a href="lastupdated.php" title="lastupdated">Website Last Updated timestamp</a>
		<br />
		<!-- -------------------------------------------
		<a href="editsysconf.php" title="sysconf">Site Configuration</a>
		------------------------------------------------- -->
	</tr>

</table>



<h4>Social Media</h4>
<table>

	<tr>
		<a href="send-tweet.php" title="sysconf">Send Tweet</a>
		<br />
		<!-- ---------------------------
		<a href="send-facebook.php" title="sysconf">Send to Facebook</a>
		<br />
		------------------------------------- -->
		<a href="google-short-url.php" title="sysconf">Google URL Shortner</a>
	</tr>


</table>


<hr>

<p>
<a href="../index.php" title="admin">Home</a>
</p>

</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->

</body>
</html>
