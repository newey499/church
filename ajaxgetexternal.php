<?php

/*****************************************

ajaxgetexternal.php

16/03/2010	CDN					Created




*******************************************/



require_once("dbconnectparms.php");	// Database connection - user id, password etc.
require_once("mysql.php");
require_once('genlib.php');


$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	


print("<br /> <h2>STILL UNDER DEVELOPMENT id=[" . $_GET['id'] . "] ajaxgetexternal.php </h2> \n");

$qry = sprintf("SELECT id, target, content FROM menus WHERE id = %d",
								$_GET['id']);

print("<h4>" . $qry . "</h4\n");
if (! $res = mysql_query($qry))
{
	print("<br /> <h4>mysql_qry FAILED [" . $_GET['id'] . "] does not exist</h4> \n");
}

if (! $row = mysql_fetch_assoc($res))
{
	print("<br /> <h4>rowid [" . $_GET['id'] . "] does not exist</h4> \n");
}
else
{
	print("<br /> <h4>ajaxgetexternal.php   menu row id [" . $_GET['id']  . "] target [" . $row['target'] . "] <br />\n");	
}

mysql_free_result($res);


?>
