<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";
require_once "../genlib.php";
$pageTitle = "Set Timestamp for last Website Update";			// Set Page title
?>
<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 02/12/2004 -->
<head>
<?php
	print("<title>$pageTitle</title>\n");

	include_once("../nocache.php");						// Stop clients from cacheing - hopefully
?>
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
<body style="margin-left:1em; margin-top:20px;" >

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<!-- ------------------ HEADER --------------------------------------- -->
<?php
require_once('topbanner.php');

print "<H3>$pageTitle</H3>\n";
?>

<!-- ---------------- END HEADER ------------------------------------- -->



<?php


/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

if (! empty($_POST['touchlastupdated']))
{
	touchUpdateRec();
}
else
{
	if (safeGet('opcode') == UPDATE_REC)
	{
		updateRec();
	}
	else
	{
		editRec();
	}
}

?>


<?php
// =============================
function editRec()
{

$qry = "SELECT lastUpdated
        FROM sysconf
        LIMIT 1";


$row = mysql_query($qry);
if (!$row)
{
	die("Query failed: [$qry] " . mysql_error());
}

$cols = mysql_fetch_assoc($row);

if ((! isset($cols['lastUpdated'])) || empty($cols['lastUpdated']))
{
	$luDate = "00/00/0000";
	$luTime = "00:00";
}
else
{
	$luDate = "00/00/9999";
	$luTime = "00:99";
	splitMySqlDateTime($cols['lastUpdated'], $luDate, $luTime);

}
$lastUpdated = $cols['lastUpdated'];

?>


<FORM action="./lastupdated.php?opcode=U" method="post">
  <P>
  	Website Last updated
  	<br />
  	<br />
    Time: <INPUT type="text" name="luTime" maxlength="5" value=
    <?php print "\"" . $luTime . "\""; ?>
    ><BR>
    Date: <INPUT type="text" name="luDate" maxlength="10" value=
    <?php print "\"" . $luDate . "\""; ?>
    >
    <BR>
    <br />
    <INPUT type="submit" value="Update">
    <INPUT type="reset">
  </P>
</FORM>

<FORM action="./lastupdated.php?opcode=U" method="post">
  <P>
    <INPUT type="submit" name="touchlastupdated" value="Touch Last Updated">
  </P>
</FORM>




<?php

	print "<p>" .
				"Return to <a href=\"index.php\">Admin</a> Menu" .
				"</p>";

	return;
}

// ================================
?>



<?php
// =================================

function updateRec()
{

	$oDate = new mysqlDate();
	$oTime = new mysqlTime();

	$sqlDate = $oDate->UkDateToMySql( $_POST['luDate'] );
	$sqlTime = $_POST['luTime'];

	if ( ! setLastupdatedDate($_POST['luDate'], $sqlTime) )
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

// ==================================
?>

<?php
// =================================

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

// ==================================
?>



<!-- ------------------ FOOTER --------------------------------------- -->
<HR>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>
<!-- ---------------- END FOOTER ------------------------------------- -->