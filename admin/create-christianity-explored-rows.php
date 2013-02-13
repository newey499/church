<html>

<?php
	$pageTitle = "Create Christianity Explored Forthcoming events";
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

<body style="margin-top:20px; margin-left:20px;">

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<?php

require_once('topbanner.php');
/************************************

create-christianity-explored-rows.php

Creates forthcoming event records for the annual
Christianity Explored Course.

Remember to make the Christianity Explored menu item visible.

07/07/2010	CDN				Turn into a form to allow user entered date range and text.

*************************************/

require_once("../dbconnectparms.php");
require_once("../globals.php");
require_once("../genlib.php");
require_once("../mysql.php");
require_once("../mysqldatetime.php");

/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


// ================================================================================
function writeForthcomingEvent(DateTime $eventdate)
{

	$stmnt = sprintf("INSERT INTO forthcomingevents
  									(eventdate,eventname,eventdesc,
                     contribemail, contactname, contactphone, contactemail,
                     isvisible, linkurl
                    )
										VALUES ('%s', '%s', '%s',
                            '%s', '%s', '%s', '%s',
                            '%s', '%s'
                           )",
										mysql_real_escape_string($eventdate->format('Y-m-d H:i')),              // eventdate
										mysql_real_escape_string("Christianity Explored Meeting"), // eventname
										mysql_real_escape_string("Come Along. All welcome"),						 // eventdesc
										mysql_real_escape_string("simon.falshaw@christchurchlye.org.uk"),					 // contribemail
										mysql_real_escape_string("Simon Falshaw"),					 // contactname
										mysql_real_escape_string("01384 - 423142 "),	 				 // contactphone
										mysql_real_escape_string("simon.falshaw@christchurchlye.org.uk"),  				 // contactemail
										mysql_real_escape_string("YES"),   			  				 // isvisible
										mysql_real_escape_string("linkurl")        				 // linkurl
									);

	if (mysql_query($stmnt))
	{
    setLastupdatedDate( date('d/m/Y'), date('H:i'));
		touchMenuItem("forthcoming events");
		touchMenuItem("whats on calendar");
		print("<p>" . "row written for " . $eventdate->format('Y-m-d H:i') . "</p>\n");
	}
	else
	{
		print("<p>" . mysql_error() . "</p>\n");
	}
}
// ================================================================================


// ================================================================================
function writeEventRows(DateTime $startDate, DateTime $endDate)
{
	print("<b>Writing Rows for [" . $startDate->format('Y-m-d H:i') . "] to [" . $endDate->format('Y-m-d H:i') . "]</b> <br />\n");

	while (dateDiff($startDate, $endDate) >= 0)
	{
		writeForthcomingEvent($startDate);
		$startDate->modify('+7 day');
	}

	touchMenuItem("Christianity Explored");
	print("Done <br />\n");
}

// ================================================================================
function writeRecords()
{
	$oDateTime = new mysqldate();
	$oTime = new mysqltime();

	// validate time
	if (! $oTime->checkTime($_POST['starttime']))
	{
		print("<b>[" . $_POST['starttime'] . "] is not a valid time. </b>\n");
		return false;
	}

	// validate start and end dates
	if (! $oDateTime->checkUKdate($_POST['startdate']))
	{
		print("<b>Start Date [" . $_POST['startdate'] . "] is not a valid date. </b>\n");
		return false;
	}
	if (! $oDateTime->checkUKdate($_POST['enddate']))
	{
		print("<b>End Date [" . $_POST['enddate'] . "] is not a valid date. </b>\n");
		return false;
	}


	$startTime = $_POST['starttime'] . ":00";

	$startDate = new DateTime($oDateTime->UkDateToMySql($_POST['startdate']) . " " . $startTime);

	$endDate = new DateTime($oDateTime->UkDateToMySql($_POST['enddate'])  . " " . $startTime);

	if ( $startDate->format('Y-m-d H:i') > $endDate->format('Y-m-d H:i') )
	{
		print("<b> Start Date [" . $_POST['startdate'] . "] is after End Date [" . $_POST['enddate'] . "]</b>\n");
		return false;
	}

	writeEventRows($startDate, $endDate);
}
// ================================================================================
?>

<?php
// ================================================================================
function displayForm()
{
?>

<form name="frm_Default" method="post" action="create-christianity-explored-rows.php" >

<input type="hidden" name="isvisible" value="YES">

<table border="1>"

	<tr>
		<td>Start Time</td>
		<td><input type="text"  name="starttime"  size="50"  maxlength="150"  value="HH:MM"></td>
		<td>
		Two digits for Hour, Two digits for minutes. Note 24 hour clock
		</td>

	</tr>

	<tr>
		<td>Date From</td>

		<td><input type="text"  name="startdate"  size="50"  maxlength="150"  value="DD/MM/YYYY"></td>
		<td>
		Two digits for Day, Two digits for Month Four digits for Year.
		</td>
	</tr>

	<tr>
		<td>Date To</td>

		<td><input type="text"  name="enddate"  size="50"  maxlength="150"  value="DD/MM/YYYY"></td>
		<td>
		Two digits for Day, Two digits for Month Four digits for Year.
		</td>
	</tr>

	<tr>
		<td>Event Name</td>
		<td><input type="text"  name="eventname"  size="50"  maxlength="150"  value="Christianity Explored Meeting"></td>
		<td> </td>
	</tr>

	<tr>
		<td>Description</td>

		<td>
			<textarea rows ="20" cols="80" name="eventdesc">Come Along. All welcome
			</textarea
		</td>
		<td> </td>
	</tr>
	<tr>
		<td>Contributor Email</td>
		<td><input type="text"  name="contribemail"  size="50"  maxlength="150"  value="simon.falshaw@christchurchlye.org.uk"></td>
		<td> </td>

	</tr>
	<tr>
		<td>Contact Name</td>
		<td><input type="text"  name="contactname"  size="50"  maxlength="150"  value="Simon Falshaw"></td>
		<td> </td>
	</tr>
	<tr>
		<td>Contact Phone</td>

		<td><input type="text"  name="contactphone"  size="50"  maxlength="150"  value="01384 - 423142"></td>
		<td> </td>
	</tr>
	<tr>
		<td>Contact Email</td>
		<td><input type="text"  name="contactemail"  size="50"  maxlength="150"  value="simon.falshaw@christchurchlye.org.uk"></td>
		<td> </td>

	</tr>
</table>
<br />

<input type="submit" value="Submit" />
&nbsp;
&nbsp;<a href="index.php">Admin</a>

</form>



<?php
}	//	end function displayForm()
// ================================================================================
?>




<p>
Create forthcoming event records for the annual
Christianity Explored Course.
</p>

<p>
Creates one event at the same time every 7 days starting at "Date From" and
continuing until "Date To" is reached.
</p>


<?php

if (isset($_POST['isvisible']))
{
	writeRecords();
}
else
{
	displayForm();
}

?>



</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>




