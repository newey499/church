<?php
require_once "../globals.php";
require_once "../mysql.php";
require_once "../dumpquerytotable.php";
$pageTitle = "Browse Regular Events";			// Set Page title
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
  <!-- End CSS Includes -->

</head>
<body style="margin-left:20px; margin-top:20px;">

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">


<!-- ------------------ HEADER --------------------------------------- -->
<?php

require_once('topbanner.php');

print("<H3>$pageTitle</H3>\n");
?>
<!-- ---------------- END HEADER ------------------------------------- -->



<?php

/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
***********************/
$qry = "SELECT id, dayofweek, weekofmonth, startdate, enddate, eventtime, eventname," .
             " eventdesc,isvisible FROM regularevents ORDER BY id DESC";

$obj = new dumpQryToTable($dbHandle,$qry);

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editregevents.php";		// Insert new row link points to this target
$obj->editTarget 		= "editregevents.php";		// Update row link points to this target
$obj->deleteTarget 	= "editregevents.php";		// Delete row link points to this target

/**********
Add names of columns in primary key
***********************/
$obj->addPrimaryKeyColumn('id');
//$obj->addPrimaryKeyColumn('next pk col name');
//$obj->addPrimaryKeyColumn('next pk col name');

/*********
Columns to display in table
Add columns - args are 	1) column name
												2) Column Header Title
****************/
//$obj->addColumn("id","Id");
//$obj->addColumn("orgid","Organisation");
$obj->addColumn("eventname","Name of Event");
$obj->addColumn("isvisible","Visible");
$obj->addColumn("dayofweek","Day");
$obj->addColumn("weekofmonth","Week");
$obj->addColumn("startdate","Start");
$obj->addColumn("enddate","End");
$obj->addColumn("eventtime","Event Time");

//$obj->tableClass = "error";						// CSS Class for table
//$obj->tableId = "centercontent";      // CSS Id for table
//$obj->thClass = "error";							// CSS Class for table header
//$obj->tdClass = "error";         			// CSS Class for table data row

$obj->addLink("index.php","Admin");	// Add a link to take us back to the Admin page
$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page


echo "<div class=\"cdnform\">\n";
$obj->exec();														// Build the table
echo "</div>\n";




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
