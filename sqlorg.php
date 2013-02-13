<?php
	/******************************
	  Need to include classes that are passed in $_SESSION
	  before instantiating the session
	  ********************************************/
	require_once("../buildform.php");	
	
	/******************************
		Start a session. This must be the very first thing done on the page
		that writes to the outgoing page
	**********************************/
	require_once("../session.php");
	$oSession = new session();
?>	
<?php
	require_once "../globals.php";
	require_once("../mysql.php");

	$_POST['opcode'] = strtoupper($_POST['opcode']);

	switch ($_POST['opcode']) {
		case INSERT_REC:		
			$pageTitle="Insert a new Organization entry";
			break;
		case UPDATE_REC:		
			$pageTitle="Update this existing Organization entry";
			break;
		case DELETE_REC:		
			$pageTitle="Delete this Organization Entry";
			break;			
		default: 			
			$pageTitle="Unexpected opcode " . $_POST['opcode'];
			break;
	}
	
?>	
<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 02/12/2004 -->
<head>
<?php
	print("<title>$pageTitle</title>\n");
	?>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Chris Newey">
	<meta name="generator" content="AceHTML 5 Freeware">
	<link rel="stylesheet" type="text/css" href="css/all.css" />
	<link rel="stylesheet" type="text/css" href="css/3cols.css" />		
</head>
<body>
<!-- ------------------ HEADER --------------------------------------- -->	
<?php
print "<H3>$pageTitle</H3>\n";
?>
<hr />
<!-- ---------------- END HEADER ------------------------------------- -->





<?php

/*********************
 Validate fields to be written to row
 ****************************************/
function validate() {
	
	$timeObj = new mysqltime;
	$dateObj = new mysqldate;
	$result = true;
	
	// If we are going to delete it we don't care if its valid
	if ($_POST['opcode'] == DELETE_REC) {
		return True;
	}
	
	
	// Organization name may not be blank
	if ((!isset($_POST['orgname'])) || (!is_string($_POST['orgname'])) || (trim($_POST['orgname']) == "")) {
		$_SESSION['oOrganisations']->addErrorMessageCol('orgname','Required');	
		dispError("Organization name may not be blank");		
		$result = false;	
	}
	else {
		$_SESSION['oOrganisations']->delErrorMessageCol('orgname');	
	}
	
	
	return $result;
} 
 


	
/********************
 Add/Amend/Delete SQL 
 ***********************/	
function doSQL($mysqli) {

	
	switch ($_POST['opcode']) {
		case INSERT_REC:		
			$stmnt = sprintf("INSERT INTO organisations
												(	orgname,contact,
													adr1,adr2,adr3,town,country,postcode,
													email,mobile,phone,fax,description
												)
												VALUES ('%s','%s',
													'%s','%s','%s','%s','%s','%s',
													'%s','%s','%s','%s','%s')",
												mysql_real_escape_string($_POST['orgname']),												
												mysql_real_escape_string($_POST['contact']),
												mysql_real_escape_string($_POST['adr1']),
												mysql_real_escape_string($_POST['adr2']),													
												mysql_real_escape_string($_POST['adr3']),													
												mysql_real_escape_string($_POST['town']),													
												mysql_real_escape_string($_POST['country']),													
												mysql_real_escape_string($_POST['postcode']),													
												mysql_real_escape_string($_POST['email']),													
												mysql_real_escape_string($_POST['mobile']),													
												mysql_real_escape_string($_POST['phone']),													
												mysql_real_escape_string($_POST['fax']),													
												mysql_real_escape_string($_POST['description'])													
												); 
			$msg = "New record created OK"; 
			break;
			
		case UPDATE_REC:		
			$stmnt = sprintf("UPDATE organisations
												SET 
													orgname = '%s',
													contact = '%s',
													adr1 = '%s',adr2 = '%s',adr3 = '%s',
													town = '%s',country = '%s',postcode = '%s',
													email = '%s',mobile = '%s',phone = '%s',
													fax = '%s',description = '%s'
												WHERE id=%d",
												mysql_real_escape_string($_POST['orgname']),												
												mysql_real_escape_string($_POST['contact']),
												mysql_real_escape_string($_POST['adr1']),
												mysql_real_escape_string($_POST['adr2']),													
												mysql_real_escape_string($_POST['adr3']),													
												mysql_real_escape_string($_POST['town']),													
												mysql_real_escape_string($_POST['country']),													
												mysql_real_escape_string($_POST['postcode']),													
												mysql_real_escape_string($_POST['email']),													
												mysql_real_escape_string($_POST['mobile']),													
												mysql_real_escape_string($_POST['phone']),													
												mysql_real_escape_string($_POST['fax']),													
												mysql_real_escape_string($_POST['description']),		
												$_POST['id']												
												); 
			$msg = "Record updated OK"; 			
			break;
			
		case DELETE_REC:		
			$stmnt = sprintf("DELETE FROM organisations
											 WHERE id=%d",
											 $_POST['id']				
											); 
			
			$msg = "Record Deleted OK"; 				
			break;
		default: 			
			break;
	}

	if (mysql_query($stmnt)) {
		print("<p>" . $msg . "</p>\n"); 
		return True;
	}
	else {
		print("<p>" . mysql_error() . "</p>\n");
		return False;
	}

}

?>



<?php
	/*****************
	 Main Processing
	 **********************/
	
	$_POST['opcode'] = strtoupper($_POST['opcode']);
	
	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');
		
	
	if (validate()){
		if (doSQL($dbHandle)) {
			unset($_SESSION['oOrganisations']);
		}
	}
	else {
		$args = http_build_query(array('opcode' => $_POST['opcode']) + array('id' => $_POST['id']));
		print("<p>Return to Organization entries <a href=\"editorgs.php?$args\">Edit Page</a></p>\n");		
	}

		
	print("<p>Return to <a href=\"borgs.php?$args\">Organization Entries</a>" . 
		    "&nbsp;&nbsp;<a href=\"admin.php?$args\">Admin</a>" . 	
				"</p>\n");

	
?>

<!-- ------------------ FOOTER --------------------------------------- -->
<HR>
</body>
</html>
<!-- ---------------- END FOOTER ------------------------------------- -->
