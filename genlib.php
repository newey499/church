<?php

/********************************************
 General purpose functions

Date   Programmer		Description
23/11/08 CDN		Add Sysconf class
16/03/10 CDN		Add AJAX Call - function loadXMLDoc(url, targetId )
27/05/10 CDN 		Added function function backToSermonsTalksPage() for AJAX
20/06/10 CDN    	Stop using AJAX Calls - leave functions in place just in case they are
					needed in the future.
22/06/10 CDN    	Added dateDiff function
02/05/12 CDN		Refactor buildLinksFromTable to strip out the building of the target URL
					and place in new function buildTargetUrl($row)
11/05/12 CDN 		Fix bug that that stops blank line between menu groups if first
					menu group doesn't have a title. Fix is to explicitly set $forceNewLine
					on every pass through loop.
 **********************************************/
require_once('set_include_path.php');
require_once('globals.php');
require_once('class.google.url.short.php');
require_once('class.twitter.php');

// Emulate function released in PHP 5
// provides a string formatted use as arguments to a web page
// 05/12/04	CDN		Comment this out - now using PHP Version 5 which implements this function
// 25/02/05 CDN		Use if_function_exists instead
// 02/12/08 CDN - The PHP 5 function http_build_query() does not produce xhtml 1.0 transitional parameter strings
//                correctly so my version is forced to be called instead
// 07/09/09 CDN   PHP fixed the parsing - inserted logical not so this is never called
//
if (!function_exists('http_build_query'))
{
	function http_build_query($data)
	{
		return cdn_http_build_query($data);
	}
}

function cdn_http_build_query($data)
{
	$ret = "";
	//print("<br /><h4>CDN debug 0031 item data [" . var_dump($data) . "]</h4><br />");
	foreach ($data as $index => $item)
	{

		//print("<br /><h4>CDN debug 0032 item rowid [" . var_dump($item) . "]</h4><br />");
		//print("<br /><h4>CDN debug 0033 item rowid [" . $item . "]</h4><br />");
		$ret = $ret . $index . '=' .
						//quoteNumerics($item) . 	// CDN 02/12/08 - encase $item in Quotes if it is numeric
						$item . 	// CDN 04/04/2012 - quoteNumerics() breaks the menu prompt creationen
            '&amp;';
		//print("<br /><h4>CDN debug 004 item rowid [" . $item . "]</h4><br />");
		//print("<br /><h4>CDN debug 005 ret [" . $ret . "]</h4><br />");
	}
	$ret = str_replace(" ", "+", "$ret");
	$ret = substr($ret,0,-5);		// lose the final char in the string - superflous '&amp;'
	return $ret;
}

function quoteNumerics($str)
{
	if (is_numeric($str))
	{
		$str= '"' . $str . '"';
	}
	return $str;
}

