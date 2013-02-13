<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";

/******************************
	Start a session. This must be the very first thing done on the page.

	The session Classes constructor also opens the database.
**********************************/
require_once("../session.php");
$oSession = new session();


$pageTitle = "Browse Menu Titles";			// Set Page title



/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
***********************/

$qry = "SELECT	id,
								itemtype,
							 	menucol,
							 	itemgroup,
							 	itemorder,
								isvisible,
							 	prompt,
							 	target,
								lastupdate
        FROM menutitles
        ORDER BY itemgroup";

$obj = new dumpQryToTable($dbHandle,$qry);

$obj->displayQueryString = False;

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editmenutitles.php";		// Insert new row link points to this target
$obj->editTarget 		= "editmenutitles.php";		// Update row link points to this target
$obj->deleteTarget 	= "editmenutitles.php";		// Delete row link points to this target

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
//$obj->addColumn("menucol","Column");
$obj->addColumn("itemgroup","Group");
//$obj->addColumn("itemorder","Item");
$obj->addColumn("isvisible","Visible");
$obj->addColumn("prompt","Prompt");


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


require('smartysetup.php');

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);

// if you want to use the traditional object parameter format, pass a boolean of false
//$smarty->register_object('browseObject',$obj);
//$smarty->register_object('foobar',$obj,null,false);
$smarty->assign_by_ref('browseObject', $obj);

$smarty->display('bmenutitles.tpl');

?>


