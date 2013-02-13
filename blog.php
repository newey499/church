<?php
require_once('globals.php');
include_once('genlib.php');
require_once('mysql.php');

/*******************************************
Display Blog Entries
*********************************************/
	print("<h4>Blog's Blog</h4>");

	/* Connect to a MySQL server */ 
	$mysqlj = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

	/* Send a query to the server 
	* mysqlj::query returns an object of class mysqlj_result */ 
	$result = mysql_query(
						"SELECT DATE_FORMAT(stamp,'%l:%i%p %W %e/%c/%Y') AS blogdate,headline,blogentry " . 
						"FROM blog " . 
						"ORDER BY stamp DESC"); 

	/* Fetch the results of the query */ 
	while( $row = mysql_fetch_assoc($result) ){ 
		//print "<hr>\n";		
		echo "<p>\n";
		
		echo "<div class=\"forthcomingEventTitle\" >";
	  printf("<b>%s</b><br>\n", stripslashes($row['headline']));
	  echo "</div>\n";
	  
		echo "<div class=\"forthcomingEventDate\" >";
		print "<br>\n";
	  printf("%s\n", stripslashes($row['blogdate']));
		print "<br />\n";
		print "<br />\n";			   
	  echo "</div>\n";
	  	
		echo "<div class=\"forthcomingEventDescription\" >";	  
		if (isset($row['blogentry'])) 
		{
		  printf("%s\n", stripslashes($row['blogentry'])); 
		}
	  echo "</div>\n";
	  		
		//print "<br>\n";
		print("</p>\n");		
	} 

	/* Destroy the result set and free the memory used */ 
	mysql_free_result($result); 

	/* Close the connection and free the memory used*/ 
	mysql_close($mysqlj); 

?>

