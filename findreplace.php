<?php

require_once("dbconnectparms.php");
require_once("globals.php");
require_once("genlib.php");
require_once("mysql.php");
require_once("dumpquerytotable.php");
require_once("class.cdnmail.php");
require_once("mysqldatetime.php");




/* Connect to a MySQL server */ 
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	


print("Database Opened<br />\n");
print("<p>========================</p>\n");

$result = mysql_query("SELECT * FROM menus"); 

$findStr = 'keithstroyde@hotmail.com';
$replStr = 'keithstroyde@hotmail.co.uk';

$content = "";

/* Fetch the results of the query */ 
while( $row = mysql_fetch_assoc($result) )
{ 
	$content = $row['content'];
	$count = 0;
	if (! (stripos( $content, $findStr) === false) )
	{
	  printf("%d   %s\n", $row['id'], $row['prompt']); 
		print "<br>\n";
		$content = str_ireplace( $findStr, $replStr, $content, &$count);
		$update = sprintf("UPDATE menus SET content = '%s' WHERE id = %d", $content, $row['id']);
		mysql_query($update); 
		printf("Updated %s Changes made %d", $row['id'], $count);
		print "<br>\n";
	}
} 

/* Destroy the result set and free the memory used */ 
mysql_free_result($result); 

/* Close the connection and free the memory used*/ 
mysql_close($dbHandle); 


print("<p>========================</p>\n");
print("Done<br />\n");


?>
