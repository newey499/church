<?php
/*************************************

 CDN 04/12/2008

	buildnewrssfeed.php

 Build RSS feed.

**************************************/

require_once("dbconnectparms.php");
require_once("globals.php");
require_once("genlib.php");
require_once("mysql.php");
require_once("dumpquerytotable.php");
require_once("class.cdnmail.php");
require_once("mysqldatetime.php");

define("PATH_TO_RSS_FEED_FILE", "rss.xml");

/*********
 Works out which Date/Time format to include in the RSS feed for each event
**************/
function formatEventDate($eventTime, $eventDateOnly, $eventDateTime)
{
	// A time of FC_HIDE_TIME is a "magic time" that means ignore the time and
	// use the date only
	if ($eventTime == FC_HIDE_TIME)
	{ print("<h4>" . FC_HIDE_TIME . " TRAPPED Returning [" . $eventDateOnly . "]</h4>\n");
		return $eventDateOnly;
	}
	else
	{
		return $eventDateTime;
	}
}



function buildNewRssFeed(&$msg, $silent = FALSE)
{
	$msg = $msg . "Creating new RSS Feed<br />\n";


	///////// Getting the data from Mysql table for first list box//////////
	mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)
		or die('Could not select database');


	$query = "SELECT id, eventdate, " .
					 "concat(lower(date_format(eventdate,'%l:%i%p')) , date_format(eventdate,' %a %D %b')) AS fmtdate, " .
					 "date_format(eventdate,'%a %D %b') AS fmtdateonly, " .
           "date_format(eventdate,'%H:%i') AS eventtime, " .
           "date_format(eventdate,'%Y-%m-%d %H:%i:00') AS pubdate, " .
					 "eventname, eventdesc " .
	         "FROM forthcomingevents " .
					 "ORDER BY eventdate ";

	$result = mysql_query($query);

	if (! $result)
	{
		print("<p>\n");
		print("Query Error [" . $result . "] <br /> \n");
		print(mysql_errno() . ": " . mysql_error());
		print("</p>\n");
	}


	$body  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$body .= "<rss version=\"2.0\">\n";
	$body .= "<channel>\n";
	$body .= "<title>Christ Church Events</title>\n";
	$body .= "<link>http://" . WEBSITE_DOMAIN  . "</link>\n";
	$body .= "<description>Forthcoming Events at Christ Church Lye West Midlands</description>\n";
	$body .= "<copyright>(c) 2008, Christ Church Parish Council All rights reserved.</copyright>\n";

	print("Forthcoming Event Rows Found for RSS Feed [" . mysql_num_rows($result)  . "] <br /> \n");

	$url = "http://" . WEBSITE_DOMAIN . "/index.php?displaypage=dispforthevent.php";
	$pubDate = date("Y-m-d h:i:00", time());
	print("Publication Date [" . $pubDate . "] <br /> \n");


	/****************************
		Process Forthcoming Events
	********************************/
	while($nt = mysql_fetch_array($result))
	{
		$articleURL = $url . "#articleid" . $nt['id'];
		print("ROW [" . $nt['id'] . "] [" . $nt['eventname'] . "] <br /> \n");
		print("URL [" . $articleURL . "] <br /> \n");
		$eventDateTime = formatEventDate($nt['eventtime'], $nt['fmtdateonly'], $nt['fmtdate']);
		print("Event Date [" . stripslashes($nt['eventdate']) . "] " .
          " mapped to [" . $eventDateTime . "]<br /> \n");
		$body .= "<item>" .
						 "<title>" . stripslashes($eventDateTime) . " " . stripslashes($nt['eventname']) . "</title>" .
						 "<link>" . stripslashes($articleURL)  . "</link>" .
						 "<description>" .  stripslashes($nt['eventname']) . "</description>" .
						 // "<pubDate>" . stripslashes($nt[eventdate]) . "</pubDate>" .
						 "<pubDate>" . $nt['pubdate'] . "</pubDate>" .
						 "</item>" . "\n";

	}

	mysql_free_result($result);
	unset($result);

	/****************************
		Process recently updated menu items
	********************************/
	$query = "SELECT id, itemtype, itemgroup, itemorder, isvisible, " .
           "       prompt, target, lastupdate, " .
					 "concat(lower(date_format(lastupdate,'%l:%i%p')) , date_format(lastupdate,' %a %D %b')) AS fmtdate " .
	         "FROM menuitems " .
					 "WHERE itemtype = 'MENU_ITEM' AND isvisible = 'YES' " .
					 "ORDER BY lower(prompt) ";

	$result = mysql_query($query);

	if (! $result)
	{
		print("<p>\n");
		print("Query Error [" . $result . "] <br /> \n");
		print(mysql_errno() . ": " . mysql_error());
		print("</p>\n");
	}

	print("<h4>New Menu Items</h4><br />\n");
	$url = "http://" . WEBSITE_DOMAIN . "/index.php";
	while($nt = mysql_fetch_array($result))
	{
		if ( isItemRecentlyUpdated($nt['lastupdate']) )
		{
			print($nt['prompt'] . "<br />\n");

			$articleURL = $url;

			$body .= "<item>" .
							 "<title>" . "New - " . stripslashes($nt['prompt']) . "</title>" .
							 "<link>" . stripslashes($articleURL)  . "</link>" .
							 "<description>" .  stripslashes($nt['prompt']) . "</description>" .
							 // "<pubDate>" . stripslashes($nt[eventdate]) . "</pubDate>" .
							 "<pubDate>" . $pubDate . "</pubDate>" .
							 "</item>" . "\n";

		}
	}

	mysql_free_result($result);
	unset($result);

	$body .="</channel>" . "\n" .
					"</rss>\n";

	print("<p> \n");
	print($body);
	print("<p> \n");

	$filenum=fopen(PATH_TO_RSS_FEED_FILE,"w");
	fwrite($filenum,$body);
	fclose($filenum);

	return;
}


?>
