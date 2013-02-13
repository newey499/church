<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";

/******************************
	Start a session. This must be the very first thing done on the page.
**********************************/
require_once("../session.php");
$oSession = new session();

require_once('smartysetup.php');

$pageTitle = "Check Menus";			// Set Page title


class menuCheck
{

	function _construct()
	{
	}

	function _destruct()
	{
	}

	public function exec()
	{
		$this->writeHeaders();
		$this->itemsWithoutHeaders();
		$this->headersWithoutItems();
	}

	function	writeDummyHeader($group, $hdr)
	{

		$stmnt = sprintf("INSERT INTO menutitles
											(	itemtype, itemgroup, isvisible,
												prompt,target,lastupdate
											)
											VALUES ('%s', %d, '%s',
															'%s','%s', '%s')",
											'MENU_TITLE',
											mysql_real_escape_string($group),
											mysql_real_escape_string('YES'),
											mysql_real_escape_string($hdr),
											mysql_real_escape_string(''),
											date('Y-m-d')
											);

		if (mysql_query($stmnt)) {
			setLastupdatedDate( date('d/m/Y'), date('H:i'));
			print("<p>" . $msg . "</p>\n");
			return True;
		}
		else {
			print("<p>" . mysql_error() . "</p>\n");
			return False;
		}



	}



	function writeHeaders()
	{
		if (isset($_POST["btnWriteHeaders"]))
		{

			print("<h4>\n");
			print("Writing Dummy Menu Headers for following Groups\n");
			print("</h4>\n");

			$qry = "select distinct itemgroup from menuitems where itemgroup not in (select itemgroup from menutitles)";

			$result = mysql_query($qry);

			// Check result
			// This shows the actual query sent to MySQL, and the error. Useful for debugging.
			if (!$result)
			{
					$message  = 'Invalid query: ' . mysql_error() . "\n";
					$message .= 'Whole query: ' . $query;
					die($message);
			}

			echo "<p>\n";
			echo "<table>\n";

			echo "<tr>";
			echo "<th>";
			echo "Group\n";
			echo "</th>";
			echo "<th>";
			echo "Group Name\n";
			echo "</th>";
			echo "</tr>\n";

			while ($row = mysql_fetch_assoc($result))
			{
				$hdr = "Dummy Hdr " . $row['itemgroup'];
				echo "<tr>";
				echo "<td>";
				echo $row['itemgroup'];
				echo "</td>";
				echo "<td>";
				echo $hdr;
				echo "</td>";
				echo "</tr>";
				writeDummyHeader($row['itemgroup'], $hdr);
			}
			echo "</table>\n";
			echo "</p>\n";
			mysql_free_result($result);

		}
	}


	function itemsWithoutHeaders()
	{
		print("<h4>\n");
		print("Menu items without menu header\n");
		print("<h4>\n");


		$qry = "select distinct itemgroup from menuitems where itemgroup not in (select itemgroup from menutitles)";


		$result = mysql_query($qry);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.
		if (!$result)
		{
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
		}

		echo "<p>\n";

		echo "<table>\n";

		echo "<tr>";
		echo "<th>";
		echo "Group\n";
		echo "</th>";
		echo "</tr>\n";


		while ($row = mysql_fetch_assoc($result))
		{
			echo "<tr>";
			echo "<td>";
			echo $row['itemgroup'];
			echo "</td>";
			echo "</tr>";
		}

		echo "</table>\n";
		echo "</p>\n";


		if (mysql_num_rows($result) > 0)
		{
			print('<form action="menucheck.php" ');
			print('method="POST"');
			print('target="_self">');

			print('<input type="submit" name="btnWriteHeaders" value="Write Dummy Menu Headers">');

			print('</form>');
		}

		mysql_free_result($result);

	}



	function headersWithoutItems()
	{

		print("<h4>\n");
		print("Menu headers without menu items\n");
		print("<h4>\n");

		$qry = "select distinct itemgroup, prompt from menutitles where itemgroup not in (select distinct itemgroup from menuitems)";


		$result = mysql_query($qry);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.
		if (!$result)
		{
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
		}

		echo "<p>\n";
		echo "<table>\n";

		echo "<tr>";
		echo "<th>";
		echo "Group\n";
		echo "</th>";
		echo "<th>";
		echo "Group Name\n";
		echo "</th>";
		echo "</tr>\n";

		while ($row = mysql_fetch_assoc($result))
		{
			echo "<tr>";
			echo "<td>";
			echo $row['itemgroup'];
			echo "</td>";
			echo "<td>";
			echo $row['prompt'];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		echo "</p>\n";
		mysql_free_result($result);

	}


}
?>




<?php
/*********************************************************

	Page entry Point

**********************************************************/

$obj = new menuCheck();

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);
$smarty->assign_by_ref('obj', $obj);
$smarty->display('menucheck.tpl');



?>