// ===================================================================
/****************

10/11/2010	CDN		Add writeJScriptForMenus method to create Javascript to hide/show menu items

****************************/
Class MenuTitles
{
	private $titlesEnabled = false;

	function __construct()
	{
		$qry =  'SELECT * FROM sysconf WHERE id = 1 ';
		$res = mysql_query($qry);

		if ($row = mysql_fetch_assoc($res))
		{
			$this->titlesEnabled = $row['showmenuhdr'];
		}
		mysql_free_result($res);
	}

	function __destruct()
	{

	}


	function writeJScriptForMenus()
	{
		$qry =  'SELECT * FROM menutitles ' .
						' WHERE isvisible = "YES" ' .
						'   AND (itemtype = "MENU_TITLE") ' .
						' ORDER BY menucol, itemgroup, itemorder';

		$res = mysql_query($qry);

		$crntGrp = '';
		while ($row = mysql_fetch_assoc($res))
		{
			// CDN 25/6/7
			// New column isvisible on table
			// isvisible ENUM('YES', 'NO') NOT NULL DEFAULT 'YES'
			if ( ! ($row['isvisible'] == 'YES') )
			{
				continue;		// Ignore the menu entry
			}

			print("\n");
			print("\t\t$('#menu_group_items_" . $row['itemgroup'] . "').hide();");
			print("\n");

			print("\t\t$('#menu_group_" . $row['itemgroup'] . "').mouseover(function(event){\n");
			print("\t\t   $('#menu_group_items_" . $row['itemgroup'] . "').show(); \n");
			print("\t\t});\n");

			print("\t\t$('#menu_group_" . $row['itemgroup'] . "').mouseout(function(event){\n");
			print("\t\t   $('#menu_group_items_" . $row['itemgroup'] . "').hide();\n");
			print("\t\t});\n");

			print("\n");
			print("\n");

		}

		mysql_free_result($res);
	}



	function buildMenuTitles($crntGrp)
	{
		if (! $this->titlesEnabled)
		{
			return;
		}


		$qry =  'SELECT * FROM menutitles ' .
						' WHERE itemgroup = ' . $crntGrp . ' ' .
						'   AND isvisible = "YES" ' .
						'   AND (itemtype = "MENU_TITLE") ' .
						' ORDER BY menucol, itemgroup, itemorder';

		$res = mysql_query($qry);

		$crntGrp = '';
		while ($row = mysql_fetch_assoc($res))
		{
			// CDN 25/6/7
			// New column isvisible on table
			// isvisible ENUM('YES', 'NO') NOT NULL DEFAULT 'YES'
			if ( ! ($row['isvisible'] == 'YES') )
			{
				continue;		// Ignore the menu entry
			}

			print("<span class=\"menutitle\">" . $row['prompt'] . "</span><br />\n");
		}

		mysql_free_result($res);

	}

} // end Class MenuTitles
// ===================================================================


// causes the content to be loaded from the file pointed to by the target field
// Creates a link to $target with prompt = $prompt
function buildmenuitemexternalajax($rowId, $prompt, $lastupdate = "", $lineBreak = FALSE, $menuItemType='MENU_ITEM')
{

$htmlIdToLoad = 'ajaxcentercontent';

// loadXMLDoc(url, targetId )
print "<a href=\"$target\"  class=\"menuitem\" ";
//print "<a href=\"\"  class=\"menuitem\" ";
//print "		onmouseover=\"this.style.color='Orange';\"\n";
//print "		onmouseout=\"this.style.color='#3675A3';\"\n";
//print "		onclick=\"loadXMLDocExternal('ajaxgetexternal.php'," . $rowId . ", '" . $htmlIdToLoad . "' ); return false;\"\n";
print ">\n";
print $prompt;
print "</a>\n";

/********
Display an asterisk before any menu item that has been
updated in the last RECENT_PAGE_UPDATE_DAYS which is defined in globals.php
*************/
if ( isItemRecentlyUpdated($lastupdate) )
{
	recentUpdateFlag();
}

if ($lineBreak)
{
	if ($menuItemType == 'MENU_ITEM_DROP')
	{
		print " | \n";
	}
	else
	{
		print "<br />\n";
	}
}

//print "</p>\n";

}


// ===================================================================
// causes the content to be loaded from the content blob field
// Creates a link to $target with prompt = $prompt
function buildmenuiteminternalajax($rowId, $prompt, $lastupdate = "", $lineBreak = FALSE, $menuItemType='MENU_ITEM')
{

$htmlIdToLoad = 'ajaxcentercontent';

// loadXMLDoc(url, targetId )
print "<a href=\"$target\"  class=\"menuitem\" ";
//print "<a href=\"\"  class=\"menuitem\" ";
//print "		onmouseover=\"this.style.color='Orange';\"\n";
//print "		onmouseout=\"this.style.color='#3675A3';\"\n";
//print "		onclick=\"loadXMLDocInternal('ajaxgetinternal.php'," . $rowId . ", '" . $htmlIdToLoad . "' ); return false;\"\n";
print ">\n";
print $prompt;
print "</a>\n";

/********
Display an asterisk before any menu item that has been
updated in the last RECENT_PAGE_UPDATE_DAYS which is defined in globals.php
*************/
if ( isItemRecentlyUpdated($lastupdate) )
{
	recentUpdateFlag();
}

if ($lineBreak)
{
	if ($menuItemType == 'MENU_ITEM_DROP')
	{
		print " | \n";
	}
	else
	{
		print "<br />\n";
	}
}

//print "</p>\n";

}





