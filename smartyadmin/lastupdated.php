<?php

/******************************
	Start a session. This must be the very first thing done on the page.

	The session Classes constructor also opens the database.
**********************************/
require_once("../session.php");
$oSession = new session();

require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";
require_once "../genlib.php";



require('smartysetup.php');

$smarty = new Smarty();

if (empty($_POST))
{

  $luTime = getLastUpdated(); // leastupdated as unix datetime
	$luDate = $luTime;
	$smarty->assign('luDate', $luDate);
	$smarty->assign('luTime', $luTime);

	$smarty->display('lastupdated.tpl');

}
else
{

	if (isset($_POST['touchLastUpdated']))
	{
		$updateStamp = touchUpdateRec();

		$message = "Website last updated set to " . $updateStamp;
		$smarty->assign('message',$message);
		$smarty->display('success.tpl');
	}
	else
	{
		$luDate = makeTimeStamp($_POST['luDateYear'], $_POST['luDateMonth'], $_POST['luDateDay'],
														$_POST['Time_Hour'], $_POST['Time_Minute']);

		$luTime = $luDate;

		$_POST['luDate'] = $luDate;
		$_POST['luTime'] = $luTime;

		$updateStamp = updateRec($luTime);  // update lastUpdated using unix timestamp returned from template
		$message = "Website last updated timestamp set to " . $updateStamp;
		$smarty->assign('message',$message);
		$smarty->display('success.tpl');
	}
}



return;
// End Control Code
// =================================

// =================================

function touchUpdateRec($unixTimestamp=null)   
{
	if (isset($unixTimestamp))
	{
		$sqlDate = strftime("%d/%m/%Y",$unixTimestamp);
		$sqlTime = strftime("%H:%M",$unixTimestamp);
	}
	else
	{
		$oDate = new DateTime();
		$sqlDate = $oDate->format("d/m/Y");
		$sqlTime = $oDate->format("H:i");
	}

	if ( ! setLastupdatedDate($sqlDate, $sqlTime) )
	{
    die('Update failed: ' . mysql_error());
	}


	return $sqlTime . " " . $sqlDate;
}

// ==================================


// =================================

function updateRec($unixTimestamp=null)   
{
	if (isset($unixTimestamp))
	{
		$sqlDate = strftime("%d/%m/%Y",$unixTimestamp);
		$sqlTime = strftime("%H:%M",$unixTimestamp);
	}

	if ( ! setLastupdatedDate($sqlDate, $sqlTime) )
	{
    die('Update failed: ' . mysql_error());
	}

	return $sqlTime . " " . $sqlDate;
}

// ==================================


// =============================
// returns last updated as unix datetime
function getLastUpdated()   
{
	
	$qry = "SELECT lastUpdated, UNIX_TIMESTAMP(lastUpdated) AS unixDateTime
		      FROM sysconf 
		      LIMIT 1";	
	

	$row = mysql_query($qry);
	if (!$row) 
	{
		die("Query failed: [$qry] " . mysql_error());		
	}
		
	$cols = mysql_fetch_assoc($row);        

	$timeStamp = $cols['unixDateTime'];

	mysql_free_result($row);

	return $timeStamp;
}
// ================================

// ================================
function makeMySqlDate($year='', $month='', $day='')
{
	return $year . '-' . $month . '-' . $day;
}
// ================================


// ================================
// Create a Unix timestamp
function makeTimeStamp($year='', $month='', $day='', $hour='', $minute='', $second='')
{
   if(empty($year)) {
       $year = strftime('%Y');
   }
   if(empty($month)) {
       $month = strftime('%m');
   }
   if(empty($day)) {
       $day = strftime('%d');
   }
   if(empty($hour)) {
       $hour = strftime('%H');
   }
   if(empty($minute)) {
       $minute = strftime('%M');
   }
   if(empty($second)) {
       $second = strftime('%S');
   }

   return mktime($hour, $minute, $second, $month, $day, $year);
}
// ================================



?>   






