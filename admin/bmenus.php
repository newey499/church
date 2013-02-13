<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";
$pageTitle = "Browse Menus";			// Set Page title
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
<body>

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">


<!-- ------------------ HEADER --------------------------------------- -->
<?php

require_once('topbanner.php');

print("<H3>$pageTitle</H3>\n");

//print("<h2>cursorpos [" . $_GET['cursorpos'] . "]</h2>\n");
?>
<hr />
<!-- ---------------- END HEADER ------------------------------------- -->



<?php


/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

/******************
 Set or reset lastupdated timestamp
 if required
*************************/
if ( isset($_GET['opcode']) &&
	   ($_GET['opcode'] == MENU_MARK_NEW || $_GET['opcode'] == MENU_MARK_NOT_NEW)
   )
{
	changeLastUpdatedTimeStamp($_GET['opcode'], $_GET['id']);
	$_GET['opcode'] = NULL;
}


setMenuFilter();


function printEnumComboBox()
{
	$finput = "";
	$oMySqlj = new mysqlj(); // Object with utility MySql objects
	// If we have been given a specific default value then use that
	// otherwise use the default held on the database
	// Note $default passed by reference to enable default value to be made available
	// So this line gets the enum entries and the default on the database
	$table = 'menus';
	$column = 'itemtype';
	$default = 'MENU_ITEM';
	$enumValues = $oMySqlj->getEnumValues($table,$column,$default);

	$finput = "<select name=\"" . $column . "\">\n";

	// Default is no filter
	$finput = $finput . "<option value=\"" . 'NO_FILTER' . "\"";
	if (! isset($_POST['itemtype']) )
	{
		$finput = $finput . " selected ";
	}
	$finput = $finput . ">" . 'NO_FILTER' . "</option>\n";

	foreach($enumValues as $enumkey => $enumvalue)
	{
		$finput = $finput . "<option value=\"" . $enumvalue . "\"";

		if (isset($_POST['itemtype']) )
		{
			if ($enumvalue == $_POST['itemtype'])
			{
				$finput = $finput . " selected ";
			}
		}

		$finput = $finput . ">" . $enumvalue . "</option>\n";

	}
	$finput = $finput . "</select>\n";

	return $finput;
}


function setMenuFilter()
{
	print("<br />\n");
	print("<div class=\"cdnform\">\n");
	print("<form name=\"formfilter\" method=\"post\" action=\"bmenus.php\">\n");
	print("<b>Set Menu Type Filter</b> \n");
	print(printEnumComboBox());
	print("<BUTTON TYPE=submit NAME=\"updatefilter\" VALUE=\"updatefilter\" >Change Filter</BUTTON>\n");
	print("</form>\n");
	print("</div>\n");
}

function changeLastUpdatedTimeStamp($opcode, $id)
{
	$qry1 = sprintf("SELECT	prompt " .
					        "FROM menus " .
									"WHERE id = %d",
									$id);

	if (! $result = mysql_query($qry1))
	{
		print("<h2>SELECT Failed on id [" . $id . "]</h2>\n");
		return;
	}
	else
	{
		$row = mysql_fetch_assoc($result);
		$prompt = $row['prompt'];
		mysql_free_result($result);
	}

	if ($opcode == MENU_MARK_NEW)
	{
		// set lastupdate to Now
		$lastUpdated = date("Y-m-d H:i", time());

		/**********
			If and only if we are marking an item as new
			Update the sysconf tables system last updated timestamp
		*****************/
		$qry = sprintf("UPDATE sysconf " .
	                 "SET lastUpdated = '%s' " .
	                 "WHERE id = %d ",
									 $lastUpdated,
									 1);

		if (! mysql_query($qry))
		{
			print("<h2>[" . $row['prompt'] . "] Update Failed " .
						$opcode . " " . $id . " set lastupdate to [" . $lastUpdated . "]</h2>\n");
		}
		else
		{
			print("<h2>[" . $row['prompt'] . "] lastupdate set to [" . $lastUpdated . "] on sysconf table</h2>\n");
		}

	}
	else if ($opcode == MENU_MARK_NOT_NEW)
	{
		// set lastupdate to Now - 8 days
		// (The application regards anything as new that has a
		// lastupdate value that is <= 7 days old - the value of 7 days is defined in globals.php
		// as RECENT_PAGE_UPDATE_DAYS
		$date = new DateTime();
		$date->modify("-" . (RECENT_PAGE_UPDATE_DAYS + 1) . " day");
		//echo $date->format("Y-m-d H:i");
		//$lastUpdated = date('Y-m-d H:i', time());
		$lastUpdated = $date->format("Y-m-d H:i");
	}
	else
	{
		print("<h4>Invalid opcode [" . $opcode . "] passed</h4>\n");
		return;
	}

	/**********
		Update the Menu Item
	*****************/
	$qry = sprintf("UPDATE menus " .
                 "SET lastupdate = '%s' " .
                 "WHERE id = %d ",
								 $lastUpdated,
								 $id);

	if (! mysql_query($qry))
	{
		print("<h2>[" . $row['prompt'] . "] Update Failed " .
					$opcode . " " . $id . " set lastupdate to [" . $lastUpdated . "]</h2>\n");
	}
	else
	{
		print("<h2>[" . $row['prompt'] . "] lastupdate set to [" . $lastUpdated . "] on menus table</h2>\n");
	}

}


/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
 NOTE - menuitems is a view of menus
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
        FROM menus ";

if (! isset($_POST['itemtype']) || $_POST['itemtype'] == "NO_FILTER" )
{
	// No Filter - no where clause
}
else
{
	$qry .= "WHERE itemtype = '" . $_POST['itemtype'] . "' ";
}

$qry .= " ORDER BY itemgroup, itemtype DESC, itemorder";

$obj = new dumpQryToTable($dbHandle,$qry);

$obj->displayQueryString = False;

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editmenus.php";		// Insert new row link points to this target
$obj->editTarget 		= "editmenus.php";		// Update row link points to this target
$obj->deleteTarget 	= "editmenus.php";		// Delete row link points to this target

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
$obj->addColumn("menucol","Column");
$obj->addColumn("itemtype","Type");
$obj->addColumn("itemgroup","Group");
$obj->addColumn("itemorder","Item");
$obj->addColumn("isvisible","Visible");
$obj->addColumn("prompt","Prompt");


/*********
Columns to display in table
Add columns - args are 	1) url of page to link to
												2) Column Header Title
The link will have the primary key values appended as arguments
****************/
$obj->addColumnLink('Touch','bmenus.php?opcode=' . MENU_MARK_NEW, 'Touch');
$obj->addColumnLink('Clear','bmenus.php?opcode=' . MENU_MARK_NOT_NEW, 'Clear');
//$obj->addColumnLink('Column Title2','garbage.php2','Anchor Garbage2');

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