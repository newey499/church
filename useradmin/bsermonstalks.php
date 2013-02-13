<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";
$pageTitle = "Browse Sermons and Talks";			// Set Page title



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
<body style="margin-top:20px; margin-left:20px;">

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


<p>
When Creating new records:
<br />
1) Insert the new row. Leave mp3 filename blank or insert garbage - ignore warning on save.
<br />
2) Change the mp3 file name to be all lower case and remove spaces from filename.
<br />
3) use the upload mp3 option on the new row. This:
<br />
3.1) Uploads the mp3 and saves it in the mp3s directory.
<br />
3.2) Saves the filename in the filename column of the row.
<br />
3.3) Marks the sermons and talks page as updated (changes the lastupdated timestamp for the menu item).
<br />
3.4) Modifies the lastupdated timestamp for the website on the sysconf table.
</p>


<?php


/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


if (safeGet('opcode') == ST_TOUCH)
{
	$qry = "update sermonstalks set lastupdated = current_timestamp() where id = " . $_GET['id'];
	mysql_query($qry);
	setLastupdatedDate( date('d/m/Y'), date('H:i'));
	touchMenuItem("sermons and talks", "", true, false); // send to Twitter but not to Facebook

	print("<h4>Touch id [" . $_GET['id'] . "] updated ok</h4>");

}



/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
***********************/

$qry = "SELECT	id,
								filename,
								dateperformed,
								series,
								biblebook,
								bibleref,
								title,
								preacher,
								description,
								groupno,
								itemno
        FROM sermonstalks
        ORDER BY groupno, itemno DESC";



$obj = new dumpQryToTable($dbHandle,$qry);

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editsermonstalks.php";		// Insert new row link points to this target
$obj->editTarget 		= "editsermonstalks.php";		// Update row link points to this target
$obj->deleteTarget 	= "editsermonstalks.php";		// Delete row link points to this target

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
$obj->addColumn("dateperformed","Date");
$obj->addColumn("series","Series");
$obj->addColumn("biblebook","Bible Book");
$obj->addColumn("bibleref","Bible Ref");
$obj->addColumn("preacher","Preacher");
$obj->addColumn("groupno","Group");
$obj->addColumn("itemno","Item");
$obj->addColumn("filename","MP3 filename");
//$obj->addColumn("eventdesc","Event Description");


/*********
Columns to display in table
Add columns - args are 	1) url of page to link to
												2) Column Header Title
The link will have the primary key values appended as arguments
****************/
//$obj->addColumnLink('Make Visible','bforthevent.php?opcode=' . FC_EVENT_MARK_VISIBLE, 'Make Visible');
$obj->addColumnLink('Upload Mp3','file-upload.php?opcode=' . ST_UPLOAD_MP3_FILE, 'Upload');
$obj->addColumnLink('Touch','bsermonstalks.php?opcode=' . ST_TOUCH, 'Touch');

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
