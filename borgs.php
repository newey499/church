<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";
$pageTitle = "Browse Organisations";			// Set Page title
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
	<link rel="stylesheet" type="text/css" href="css/all.css" />
	<link rel="stylesheet" type="text/css" href="css/3cols.css" />		
</head>
<body>
<!-- ------------------ HEADER --------------------------------------- -->	
<?php
print "<H3>$pageTitle</H3>\n";
?>
<hr />
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

$qry = "SELECT	id,
								orgname,
							 	contact,
							 	mobile,
							 	phone,
							 	email 
        FROM organisations
        ORDER BY upper(orgname)";  
       
$obj = new dumpQryToTable($dbHandle,$qry);

$obj->displayQueryString = False;

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editorgs.php";		// Insert new row link points to this target
$obj->editTarget 		= "editorgs.php";		// Update row link points to this target
$obj->deleteTarget 	= "editorgs.php";		// Delete row link points to this target

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
$obj->addColumn("orgname","Organisation");
$obj->addColumn("contact","Contact");
$obj->addColumn("mobile","Mobile");
$obj->addColumn("phone","Phone");
$obj->addColumn("email","Email");

/*********
Columns to display in table
Add columns - args are 	1) url of page to link to 
												2) Column Header Title	
The link will have the primary key values appended as arguments												
****************/
//$obj->addColumnLink('Column Title1','garbage.php1','Anchor Garbage1');
//$obj->addColumnLink('Column Title2','garbage.php2','Anchor Garbage2');

//$obj->tableClass = "error";						// CSS Class for table 
//$obj->tableId = "centercontent";      // CSS Id for table
//$obj->thClass = "error";							// CSS Class for table header
//$obj->tdClass = "error";         			// CSS Class for table data row 

$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page


echo "<div class=\"cdnform\">\n";
$obj->exec();														// Build the table
echo "</div>\n";

?>

<!-- ------------------ FOOTER --------------------------------------- -->
<HR>

</body>
</html>
<!-- ---------------- END FOOTER ------------------------------------- -->
