<?php

/*****************************************

ajaxgetinternal.php

16/03/2010	CDN					Created




*******************************************/



require_once("dbconnectparms.php");	// Database connection - user id, password etc.
require_once("mysql.php");
require_once('genlib.php');


$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	


//print("<h2> id=[" . $_GET['id'] . "] </h2> \n");

print(getInternalContent($_GET['id']));

?>



