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


$pageTitle = "Browse Newsletters";			// Set Page title


/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
***********************/

$qry = "SELECT	id,
								DATE_FORMAT(publicationdate,'%d/%m/%Y') as pubdate,
							 	title,
								filename,
							 	newslettertext,
								emailed
        FROM newsletters
        ORDER BY publicationdate DESC";

$obj = new dumpQryToTable($oSession->dbHandle,$qry);

$obj->displayQueryString = False;

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editnewsletter.php";		// Insert new row link points to this target
$obj->editTarget 		= "editnewsletter.php";		// Update row link points to this target
$obj->deleteTarget 	= "editnewsletter.php";		// Delete row link points to this target

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
$obj->addColumn("emailed","Emailed");
$obj->addColumn("pubdate","Date");
$obj->addColumn("title","title");
$obj->addColumn("filename","Filename");
$obj->addColumn("newslettertext","Text");


$obj->addColumnLink("Publish", "editnewsletter.php?opcode=" . NEWSLETTER_PUBLISH, "Email this Newsletter");

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

$smarty->display('bnewsletter.tpl');



?>


