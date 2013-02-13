<?php

require_once('globals.php');
include_once('genlib.php');
require_once('mysql.php');
require_once("buildform.php");	


function browseGuestbook() {

	$args = http_build_query(array('displaypage' => 'guestbook.php',
											 'opcode' => INSERT_REC));
 	echo "<p><a href=\"index.php?$args\">Add a Guestbook entry</a></p>\n";	




	/* Connect to a MySQL server */ 
	$mysqlj = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


	/* Send a query to the server 
	* mysqlj::query returns an object of class mysqlj_result */ 
	$result = mysql_query("SELECT DATE_FORMAT(stamp,'%l:%i%p %W %e/%c/%Y') AS gbookdate, id, email, mesg " . 
								 " FROM guestbook ORDER BY stamp DESC"); 

	print("<div style=\"background:#eee; margin-left:20px\">\n");
	/* Fetch the results of the query */ 
	while( $row = mysql_fetch_assoc($result) ){ 
		//print "<hr>\n";		
		print("<p>\n");
		echo "<div style=\"background:#ddd;\">";
		printf("<b>%s</b><br>\n", stripslashes($row['gbookdate'])); 
    printf("<b>%s</b><br>\n", stripslashes($row['email']));
    echo "</div>\n"; 	
		if (isset($row['mesg'])) {
		  printf("<p>%s<br></p>\n", stripslashes($row['mesg'])); 
		}
		print "<br>\n";
		print("</p>\n");		
	} 
   print("</div>\n");

	/* Destroy the result set and free the memory used */ 
	mysql_free_result($result); 

	/* Close the connection and free the memory used*/ 
	mysql_close($mysqlj);

}


function writeForm() {

	$oMysql = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	  or die('Could not connect: ' . mysql_error());		

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	 

	$stmnt = sprintf("INSERT INTO guestbook (email,mesg) VALUES ('%s', '%s')",
							mysql_real_escape_string($_POST['email']),
							mysql_real_escape_string($_POST['mesg'])		
							);

	if (! mysql_query($stmnt)) {
		echo "<p>Guestbook Insert Failed. Please try again later.</p>\n";
	}
	else {
		echo "<p>Guestbook Entry written OK.</p>\n";
	}
	$args = http_build_query(array('displaypage' => 'guestbook.php'));
 	echo "<p><a href=\"index.php?$args\">Guestbook</a></p>\n";		

}

function editGuestbook() {

	$qry = 'SELECT id,email,mesg FROM guestbook';
	$pkey = array('id' => NULL);
	$oForm = new buildForm(INSERT_REC,$qry,$pkey,$flds= NULL);

	$args = http_build_query(array('displaypage' => 'guestbook.php',
											 'opcode' => INSERT_REC,
											 'sub_opcode' => FORM_VALIDATE));
	$oForm->submitTarget = "index.php?$args";

	$oForm->addField('email','Email',$_POST['email'],"Optional");
	$oForm->addField('mesg','Message',$_POST['mesg'],"Required");	

	$args = http_build_query(array('displaypage' => 'guestbook.php'));
	$oForm->addLink("index.php?$args",'Guestbook');


	if ($_GET['sub_opcode'] == FORM_VALIDATE) {
		// Perform any required data validation
		$isValid = True;
		if (trim($_POST['mesg']) == '') {
			print("Comment required");
			$oForm->setValidationMessage('mesg','Message may not be empty');			
			$isValid = False;		
		}
		// If the form is valid then insert the record
		// otherwise redisplay the form
		if ($isValid) {
			writeForm();			
		}
		else {
			// Just display form
			$oForm->exec();
		}
	}		
	else {
		// Just display form
		$oForm->exec();
	}
	

}




/*******************************************
Guestbook Entry Point
*******************************************/
	print("<h4>Guestbook</h4>");


	switch ($_GET['opcode']) {
		case INSERT_REC:
			editGuestbook();
			break;

		default:
			browseGuestbook();	
			break;
	}


?>

