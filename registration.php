<?php
	/******************************
		Start a session. This must be the very first thing done on the page.
	**********************************/
	require_once("session.php");
	$oSession = new session();
?>	

<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 08/01/2005 -->
<head>
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="newey499@hotmail.com">
	<meta name="generator" content="AceHTML 5 Freeware">
	<link rel="stylesheet" type="text/css" href="/css/server.css" />
	<link rel="stylesheet" type="text/css" href="css/site.css" />		
	<link rel="stylesheet" type="text/css" href="css/church.css" />		
</head>

<body>


<?php

require_once("mysql.php");
require_once("globals.php");
require_once("genlib.php");

?>

<?php
	include_once("topbanner.html");
?>


<br>



<?php
	print("<h1>Registration</h1>\n");



	// Read the system configuration table into memory
	$oSession->loadSystemVars();


	if (! $mysql = new mysqlj(MYSQL_SERVER,MYSQL_USERNAME,MYSQL_PASSWORD,MYSQL_DATABASE)) {
  	die("Connect failed: " .  mysqli_connect_error());
	}		
		

	// look up the user		
	$rset = $mysql->prepare("SELECT nickname,initialpassword,password,memberstatus FROM members where nickname=?");	
	$rset->bind_param('s',$_GET['nickname']);	
	
	$rset->execute();

  /* bind variables to prepared statement */ 
  $rset->bind_result($nickname,$initialpassword,$password,$memberstatus); 

  /* fetch values */ 
	$rset->fetch();
	
	

	// Have we found a member record
	if (! ($nickname == $_GET['nickname'])) {
		dispError("Sorry, Cannot find registration details for nickname " . $_GET['nickname']);
		dispRegError(1010);
	}
	else {

		switch ($memberstatus) {
			
			case 'CURRENT':
				// Already registered  - issue message and do nothing			
				dispError("Hi $nickname, You are already registered.");
				break;
				
			case 'NEW':
			case 'SUSPENDED':
			case 'CANCELLED':			
				// Check the current user status on the email matches that on the database
				if (strToUpper($_GET['cstatus']) == strToUpper($memberstatus)) {
					// Check that the magic number on the email matches the one on the database
					if ($_GET['regKey'] == $initialpassword) {
						dispError("Hi $nickname, You registration is now activated.");						
						// see if the registration has been accepted or declined
						switch ($_GET['status']){
							
							case LOGIN_ACCEPT_REGISTRATION:
								dbug('processing acceptance');
								
								/********************
								$args = http_build_query(array('displaypage' => 'index.php',
																							 'loginstatus' => LOGIN_VALIDATE,
																							 'nickname'		 => $nickname, 	
																							 'password'    => $password
																							 ));	
																		 
								print("<a HREF=\"index.php?$args\" >Log in</a>\n");									
								**********************************/
								print("<a HREF=\"index.php?$args\" >Home</a>\n");									
								
								break;
								
							case LOGIN_DECLINE_REGISTRATION:
								dbug('processing deline');
								break;
								
							default: 
								dispRegError(1020);			
								break;
						}		
					}
					else {
						dbug("get " . $_GET['cstatus'] . " table " . $memberstatus);
						dbug("get [*" . $_GET['regKey'] . "*] table [" . $initialpassword . "]");						
						dispRegError(1030);							
					}
				}
				else {
					dispRegError(1040);						
				}		
				break;				
				
			default:
				dispRegError(1050);
				break;	
			
		}
			
	}
		

	$rset->close();
	$mysql->close();	


function dispRegError($errcode) {
	dispError("Sorry, the Registration Details you have sent do not match those expected");						
	dispError("Your registration cannot be completed. ($errcode)");
	dispError("Please email the webmaster and mention the above error code in the email.");			
}
	
	


?>

</body>
</html>