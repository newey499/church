<?php
require_once('globals.php');
require_once('genlib.php');
require_once('mysql.php');

/*******************************************
Display Can you Help Entries
*********************************************/
  print("<br />");

	print("<h2>Can You Help</h2>");

?>

<p>

  <h4>Become involved with Church and Community Activities.</h4>

<p>
  Church Office 01384 - 894948
  <br />

<form action="index.php?displaypage=mailform.php" method="post">
<input type="hidden" name="emailrecipient" 
value="office@christchurchlye.org.uk">
<input type="submit" value="Email Church Office">
</form>

</p>

<br />

</p>

<?php  

	/* Connect to a MySQL server */ 
	$mysqlj = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

	/* Send a query to the server 
	* mysqlj::query returns an object of class mysqlj_result */ 
	$result = mysql_query(
						// "SELECT DATE_FORMAT(stamp,'%l:%i%p %W %e/%c/%Y') AS blogdate,headline,blogentry " . 
						"SELECT DATE_FORMAT(stamp,'%W %e/%c/%Y') AS blogdate,headline,blogentry " . 
						"FROM canyouhelp " . 
						"ORDER BY stamp DESC"); 

	/* Fetch the results of the query */ 
	print("<p>\n");
	while( $row = mysql_fetch_assoc($result) ){ 
		
		echo "<div class=\"forthcomingEventTitle\" >";
	  printf("<b>%s</b>\n", stripslashes($row['headline']));
	  echo "</div>\n";
	  
		echo "<div class=\"forthcomingEventDescription\" >";	  
		if (isset($row['blogentry'])) 
		{
		  printf("%s\n", stripslashes($row['blogentry'])); 
		}
	  echo "</div>\n";
	
	} 
	print("</p>\n");	

	/* Destroy the result set and free the memory used */ 
	mysql_free_result($result); 


?>

