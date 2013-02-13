<?php

	require_once("dbconnectparms.php");
	require_once("globals.php");
	require_once("genlib.php");
	require_once("mysql.php");
	require_once("dumpquerytotable.php");
	require_once("class.cdnmail.php");
	require_once("mysqldatetime.php");

  // How to force a redirect
  //Header("Status: 302");
  //Header("Location: http://www.google.co.uk"); 

	// No Caching
	//Header("Cache-Control: no-cache");
	//Header("Cache-Control: no-store");
	//Header("Cache-Control: must-revalidate");


	/******************************
		Start a session. This must be the very first thing done on the page.
    The session objects constructor also connects to the database.
	**********************************/
	require_once("session.php");
	$oSession = new session();

	print('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">');

	$_SESSION['siteMainPage'] = "mobile.php";

?>
<html>

<head>

<?php

print("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" >" . "\n");
print('<meta http-equiv="Content-Style-Type" content="text/css" >' . "\n");

?>

  <title>Christ Church Lye &amp; Stambermill</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" >	

  <meta name="keywords" content="christ, church, lye, cofe, evangelical, england" >	

  <!-- RSS Feed -->
  <link rel="alternate" type="application/rss+xml" 
   href="http://www.christchurchlye.org.uk/rss.xml" title="Christ Church Events" >
  <!-- End RSS Feed -->


  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" >
  <link rel="stylesheet" type="text/css" href="css/church.css" >		
  <link rel="stylesheet" type="text/css" href="css/slideshow.css" >	
  <link rel="stylesheet" type="text/css" href="css/tooltip.css" >	
	<!-- CSS Specific to mobile site -->
  <link rel="stylesheet" type="text/css" href="css/mobile.css" >	
	<!== CSS for printout -->
  <link rel="stylesheet" type="text/css" media="print" href="css/print.css" >	
  <!-- End CSS Includes -->

  <!-- Javascript Includes -->
  <!--   <script type="text/javascript" src="jscript/chromejs/chrome.js" ></script> -->
  <!--   <script type="text/javascript" src="jscript/javaxhtml1-0.js" ></script> -->
  <script type="text/javascript" src="jscript/jquery-1.2.6.min.js" ></script>
  <script type="text/javascript" src="jscript/genlib.js" ></script>
  <script type="text/javascript" src="jscript/ajax.js" ></script>  
  <script type="text/javascript" src="jscript/church.js" ></script>  
  <script type="text/javascript" src="jscript/slideshow.js" ></script>
  <script type="text/javascript" src="jscript/tooltip.js" ></script>
	<!-- <script type="text/javascript" src="jscript/changestyle.js"></script> -->
  <!-- End Javascript Includes -->

	<script type="text/javascript">

		$(document).ready(function(){

			<?php
				$oMenuTitle = new MenuTitles();
				$oMenuTitle->writeJScriptForMenus();
			?>

	  });
    
  </script>



</head>

<body> 

<?php
$oSysconf = new Sysconf();

require_once("mobiletopbanner.php");
?>

<p>

<?php

	if (! isset($_GET['displaypage']))
	{
		printMainSiteLink();

		printReturnLink();
		mobileBuildLinksFromTable('',array('MENU_ITEM_DROP'));

		print("<br />\n");
		//print("<br />\n");

		if ($oSysconf->isPageMenuEnabled() )
		{
			mobileBuildLinksFromTable('LEFT');
			print("<br />\n");
			//print("<br />\n");
		}

		if ($oSysconf->isPageMenuEnabled() )
		{
			mobileBuildLinksFromTable('RIGHT');
		}
		printReturnLink();
		printMainSiteLink();
	}
	else
	{
		printMainSiteLink();
		printReturnLink();
		displayMobilePage();
		printReturnLink();
		printMainSiteLink();
	}
?>

</p>

 
<?php

?>


</p>

</body> 
</html>
<?php
// =========================================================================================
// End of HTML
// =========================================================================================


// =========================================================================================
// Mobile specific php 
//
// Date			Programmer	Description
// 08/11/2010	CDN			Created
// =========================================================================================
function openDatabase()
{
	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	
}


/*******************
function to build menu links for left and right columns of main page
valid values for $column are "LEFT" and "RIGHT"
**************************************************/
function mobileBuildLinksFromTable($column, $menuTypes = array('MENU_ITEM','MENU_ITEM_PAGE')) 
{

	$firstGroup = true;
	$oMenuTitle = new MenuTitles();
	
	// Only force a newline ("<br />") if building a left or right column menu
	//$forceNewLine = ($column == 'LEFT' || $column == 'RIGHT');
	// What gets appended to a menu item link now depends upon what type of
	// menu item it is
	$forceNewLine = TRUE;

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

	
		if ($crntGrp <> $row['itemgroup']) 
		{
			/***********
			 CDN 10/11/10
			If there is a previous menu group close its div
			*************************/
			if ($crntGrp != "")
			{
				closeMenuGroupDiv($crntGrp);
			}


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
				echo "<br />";
			}
			$crntGrp = $row['itemgroup'];

			/***********
			 CDN 10/11/10
			Enclose Menu header and menu items in a div - also enclose menu items in another div
			to let jscript hide/show menu items on a mouse over
			*************************/
			createMenuGroupDiv($crntGrp);
			$oMenuTitle->buildMenuTitles($crntGrp);
			createMenuItemGroupDiv($crntGrp);
		}
		
		// If we ain't got a prompt refuse to play
		if (trim($row['prompt']) == '') 
		{		
			die("Invalid Menu record configuration (Empty Prompt) menus.rowid=" . $row['rowid']);			
		}

		// If this the last menu item then don't force a new line
		if ($lastMenuItem == $row['prompt'])
		{
			$forceNewLine = FALSE;
			$isLastItem = TRUE;
		}
		else
		{
			$isLastItem = FALSE;
		}


		// if a link to a target file is given then that takes priority
		if (trim($row['target']) <> '') 
		{
			// build link based upon assumption that external links will commence with 
			// "http:"
			if (strToUpper(substr($row['target'],0,5)) == 'HTTP:') {
				mobileBuildmenuitem($row['target'],
                				    stripslashes($row['prompt']),
                				    $row['lastupdate'],
                				    $forceNewLine,
														$row['itemtype']);					
			}
			else 
			{
				mobileBuildmenuitem('mobile.php?displaypage=' .
                				    $row['target'],
                				    stripslashes($row['prompt']),
				                    $row['lastupdate'],
				                    $forceNewLine,
														$row['itemtype']);	

										
			}
		}
		else 
		{
			// no link to file - see if there's any text in the blob (text) field
			if (trim($row['content']) <> '') 
			{

				// CDN 02/12/08 - $row['id'] is the primary get field onthe menus table
				//                rowid is the $_GET string that mobile.php expects the 
				//                primary key for internal page content to be in
				$args = cdn_http_build_query(array('displaypage' => INTERNAL_CONTENT_FLAG,
																			 'rowid' => $row['id']	
																			)
																);
																
				mobileBuildmenuitem('mobile.php?' . $args .
                				    $row['target'],
                				    stripslashes($row['prompt']),
                				    $row['lastupdate'],
                				    $forceNewLine,
														$row['itemtype']);
																

			}
			else 
			{
				die("Invalid Menu record configuration menus.rowid=" . $row['rowid'] . " content is empty");
			}
		}

	}

	/***********
	 CDN 10/11/10
	Last item of collection of menu groups processed (TOP, LEFT or RIGHT) so close div
	of last menu group
	*************************/
	if ($crntGrp <> $row['itemgroup']) 
	{
		/***********
		 CDN 10/11/10
		If there is a previous menu group close its div
		*************************/
		if ($crntGrp != "")
		{
			closeMenuGroupDiv($crntGrp);
		}
	}

		
	mysql_free_result($res);

}
// =========================================================================================