// Creates a link to $target with prompt = $prompt
function buildmenuitem($target,$prompt,$lastupdate = "", $lineBreak = FALSE, $menuItemType='MENU_ITEM')
{

// page load
print "<a href=\"$target\"  class=\"menuitem\" ";
// AJAX load
//print "<a href=\"\"  class=\"menuitem\" ";
//print "		onmouseover=\"this.style.color='Orange';\"\n";
//print "		onmouseout=\"this.style.color='#3675A3';\"\n";
//print "		onclick=\"loadXMLDoc('ajaxgetinternal.php', 'ajaxcentercontent' ); return false;\"\n";

// close anchor
print ">\n";
print $prompt;
print "</a>\n";

/********
Display an asterisk before any menu item that has been
updated in the last RECENT_PAGE_UPDATE_DAYS which is defined in globals.php
*************/
if ( isItemRecentlyUpdated($lastupdate) )
{
	recentUpdateFlag();
}

if ($lineBreak)
{
	if ($menuItemType == 'MENU_ITEM_DROP')
	{
		print " | \n";
	}
	else
	{
		print "<br />\n";
	}
}

//print "</p>\n";

}

function isItemRecentlyUpdated($lastupdate)
{
// 7 days; 24 hours; 60 mins; 60secs
// RECENT_PAGE_UPDATE_DAYS is defined in globals.php
$weekSeconds = (RECENT_PAGE_UPDATE_DAYS * 24 * 60 * 60);
if ( strtotime('now') < (strtotime($lastupdate) + $weekSeconds) )
{
	return TRUE;
}

return FALSE;

}


function recentUpdateFlag()
{
	print("<span class=\"new\">&nbsp;New</span>\n");
}

function dbug($str) {
	print("\n<pre>" . print_r($str) . "</pre>\n");
}


function insertSpaces($number)
{
	for ($i = 1; $i <= $number; $i++)
	{
		echo "&nbsp;";
	}
	echo "\n";
}


/**************
 Displays the passed string using the class "error"
 ******************************************************/
function dispError($str,$noPara = False) {
	if ($noPara) {
		print("<span class=\"error\">$str</span>\n");
	}
	else {
		print("<p><span class=\"error\">$str</span></p>\n");
	}
}

function dispWarn($str, $noPara= False) {
	if ($noPara) {
		print("<span class=\"error\">$str</span>\n");
	}
	else {
		print("<p><span class=\"error\">$str</span></p>\n");
	}
}



// =================================

function setLastupdatedDate($pSqlDate, $pSqlTime = "00:00")
{

	$oDate = new mysqlDate();
	$oTime = new mysqlTime();

	$sqlDate = $oDate->UkDateToMySql( $pSqlDate );
	$sqlTime = $pSqlTime;

	if (! $sqlDate)
	{
		die("[" . $pSqlDate . "] is not a valid date. Format should be DD/MM/YYYY.");
	}


	if (! $oTime->checkTime($pSqlTime) )
	{
		die("Update failed: Invalid Time [" . $pSqlTime . "]");
	}
	else
	{
		$sqlTime = $pSqlTime;
	}


	$qry = "UPDATE sysconf " .
	       "SET lastUpdated = \"" . $sqlDate . " " . $sqlTime . "\"";

	if (! mysql_query($qry) )
	{
    die('Update failed: ' . mysql_error());
	}


	return true;
}



// ==================================

// ==================================
/********************
02/05/2012	CDN	Add ability to send Tweet

********************************/
function touchMenuItem($menuPrompt, $msg = "", $sendToTwitter=false, $sendToFacebook=false)
{


	$qry =	"UPDATE menus " .
					" SET lastupdate = CURRENT_DATE()" .
					"WHERE UCASE(prompt) = UCASE('" . $menuPrompt . "')";

	if (! mysql_query($qry) )
	{
		die('touchMenuItem failed: [' . $menuPrompt . "] " . mysql_error());
	}

	return true;
}
// ==================================

// ==================================

function getLastUpdatedDate()
{
	return getLastUpdateTimestamp();
}

