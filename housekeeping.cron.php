<?php
/***********************************

CDN 14/07/07

housekeeping.cron.php

Housekeeping routines run nightly as a cron job

04/12/2008 CDN  		Add Code to build RSS Feed
***************************************/


require_once("dbconnectparms.php");
require_once("globals.php");
require_once("genlib.php");
require_once("mysql.php");
require_once("dumpquerytotable.php");
require_once("class.phpmailer.php");
require_once("class.cdnmail.php");
require_once("mysqldatetime.php");
require_once("buildnewrssfeed.php"); // Required for building RSS Feeds
																		 // contains buildNewRssFeed function

$msg = "Housekeeping cron job testcron.php <br />";

$msg = $msg . "<b> Database: " . MYSQL_DATABASE . " </b> <br />";

$msg = $msg . "Start <br />";



/* Connect to a MySQL server */ 
if ( ! $dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD) )
{
	$msg = $msg . 'Could not connect: ' . mysql_error() . "<br />";
	sendEmail($msg);
	die('Could not connect: ' . mysql_error());
}

if ( ! mysql_select_db(MYSQL_DATABASE) )
{
	$msg = $msg . 'Could not select database <br />';
	sendEmail($msg);
	die('Could not select database');	
}

/****
 Carry out housekeeping jobs
************/
deleteExpiredForthcomingEvents($msg);
// CDN 04/12/2008
buildNewRssFeed($msg, FALSE);

$msg = $msg . "End <br />";

sendEmail($msg);

print $msg;

return;

// ==============================================================
function deleteExpiredForthcomingEvents(&$msg)
{
	$msg = $msg . "deleteExpiredForthcomingEvents() <br /> ";
	$qry = "delete from forthcomingevents where eventdate < CURRENT_DATE";
	
	if ( ! mysql_query($qry)	) 
	{
		$msg = $msg . 'Invalid query: ' . mysql_error() . "<br />"; 
		sendEmail($msg);
    die('Invalid query: ' . mysql_error());
	}
 
	$msg = $msg . "deleteExpiredForthcomingEvents() Completed OK <br />";
	return;
}

// ==============================================================

function sendEmail($msg)
{
	$oMail = new PHPMailer();
		
	$oMail->IsSendmail();																		// Use sendmail as transport
	$oMail->IsHTML(True);                               		// set email format to Plain Text
	$oMail->Sender = "churchCron@christchurchlye.org.uk";   // Return path of message - where a reply to this email
																		          						// will actually be sent
	$oMail->From     = "churchCron@christchurchlye.org.uk"; // email address of sender that appears in email client
	$oMail->FromName = "Church Cron Job"; 									// Name of person sending email
	$oMail->AddAddress("webmaster@ChristChurchLye.org.uk");	// Who the mail goes to
	$oMail->Subject  = "Church Housekeeping Cron Job: ";
	$oMail->Body     = $msg;
				
	/**************
	Bit pointless to whinge to stdout or stderr when running as a cron job
	if( $oMail->Send() )
	{
		print "Mail OK\n";
	}
	else
	{
		print "Mail Failed\n";
	}
	***********************/
	$oMail->Send();

	return;
}


?>