// =========================================================================================
// Creates a link to $target with prompt = $prompt
function mobileBuildmenuitem($target,$prompt,$lastupdate = "", $lineBreak = FALSE, $menuItemType='MENU_ITEM') 
{

// page load
print "<a href=\"$target\"  class=\"menuitem\" "; 


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
	// CDN 8/11/10 - For mobile always print a line break
	/**************
	if ($menuItemType == 'MENU_ITEM_DROP')
	{
		print " | \n";
	}
	else
	{
		print "<br />\n";
	}
	*******************/
	print "<br />\n";
}

//print "</p>\n";

}
// =========================================================================================

// =========================================================================================
function displayMobilePage()
{

print("<div id='ajaxcentercontent'>\n");

/*******************
If the displaypage requested indicates internal content then that content is pulled from the database.

If a relative path to a file is given then that file is included.

If no displaypage is given then the home page is loaded.
*****************************/
if (isset($_GET['displaypage'])) 
{
	if ($_GET['displaypage'] == INTERNAL_CONTENT_FLAG) 
	{
		echo getInternalContent($_GET['rowid']);
	}
	else 
	{
		// The link that bought us here passes the name of the required source
		if (file_exists($_GET['displaypage'])) 
		{
			include_once($_GET['displaypage']);
			/***************
			print("<script type='text/javascript'> \n");
			print("loadXMLDoc('" . $_GET['displaypage'] . "', 'ajaxcentercontent' ); \n");
			print("</script> \n");			
			******************/
			//loadXMLDoc($_GET['displaypage'], 'ajaxcentercontent');
		}
		else 
		{
			// Whoops something is screwed up
			print("<br /><h4>Requested file - [" . $_GET['displaypage'] . "] does not exist</h4>");
		}
	}
}
else 
{
	// displaypage not passed so default to the file included below
	include_once('home.php');	

}

print("</div>\n");

}
// =========================================================================================