// ==================================

	function getLastUpdateTimestamp()
	{
		$qry = "SELECT DATE_FORMAT(lastUpdated, '%H:%i %d/%m/%Y') as formattedDate
        FROM sysconf
        LIMIT 1";


		$row = mysql_query($qry);
		if (!$row)
		{
			die("Query failed: [$qry] " . mysql_error());
		}

		$cols = mysql_fetch_assoc($row);

		return $cols['formattedDate'];

	}




	/*******************
	function to build menu links for left and right columns of main page
	valid values for $column are "LEFT" and "RIGHT"
	**************************************************/
	function buildLinksFromTable($column, $menuTypes = array('MENU_ITEM','MENU_ITEM_PAGE'))
	{

		$oMenuTitle = new MenuTitles();

		// Only force a newline ("<br />") if building a left or right column menu
		//$forceNewLine = ($column == 'LEFT' || $column == 'RIGHT');
		// What gets appended to a menu item link now depends upon what type of
		// menu item it is
		$forceNewLine = TRUE;

		/* Connect to a MySQL server */
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

		$qry  = 'SELECT * FROM menus ';
		if ( ! empty($column) )
		{
			$qry .=	' WHERE menucol = "' . $column . '" ';
			$qry .= '   AND isvisible = "YES" ';
		}
		else
		{
			$qry .=	' WHERE ';
		  $qry .= '   isvisible = "YES" ';
		}
		//$qry .= '   AND isvisible = "YES" ';
		//$qry .= '   AND (itemtype = "MENU_ITEM" OR itemtype = "MENU_ITEM_PAGE") ';

		$qry .= '   AND ( ';
		$lastItem = end($menuTypes);
		reset($menuTypes);
		foreach ($menuTypes as $menuType)
		{
			$qry .= "    itemtype = '" . $menuType . "' ";
			if (! ($menuType == $lastItem) )
			{
				$qry .= " OR ";
			}
		}
		$qry .= '       )';
		$qry .= ' ORDER BY menucol, itemgroup, itemorder';

		$res = mysql_query($qry);
		$firstGroup = true;
		$crntGrp = '';
		while ($row = mysql_fetch_assoc($res))
		{

			// CDN 25/6/7
			// New column isvisible on table
			// isvisible ENUM('YES', 'NO') NOT NULL DEFAULT 'YES'
			if ( ! ($row['isvisible'] == 'YES') )
			{
				continue;		// Ignore the menu entry
			}


			if ( ($crntGrp != $row['itemgroup'])) {

				//print("<h4>current [" . $crntGrp . "] row [" . $row['itemgroup'] . "]</h4>");

				if ($firstGroup)
				{
					mysql_data_seek($res, mysql_num_rows($res) -1); // GOTO last row
					$row = mysql_fetch_assoc($res); // fetch the last row
					$lastMenuItem = $row['prompt'];
					mysql_data_seek($res, 0); // Return to first row
					$row = mysql_fetch_assoc($res); // refetch the first row
					$firstGroup = false;
				}
				else
				{
					//print("<h4>html newline</h4>");
					echo "<br />";
				}
				$crntGrp = $row['itemgroup'];
				$oMenuTitle->buildMenuTitles($crntGrp);
			}

			// If we ain't got a prompt refuse to play
			if (trim($row['prompt']) == '') {
				die("Invalid Menu record configuration (Empty Prompt) menus.rowid=" . $row['rowid']);
			}

			// If this the last menu item then don't force a new line
			// 11/05/2012 CDN 	Fix bug that that stops blank line between menu groups if first
			//					menu group doesn't have a title. Fix is to explicitly set $forceNewLine
			//					on every pass through loop
			if ($lastMenuItem == $row['prompt'])
			{
				$forceNewLine = FALSE;
			}
			else
			{
				$forceNewLine = TRUE;
			}

			$target =  buildTargetUrl($row);
			buildmenuitem($target,
						  stripslashes($row['prompt']),
						  $row['lastupdate'],
						  $forceNewLine,
						  $row['itemtype']);

		}

		mysql_free_result($res);

	}


	function buildTargetUrl($row)
	{
		$target = "";

		// if a link to a target file is given then that takes priority
		if (trim($row['target']) <> '')
		{
			// build link based upon assumption that external links will commence with
			// "http:"
			if (strToUpper(substr($row['target'],0,5)) == 'HTTP:')
			{
				$target = $row['target'];
			}
			else
			{
				$target = 'index.php?displaypage=' . $row['target'];
			}
		}
		else
		{
			// no link to file - see if there's any text in the blob (text) field
			if (trim($row['content']) <> '')
			{

				// CDN 02/12/08 - $row['id'] is the primary get field onthe menus table
				//                rowid is the $_GET string that index.php expects the
				//                primary key for internal page content to be in
				$args = cdn_http_build_query(array('displaypage' => INTERNAL_CONTENT_FLAG,
													'rowid' => $row['id'])	);

				$target = 'index.php?' . $args . $row['target'];

			}
			else
			{
				die("Invalid Menu record configuration menus.rowid=[" . $row['rowid'] . "] content is empty");
			}
		}

		return $target;
	}


	function getInternalContent($rowid)
	{
		/************
		CDN 18/2/10

		Ensure $rowid is numeric and an integer
		********************/
		if (! is_numeric($rowid) )
		{
			return "<br /> <p><h4>Error 1: rowid of [" . $rowid  . "] is not numeric</h4></p>\n";
		}
		else
		{
			if ( (intval($rowid) != $rowid) || ( ! (strpos($rowid, '.') === false) ) )
			{
				return "<br /> <p><h4>Error 2: rowid of [" . $rowid  . "] is not an integer</h4></p>\n";
			}
		}

		/* Connect to a MySQL server */
		/**************
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');
		**********************/

		$qry =  sprintf("SELECT * FROM menus WHERE id = %d", mysql_real_escape_string($rowid));

		$res = mysql_query($qry);

		if (! $row = mysql_fetch_assoc($res))
		{
			print("<br /> <h4>rowid [" . $rowid . "] does not exist</h4> \n");
		}

		$tmp = trim(stripslashes($row['content']));
		if (! empty($tmp))
		{
			$content = $tmp;
		}
		else
		{
			$content = "<br /> <h4>menu row id [" . $rowid  . "] target [" . $row['target'] . "] - no contents to load</h4>\n";
		}

		mysql_free_result($res);

		return $content;
	}







