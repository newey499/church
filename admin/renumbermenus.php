<?php
/**************************

	menurenumber.php

Renumbers the itemgroup and itemorder to have increments of 100.

Also takes into account menucol ("LEFT" or "RIGHT")



09/12/08	CDN		Created.

***************************/

require_once("../dbconnectparms.php");
require_once("../globals.php");
require_once("../genlib.php");
require_once("../mysql.php");
require_once("../dumpquerytotable.php");
require_once("../class.cdnmail.php");
require_once("../mysqldatetime.php");


/******************************
	Start a session. This must be the very first thing done on the page.
**********************************/
require_once("../session.php");
$oSession = new session();

/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

/********
 Update the menu table with the new group and item numbers
***************/
function updateItem($id, $newGroup, $newItem)
{
	$qry = sprintf( "UPDATE menus " .
									"SET " .
									" itemgroup = %d, " .
									" itemorder = %d " .
									"WHERE id = %d",
									$newGroup,
									$newItem,
									$id);

	if (! mysql_query($qry))
	{
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $qry;
    die($message);
	}

}


/********
 Renumber the menu items
***************/
function renumberMenus()
{

	$qryLeft = sprintf('SELECT id, itemgroup, itemorder, menucol, prompt ' .
										 'FROM menus ' .
										 'WHERE menucol = "%s" ' .
										 'ORDER BY itemgroup, itemorder ',
										 'LEFT');

	$qryRight = sprintf('SELECT id, itemgroup, itemorder, menucol, prompt ' .
											'FROM menus ' .
										  'WHERE menucol = "%s" ' .
										  'ORDER BY itemgroup, itemorder ',
										  'RIGHT');

	if (! $resLeft = mysql_query($qryLeft))
	{
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $qryLeft;
    die($message);
	}

	if (! $resRight = mysql_query($qryRight))
	{
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $qryRight;
    die($message);
	}

	$currentGroup;
	$newGroup = 100;
	$newItem = NULL;
	$firstTime = TRUE;

	/***********
		Every Group LEFT and RIGHT is reset so that the Group numbers
		are unique - ie. no shared group numbers in LEFT and RIGHT columns
	*******************/
	while ($row = mysql_fetch_assoc($resLeft))
	{
		if ($firstTime)
		{
			$firstTime = FALSE;
			$currentGroup = $row['itemgroup'];
			$newItem = 100;
		}
		else
		{
			if ($currentGroup != $row['itemgroup'])
			{
				$newGroup += 100;
				$newItem  = 100;
				$currentGroup = $row['itemgroup'];
			}
			else
			{
				$newItem  += 100;
			}
		}
    echo $row['menucol'] . " ";
    echo str_pad($row['itemgroup'], 4, "*", STR_PAD_LEFT) . " ";
    echo str_pad($row['itemorder'], 4, "*", STR_PAD_LEFT) . " ";
    //echo str_pad($row['id'], 4, "*", STR_PAD_LEFT) . " ";
    echo " NEW " . str_pad($newGroup, 4, "*", STR_PAD_LEFT) . " ";
    echo " " . str_pad($newItem, 4, "*", STR_PAD_LEFT) . " ";
		echo "<br /> \n";

		updateItem($row['id'], $newGroup, $newItem);
	}

	$firstTime = TRUE;
	$newGroup += 100;

	while ($row = mysql_fetch_assoc($resRight))
	{
		if ($firstTime)
		{
			$firstTime = FALSE;
			$currentGroup = $row['itemgroup'];
			$newItem = 100;
		}
		else
		{
			if ($currentGroup != $row['itemgroup'])
			{
				$newGroup += 100;
				$currentGroup = $row['itemgroup'];
				$newItem  = 100;
			}
			else
			{
				$newItem  += 100;
			}
		}

    echo $row['menucol'] . " ";
    echo str_pad($row['itemgroup'], 4, "*", STR_PAD_LEFT) . " ";
    echo str_pad($row['itemorder'], 4, "*", STR_PAD_LEFT) . " ";
    //echo str_pad($row['id'], 4, "*", STR_PAD_LEFT) . " ";
    echo " NEW " . str_pad($newGroup, 4, "*", STR_PAD_LEFT) . " ";
    echo " " . str_pad($newItem, 4, "*", STR_PAD_LEFT) . " ";
		echo "<br /> \n";

		updateItem($row['id'], $newGroup, $newItem);
	}



	mysql_free_result($resLeft);
	mysql_free_result($resRight);
}



/**************************
 Page Entry Point
****************************/
?>
<!doctype html public "-//w3c//dtd html 3.2//en">

<html>

<head>
<title>Renumber Menus</title>

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


<?php
	require_once('topbanner.php');
?>

<br />

<h2>Renumbering menus table with increments of 100</h2>
<hr />

<p>

<div class="fixedwidth">
<?php
	require_once('topbanner.php');

	renumberMenus();
?>
</div>

</p>


<h4>Completed</h4>

<hr />

<?php

printLinkToReferrer();

?>

</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->

</body>

</html>