// =========================================================================================
function printReturnLink()
{

	print("<p>\n");
	print("Return to ");

	/****************
	if (  $_SERVER['HTTP_REFERER'] != "http://christchurchlye.org.uk/mobile.php")
	{
		print("<a href=\"" . $_SERVER['HTTP_REFERER'] . "\"> Mobile Menu</a>\n");
	}
	else
	{
		print("Ok arrived here from a direct URL");
		print("<a href=\"" . WEBSITE_DOMAIN . "\"> Main Website</a>\n");
	}
	*********************/

	print("<a href=\"http://" . WEBSITE_DOMAIN . "/mobile.php\"> Mobile Website</a>\n");


	print("</p>\n");

}
// =========================================================================================
function printMainSiteLink()
{
	$url = "http://" . WEBSITE_DOMAIN;

	print("<a href=\"" . $url . "\">Main Website</a>\n");
	
}
// =========================================================================================


// =========================================================================================
/***********
 CDN 10/11/10
close divs of menu group and menu group items
*************************/
function closeMenuGroupDiv($crntGrp)
{
	print("\n</div> <!-- end div id=\"menu_group_items_" . $crntGrp . "-->\n");
	print("</div> <!-- end div id=\"menu_group_" . $crntGrp . "-->\n\n");
}
// =========================================================================================


// =========================================================================================
/***********
 CDN 10/11/10
create divs of menu group and menu group items
*************************/
function createMenuGroupDiv($crntGrp)
{
	print("\n\n<div id=\"menu_group_" . $crntGrp . "\"> <!-- start div id=\"menu_group_" .
				$crntGrp . "-->\n");

}
// =========================================================================================

// =========================================================================================
/***********
 CDN 10/11/10
create divs of menu group and menu group items
*************************/
function createMenuItemGroupDiv($crntGrp)
{
	print("<div id=\"menu_group_items_" . $crntGrp . "\"> <!-- start div menu_group_items_" . 
        $crntGrp . "-->\n\n");
}
// =========================================================================================

?>