class Sysconf
{
  function __construct()
	{
		// does nothing
  }

  function __destruct()
	{
		// does nothing
  }

	public function isDropMenuEnabled()
	{
		return $this->chkMenuEnabled('MENU_DROP');
	}

	public function isPageMenuEnabled()
	{
		return $this->chkMenuEnabled('MENU_PAGE');
	}

	private function chkMenuEnabled($menuType)
	{
		$qry = 'SELECT menutypes FROM sysconf WHERE id = 1';
		$isEnabled = FALSE;

		$result = mysql_query($qry);

		if (!$result)
		{
	    $message  = 'Invalid query: ' . mysql_error() . "\n";
	    $message .= 'Whole query: ' . $qry;
	    die($message);
		}


		if ( ! ($row = mysql_fetch_assoc($result)) )
		{
			// Should never happen the require should always
			// return a single row
			die("Failed to fetch sysconf row [" . $qry . "]\n");
		}
		else
		{
	    if ( ($row["menutypes"] == 'MENU_BOTH') || ($row["menutypes"] == $menuType) )
			{
				$isEnabled = TRUE;
			}
		}

		mysql_free_result($result);

		return $isEnabled;

	}

}


# Commence sniffing:
function isInternetExplorer()
{
	$b = sniffBrowser();

	if ($b == "IE_WIN" || $b == "IE_MAC")
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}

}



