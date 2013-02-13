<?php
	/************************************
	This file is included in index.php and provides the centre content column
	On Entry we have a database handle and the required database is selected.
	*****************************************/
/********	
define("LOGIN_MEMBER",								1);							// Login an existing member
define("LOGIN_CREATE",								2);							// Create a new member
define("LOGIN_VALIDATE",							3);							// Validate a Login attempt
define("LOGOUT_MEMBER",								4);							// Logout a member who is already logged in
define("LOGIN_MEMBER_EDIT",				    5);							// Edit a logged in members details
define("LOGIN_MEMBER_UPDATE",					6);							// Update a logged in members details
define("LOGIN_ACCEPT_REGISTRATION", 	7);							// Email Argument when confirming user registration
define("LOGIN_DECLINE_REGISTRATION", 	8);							// Email Argument when declining user registration
*************/	
	
	echo "<h1>Newsletter</h1>\n";


	displayNewsletters();
	
		
	displayNewsletterForm();	
?>
		
	



<?php

function displayNewsletters() {

global $dbHandle;	
$oTable = new dumpqrytotable($dbHandle, 'SELECT * FROM newsletters');

$oTable->addColumn('publicationdate', 'Published');
$oTable->addColumn('title', 'Title');

$url = "index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_VIEW . "\"";
$oTable->addColumnLink('Newsletter', $url, 'View');


$oTable->addLink("index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_SUBSCRIBE . "\"",'Subscribe');
$oTable->addLink("index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_UNSUBSCRIBE . "\"",'Unsubscribe');

$oTable->addPrimaryKeyColumn('id');

$oTable->exec();

}



function displayNewsletterForm() {

print "<p>\n";
print "<form action=\"index.php?displaypage=newsletter.php\" method=\"post\">\n";

print "<p>\n";
print "Email&nbsp;";
print "<input type=\"text\" name=\"email\" size=\"50\" maxlength=\"50\" />\n";
print "</p>\n";

print "<p>\n";
print "<select name=\"cbxOpCode\" size=\"3\">\n";
print "	<option value=\"" . NEWSLETTER_SUBSCRIBE . "\" SELECTED>Subscribe\n";
print "	<option value=\"" . NEWSLETTER_UNSUBSCRIBE . "\" >Unsubscribe\n";
print "	<option value=\"" . NEWSLETTER_DOWNLOAD . "\" >Download Newsletter\n";
print "</select>\n";
print "</p>\n";

print "<p>\n";
print "<button type=\"submit\">Submit</button>\n";
print "&nbsp;&nbsp;";
print "<button type=\"reset\">Reset</button>\n";
print "</p>\n";

print "</form>\n";	
print "</p>\n";

print "<p>\n";
print "<a href=\"index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_SUBSCRIBE . "\" >Subscribe</a>\n";
print "</p>\n";

print "<p>\n";
print "<a href=\"index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_UNSUBSCRIBE . "\" >Unsubscribe</a>\n";
print "</p>\n";

print "<p>\n";
print "<a href=\"index.php?displaypage=newsletter.php&opcode=" . NEWSLETTER_DOWNLOAD . "\" >Download</a> Newsletter\n";
print "</p>\n";

}
?>
