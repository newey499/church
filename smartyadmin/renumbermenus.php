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

require_once('smartysetup.php');

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
class renumberMenus
{
	function _construct()
	{
	}

	function _destruct()
	{
	}

	public function exec()
	{
		print("<h4>function renumberMenus()</h4>\n");
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

}


/**************************
 Page Entry Point
****************************/


$pageTitle = "Renumbering menus table with increments of 100";

$obj = new renumberMenus();

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);
$smarty->assign('msg', $msg);
$smarty->assign_by_ref('obj', $obj);
//$smarty->display('renumbermenus.tpl');
$smarty->display('disabledrenumbermenus.tpl');

?>