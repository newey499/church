<?php
require_once('globals.php');
require_once('genlib.php');
require_once('mysql.php');

/*******************************************
Display regular events in the central panel
*********************************************/
	print("<h1>Regular Events</h1>");

	/* Connect to a MySQL server */ 
	$mysqlj = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

	/* Send a query to the server 
	* mysqlj::query returns an object of class mysqlj_result */ 
	$result = mysql_query('SELECT frequency, eventdesc, eventname FROM regularevents ORDER BY eventname ASC'); 

	/* Fetch the results of the query */ 
	while( $row = mysql_fetch_assoc($result) ){ 
		print("<p>\n");
		echo "<div style=\"background:#ddd;\">";		
	  printf("<b>%s</b><br>\n", $row['eventname']); 
	  printf("<b>%s</b><br>\n", $row['frequency']); 	
		echo "</div>\n";		  
		if (isset($row['eventdesc'])) {
		  printf("<p>%s<br></p>\n", $row['eventdesc']); 
		}
		print "<br>\n";
		print("</p>\n");		
	} 


	/* Destroy the result set and free the memory used */ 
	mysql_free_result($result); 

	/* Close the connection and free the memory used*/ 
	mysql_close($mysqlj); 

?>