function sniffBrowser()
{
	# Get user agent string from browser:
	$b = $_SERVER['HTTP_USER_AGENT'];

  if	( (stristr($b, "msie"))
				/***********
					02/12/08 CDN - Too clever by half
				(
        (stristr($b, "msie 8.0")) ||
        (stristr($b, "msie 7.0")) ||
        (stristr($b, "msie 6.0")) ||
        (stristr($b, "msie 5.0")) ||
				(stristr($b, "msie 5.5")) ||
				(stristr($b, "msie 4.01"))
				)
				&& (stristr($b, "windows"))
				******************/
			)
	{
	  # If we are dealing with a windows-based IE browser, we return
		# 'Win_IE' as handle:
    return "IE_WIN";
  } elseif (stristr($b, "opera"))
	{
	  # If it's an Opera beast, we dig deeper:
    if (stristr($b, "macintosh"))
		{
	    # Hey, it's a mac Opera:
      return "OPERA_MAC";
    } elseif (stristr($b, "Opera 7.0"))
		{
      # Hey it's Opera v.7:
      return "OPERA_7";
    } else
		{
	    # Hey, it's just Opera:
      return "OPERA";
    }
	} elseif (stristr($b, "msie") && stristr($b, "mac_powerpc"))
	{
	  // We are dealing with a Mac IE. I assume version 5 at least, hence
		//	no version checking. Deal with it:
    return "IE_MAC";
  } elseif (((stristr($b, "mozilla/5.0")) || (stristr($b, "gecko"))))
	{
	  # We are dealing with a gecko based browser:
    if (stristr($b, "omniweb"))
		{
	    # Oh my, it's OmniWeb = not CSS2 compliant, at all:
      return "OMNI";
    } else
		{
		  # Either Netscape 6+, Chimera, Phoenix, Galeon, Mozilla. In
			# other words, CSS2 compliant:
      return "GECKO";
    }
  } elseif (stristr($b, "konqueror"))
	{
	  # Konqueror is down here, as it doesn't ID itself as a gecko
		# growser [not all the time anyway]. Better separate it from the rest then,
		# in case it needs a brush-up later on.
    return "KONQ";
  }

	// If we get here we are in trouble
	return "UNKNOWN";
}



/**
 * Remove HTML tags, including invisible text such as style and
 * script code, and embedded objects.  Add line breaks around
 * block-level tags to prevent word joining after tag removal.
 */
function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    return strip_tags( $text );
}


/********
16/03/10 CDN			Add AJAX Call

Uses JavaScript function (see genlib.js) to fill the HTML element
with id <targetId> with the content of file <url>

*****************/
function loadXMLDoc($url, $targetId)
{
	print("<script type='text/javascript'> \n");
	print("loadXMLDoc('" . $url . "', '" . $targetId . "' ); \n");
	print("</script> \n");
}

/***********
 Used to produce a link from a talk page back to the Sermons and Talks Page
 *******************************/
function backToSermonsTalksPage()
{
	//print("<a href=\"" . $_SERVER['HTTP_REFERER'] . "/#id=19" . "\">Back</a>\n");
	print("<a href='" . $_SERVER['HTTP_REFERER'] . "' onclick='window.history.back();return false;' >Back</a>\n");
}


// ================================================================================
// Returns > 0 if $startDate is before $endDate, 0 if $startDate == $endDate
function dateDiff(DateTime $startDate, DateTime $endDate)
{
	$diff = strtotime($endDate->format('Y-m-d H:i')) - strtotime($startDate->format('Y-m-d H:i'));

	return $diff;
}
// ================================================================================


/***********
 Creates link to original sized jpg from smaller version of image
********************************************************************/
function writeImgLink($jpg, $align, $title, $attrib)
{
	$tmp = strtoupper($align);
	$alignInImage = true;

	print('<div ');
	switch ($tmp)
	{
		case 'LEFT':
			//print('class="imgFloatLeft"');
			break;
		case 'RIGHT':
			//print('class="imgFloatRight"');
			break;
		case 'CENTER':
			print('style="text-align:center"');
			break;
		case 'NONE':
			//print('style="text-align:center"');
			break;
		default:
			//print('style="text-align:center"');
			break;
	}
	print(' >');


	print('<a href="jpgdisp.php?jpgurl=images/original/' . $jpg . '" >');
	print('<img ');
	print(' src="images/small/' . $jpg . '" ');
	print(" " . $attrib . " ");
	print("     alt=\"" . $jpg . "\"");
	print("     title=\"" . $title . " (Click to view full size)\"");
	if ($alignInImage)
	{
		switch ($tmp)
		{
			case 'LEFT':
				print('class="imgFloatLeft"');
				break;
			case 'RIGHT':
				print('class="imgFloatRight"');
				break;
			case 'CENTER':
				print('style="text-align:center"');
				break;
			case 'NONE':
				break;
			default:
				print('style="text-align:center"');
				break;
		}
	}
	print("	/>");
	print("</a>");

	print("</div>");

}


