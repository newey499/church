<?php
	require_once "../globals.php";
	require_once("../mysql.php");

	require_once('../session.php');
	$oSession = new Session();


function touchUpdateRec()
{

	$oDate = new DateTime();

	$sqlDate = $oDate->format("d/m/Y");
	$sqlTime = $oDate->format("H:i");

	if ( ! setLastupdatedDate($sqlDate, $sqlTime) )
	{
    die('Update failed: ' . mysql_error());
	}
	else
	{
		print "Website last updated timestamp set to " . $sqlDate . " " . $sqlTime . "<br />\n";
	}

	print "<p>" .
				"Return to <a href=\"index.php\">Admin</a> Menu" .
				"</p>";

	return;
}

?>

<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 08/01/2005 -->
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
<h2>Touch Operations after MP3's have been uploaded</h2>

<?php
	/* Connect to a MySQL server */
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');



	touchMenuItem("whats on calendar");
	print "Whats On Calendar lastupdated status set<br />\n";
	touchMenuItem("sermons and talks");
	print "Sermons and Talks lastupdated status set<br />\n";
	touchUpdateRec();

?>


<p>
<a href="../index.php" title="admin">Home</a>
</p>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>