function padLines($lines)
{
	for ($i = 0; $i < $lines; $i++)
	{
		print("<br />");
	}
}



function safeGet($name)
{
	return (isset($_GET[$name]) ? $_GET[$name] : '');
}

function safePost($name)
{
	return (isset($_POST[$name]) ? $_POST[$name] : '');
}

function printLinkToReferrer()
{
	print('<a href="' . $_SERVER['HTTP_REFERER'] . '" >Back</a> <br />');
}

function cleanFileName($fname)
{
	$fname = str_replace (" ", "", $fname);
	return strtolower($fname);
}


/*******************
function to build menu links for horizontal bar menu of main page
**************************************************/
function buildLinksFromTableHbar()
{

	$oMenuTitle = new MenuTitles();

	// Only force a newline ("<br />") if building a left or right column menu
	//$forceNewLine = ($column == 'LEFT' || $column == 'RIGHT');
	// What gets appended to a menu item link now depends upon what type of
	// menu item it is
	$forceNewLine = TRUE;

	/* Connect to a MySQL server */
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

	$qry  = ' SELECT * FROM menus ';
	$qry .=	' WHERE isvisible = "YES" ';
	$qry .= ' AND (itemtype = "MENU_TITLE" OR itemtype = "MENU_ITEM_DROP") ';
	$qry .= ' ORDER BY itemgroup, itemorder';

	$res = mysql_query($qry);
	if (! $res)
	{
		print("<h2>" . mysql_error() . "</h2>");
	}


	// build the list html
	print("<ul id='nav'>\n");

	while ($row = mysql_fetch_assoc($res))
	{

		// If we ain't got a prompt refuse to play
		if (trim($row['prompt']) == '') {
			die("Invalid Menu record configuration (Empty Prompt) menus.rowid=" . $row['rowid']);
		}

		// print menu header
		print("\t<li>\n");

		// if the item is 'MENU_ITEM_DROP' then build a link
		if ($row['itemtype'] == 'MENU_ITEM_DROP')
		{
			$target =  buildTargetUrl($row);
			if (empty($target))
			{
				print("\t<a >" . $row['prompt'] . "</a>\n");
			}
			else
			{
				print("\t<a href='" . $target . "' > " . $row['prompt'] . "</a>\n");
				// if its a MENU_ITEM_DROP with a target then don't attempt to build child items
				continue;
			}
		}
		else
		{
			print("\t<a >" . $row['prompt'] . "</a>\n");
		}

		// build  menu items for the meu group we are looking at
		$qryItem  = ' SELECT * FROM menuitems ';
		$qryItem .=	' WHERE isvisible = "YES" ';
		$qryItem .= ' AND itemgroup = "' . $row['itemgroup'] . '" ';
		$qryItem .= ' ORDER BY itemorder';

		$rowItemCursor = mysql_query($qryItem);
		if (! $rowItemCursor)
		{
			print("<h2>" . mysql_error() . "</h2>");
		}

		// Don't try and build menu items if they don't exist
		if (mysql_num_rows($rowItemCursor) > 0)
		{
			// construct the menu items
			print("\t\t<ul>\n");
			while ($rowItem = mysql_fetch_assoc($rowItemCursor))
			{
				print("\t\t\t<li>\n");
				print("\t\t\t\t<a href='");
				$target =  buildTargetUrl($rowItem);
				print($target);
				print("''> \n");
				print("\t\t\t\t\t" . $rowItem['prompt']);
				/********
				Display Marker after any menu item that has been
				updated in the last RECENT_PAGE_UPDATE_DAYS which is defined in globals.php
				*************/
				if ( isItemRecentlyUpdated($rowItem['lastupdate']) )
				{
					print(" ");
					recentUpdateFlag();
				}
				print("\n");
				print("\t\t\t\t</a>\n");
				print("\t\t\t</li>\n");
			}
			print("\t\t</ul>\n");
		}
		mysql_free_result($rowItemCursor);

		print("</li>\n");

	}

	print("</ul>\n"); // close the ul tag for the menu
	mysql_free_result($res);

}


// End genlib.php code
?>
