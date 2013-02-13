<?php
/*********************************
 Handles logging in and logging out a member

14/03/05 CDN 			Originally written for PHP 5 - lobotomised and hacked to go back to PHP 4
	
**************************************/
require_once("globals.php");
require_once("genlib.php");
require_once("mysql.php");
require_once("buildform.php");
require_once("class.cdnmail.php");

?>


<?php

	/*****************
	$args = http_build_query(array('displaypage' => 'login.php',
																 'loginstatus' => LOGIN_MEMBER	));	
 	echo "<p><a  href=\"index.php?$args\">Log in</a></p>\n";	
	*************************/

// Whatever we are going to do it happens in a paragraph
echo "<p>\n";
	
 	switch($_GET['loginstatus']) {
	 	
		case LOGIN_MEMBER:
			loginMember();
			break;
		
		case LOGIN_VALIDATE:
			loginValidate();
			break;			
			
		case LOGIN_CREATE:
			loginCreate();
			break;		
			
		case LOGOUT_MEMBER:
			logoutMember();
			break;

		case LOGIN_MEMBER_EDIT:
			editMemberDetails();
			break;			

		case LOGIN_MEMBER_UPDATE:
			updateMemberDetails($_GET['opcode']);
			break;		
						
			
												
		default:
			print("<h4>Invalid Login operation requested  - loginstatus = " . $_GET['loginstatus']);
			break;
	}

echo "</p>\n";
		
?>






<?php
/****************************
 Login Form
 **********************************/
	function loginMember() {
	
		$args = http_build_query(array('displaypage' => 'login.php',
																	 'loginstatus' => LOGIN_VALIDATE));	
	
					
		print("<br />\n");												 
		print("<form action=\"index.php?$args\" METHOD=\"POST\" >\n");	
		
		print("<P>\n");
		print("Username <INPUT TYPE=text NAME=\"nickname\" VALUE=\"" . $_GET['nickname'] . "\" SIZE=50 MAXLENGTH=50>\n");
		print("</P>\n");		
?>	

		<P>
		Password <INPUT TYPE=password NAME=password SIZE=20 MAXLENGTH=20>
		</P>
		<P>
		<INPUT TYPE=submit VALUE="Submit"> &nbsp; <INPUT TYPE=reset VALUE="Reset">
		</P>		
			
		</form>
		
		
<?php		
		$args = http_build_query(array('displaypage' => 'login.php',
																	 'loginstatus' => LOGIN_CREATE	));	
 		echo "<p><a  href=\"index.php?$args\">Join</a> the Christ Church Community</p>\n";	


	}
/****************************
 End of Login Form
 **********************************/	
?>


<?php
/****************************
 Login Validation Form
 
 makes sure the previously entered user name and password match an entry
 on the members table. If it does then accepts the login attempt
 and set the users nickname in a session variable. 
 **********************************/
	function loginValidate() {

		/* Connect to a MySQL server */ 
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	

		$rset = mysql_query(
							sprintf("SELECT id, password FROM members WHERE nickname = '%s'",
											$_POST['nickname']));

    /* fetch values */ 
    $row = mysql_fetch_assoc($rset);
		if ((! $rset) || (! $row)) {
		  $_SESSION['nickname'] = NULL;
	    printf("<p>Sorry, Cannot find any record of nickname %s</p>\n",$_POST['nickname']);
	    
			$args = http_build_query(array('displaypage' => 'login.php',
															 		 	 'loginstatus' => LOGIN_MEMBER,
															 		 	 'nickname'    => $_POST['nickname']));	
 			echo "<p><a  href=\"index.php?$args\">Log in</a></p>\n";	
		}
		else {
	  	if (strtoupper($row['password']) == strtoupper($_POST['password'])) {  
      	printf("<h4>Hi %s - you have been logged in. </h4>\n", $_POST['nickname']); 
      	$_SESSION['nickname'] = $_POST['nickname'];
    	} 
    	else {
		    $_SESSION['nickname'] = NULL;
	    	printf("<p>Sorry, Incorrect password for nickname %s</p>\n",$_POST['nickname']);
	    
				$args = http_build_query(array('displaypage' => 'login.php',
																 		 	 'loginstatus' => LOGIN_MEMBER,
																 		 	 'nickname'    => $_POST['nickname']));	
 				echo "<p><a  href=\"index.php?$args\">Log in</a></p>\n";		    
    	}
  	}

		mysql_free_result($rset);

		
	}
/****************************
 End of Login Validation
 **********************************/	
?>


<?php
/****************************
 Logout Member
 
 Kills the session variable containing the logged on users nickname
 **********************************/
	function logoutMember() {
		
		print("<p><h4>" . $_SESSION['nickname'] . " Logged out</h4></p>\n");
		$_SESSION['nickname'] = NULL;

	}
/****************************
 End of Logout Member
 **********************************/	
?>




<?php
/****************************
 Create a new member Form
 
 Calls the edit member form routine asking for a blank record
 **********************************/
	function loginCreate() {

		editMemberDetails(LOGIN_CREATE);
	
	}
/****************************
 End of Create a new member form
 **********************************/	
?>


<?php
/****************************
 Edit Member Details Form
 
 Edits a members details, or if LOGIN_CREATE is passed as $edittype arg
 it presents a blank form
 **********************************/
	function editMemberDetails($editType = LOGIN_MEMBER_EDIT) {
		
		global $oSession;

		/* Connect to a MySQL server */ 
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	

				
		switch ($editType) {
		
			case LOGIN_MEMBER_EDIT:  // Edit an existing member
				$opcode = UPDATE_REC;
				print("<h4>Edit Member Details</h4>\n");			
				
				
				
				$rset = mysql_query(
										sprintf("SELECT id, nickname, forename, surname, email, sendnewsletter,
																				memberstatus, password AS password1, password AS password2,
																				joined, confirmed, suspended,
																				cancelled,htmlemail
																 FROM members 
																 WHERE UPPER(nickname) = UPPER('%s')",
																 $_SESSION['nickname']
														));	


    		// fetch values and close the result set - the nickname is unique
    		if (mysql_fetch_assoc($rset)) { 
	    		$_SESSION['id'] = $rset['id'];
	    	}
	    	else {
	    		echo "<h4>Nickname " . $_SESSION['nickname'] . " not found</h4>\n";
	    	}
				break;
			
			
			case LOGIN_CREATE:
				$opcode = INSERT_REC;
				print("<h4>Join the Christ Church Community</h4>\n");				
				$nickname 			= $oSession->getClear_var('nickname');
				$forename 			= $oSession->getClear_var('forename');
				$surname  			= $oSession->getClear_var('surname');
				$email    			= $oSession->getClear_var('email');
				//$sendnewsletter = $oSession->get_var('sendnewsletter');				
				//$htmlemail			= $oSession->get_var('htmlemail');						
				$sendnewsletter = 'YES';				
				$htmlemail			= 'YES';					
				$password1 			= $oSession->getClear_var('password1');
				$password2 			= $oSession->getClear_var('password2');
				$memberstatus		= 'NEW';
				
				break;
				
			default:
				die("Unknown code " . $editType . " passed to editMemberDetails");
				break;
			
		}
		$oFrm = new buildform($opcode,		
													"SELECT id, nickname, forename, surname, email, sendnewsletter, htmlemail,
																	memberstatus, password AS password1, password AS password2, joined, confirmed, suspended,
																	cancelled
													FROM members 
													WHERE UPPER(nickname) = UPPER(\"" . $_SESSION['nickname'] . "\")",
													array('nickname' => $_SESSION['nickname']));
		$oFrm->addField('nickname','Nickname *',$nickname);
		$oFrm->addField('forename','Forename',$forename);													
		$oFrm->addField('surname','Surname',$surname);		
		$oFrm->addField('email','Email *',$email);		
		$oFrm->addField('sendnewsletter','Receive Newsletter',$sendnewsletter);
		$oFrm->addField('htmlemail','HTML formatted email',$htmlemail);		
		$oFrm->addField('password1','Password *',$password1);		
		$oFrm->addField('password2','Re-enter Password *',$password2);			
		
		// Password fields
		$oFrm->flagPasswordField('password1');
		$oFrm->flagPasswordField('password2');
																				 		
		$args = http_build_query(array('displaypage' => 'login.php',
															 		 'loginstatus' => LOGIN_MEMBER_UPDATE,
															 		 'opcode'			 => $opcode,	
															 		 'nickname'    => $_POST['nickname']));	
		
		$oFrm->submitTarget = "index.php?" . $args;
		$oFrm->displayQuery = False;
		
		
		$oFrm->exec();		
/*************************		
		$rset = $mysql->prepare("SELECT password FROM members WHERE UPPER(nickname) = UPPER(?)");	
		$rset->bind_param('s',$_POST['nickname']);
		$rset->execute();
		
    // bind variables to prepared statement 
    $rset->bind_result($tblPassword); 

    // fetch values 
    $rset->fetch();
 
  	if (strtoupper($tblPassword) == strtoupper($_POST['password'])) {  
      printf("<p>Hi %s - you have been logged in. </p>\n", $_POST['nickname']); 
      $_SESSION['nickname'] = $_POST['nickname'];
    } 
    else {
	    $_SESSION['nickname'] = NULL;
	    printf("<p>Sorry, Incorrect password for nickname %s</p>\n",$_POST['nickname']);
	    
			$args = http_build_query(array('displaypage' => 'login.php',
																 		 'loginstatus' => LOGIN_MEMBER,
																 		 'nickname'    => $_POST['nickname']));	
 			echo "<p><a  href=\"index.php?$args\">Log in</a></p>\n";		    
    }		
		
		
		
		$rset->close();
 *************************************/		
		//$mysql->close();
		
	}
/****************************
 End of Edit Member Details Form
 **********************************/	
?>


<?php
/****************************
 Update Member Details Form
 **********************************/
function updateMemberDetails($opcode) {

	global $oSession;
			
	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');				
			

	
	switch ($_GET['opcode']) {
			
		case INSERT_REC:

			$eflag = False;
			if (! isset($_POST['nickname'])) {
				$eflag = True;
				MySQLException("Nickname may not be blank.",-97);
			}					
					
			if (! isset($_POST['email'])) {
				$eflag = True;				
				MySQLException("Email may not be blank.",-96);
			}								
					
									
			if ($_POST['password1'] <> $_POST['password2']) {
				$eflag = True;				
				MySQLException("Password Entries are not the same - Please Re-enter your password.",-99);
			}

			if (( ! isset($_POST['password1'])) ||  (! isset($_POST['password2'])) ) {					
				$eflag = True;				
				MySQLException("Password may not be blank - Please Re-enter your password.",-98);					
			}					
				
			if (! $eflag) {
					
				$regkey = rand(1000,9000);
				$memberstatus = 'NEW';
							
				$qstr = sprintf("INSERT INTO members " .
												"(nickname,forename,surname,email,sendnewsletter,memberstatus, " .
												"	password,joined,initialpassword,htmlemail) " .
												"	VALUES ('%s', '%s', '%s', '%s', '%s', " . 
												" '%s', '%s', '%s', '%s', '%s')",
												$_POST['nickname'],
												$_POST['forename'],
												$_POST['surname'],
												$_POST['email'],
												$_POST['sendnewsletter'],
												$memberstatus,
												$_POST['password1'],
												date('Y-m-d'),
												$regkey,
												$htmlemail														
												);
												
				if (! mysql_query($qstr)) {
					if (mysql_errno() == 1062) {
						echo "<h4>Nickname \"" . $_POST['nickname']  . "\" already in use</h4>\n";
					}
					else {
						echo "<h4>Insert Failed</h4>\n";
						dbug($qstr);
						dbug(mysql_error() . ' Error No:' . mysql_errno());
					}
				} 
				else {

					$oMail = new cdnphpmail();				// Note - this sets up the sender information

					setEmailText($oMail, $regkey, $htmlemail);							// Sets up email text with HTML and plain text version
					
					var_dump($oMail);
					
					$oMail->Subject = 'Christ Church Website - confirmation of registration';
							
					// Send to webmaster
					$oMail->addAddress($_POST['email']);
		
					if ($oMail->Send()) {
						print("<p>\n");
						print("An email has been sent to the email address (" . $_POST['email'] . ") you gave.<br>\n"); 
						print("To complete your registration, open the email and click on the link in the email.\n");	
						print("</p>\n");
					}		
					else {
						print("A problem occurred when sending the email - Please try again later<br>\n");
						print("$oMail->ErrorInfo\n");
					}					

					print("<p>Thanks for joining us.</p>\n");
				}
			}
			
			break;
			
		case UPDATE_REC:

			$eflag = False;			
				
			if (! isset($_POST['nickname'])) {
				$eflag = True;
				MySQLException("Nickname may not be blank.",-97);
			}					
					
			if (! isset($_POST['email'])) {
				$eflag = True;				
				MySQLException("Email may not be blank.",-96);
			}								
					
									
			if ($_POST['password1'] <> $_POST['password2']) {
				$eflag = True;				
				MySQLException("Password Entries are not the same - Please Re-enter your password.",-99);
			}

			if (( ! isset($_POST['password1'])) ||  (! isset($_POST['password2'])) ) {					
				$eflag = True;				
				MySQLException("Password may not be blank - Please Re-enter your password.",-98);					
			}					
					
			$memberstatus = 'NEW';

			$qstr = sprintf("UPDATE members "  .
											"SET nickname = '%s', " . 
											"    forename = '%s', " .
											"    surname  = '%s', " .
											"    email    = '%s', " .
											"    sendnewsletter = '%s', " .
											"    memberstatus = '%s', " .
											"    password = '%s', " .
											"    htmlemail= '%s' " .
											"WHERE id = '%s'",
											$_POST['nickname'],
											$_POST['forename'],
											$_POST['surname'],
											$_POST['email'],
											$_POST['sendnewsletter'],
											$memberstatus,
											$_POST['password1'],
											$_POST['htmlemail'],
											$_SESSION['id']				
											);

												
			if (! mysql_query($qstr)) {
				unset($_SESSION['nickname']);				
				dbug($qstr);	
			}		
			else {													
				// Store the nickname in the session - the update may have changed it
				$_SESSION['nickname'] = $_POST['nickname'];
				print("<p><h4>Your Settings have been updated</h4></p>\n"); 						
			}
				
			break;
				
								
						
			case DELETE_REC:
				dbug('delete');
				break;			
			
			default:
				die("Don't know how to cope with opcode " . $_GET['opcode']);	
				break;
	}

}
/****************************
 End of Update Member Details Form
 **********************************/	
?>


<?php

/******************
Returns registration confirmation email HTML
**************************/
function getEmailText($regKey,$localIP)	{

	//$s = "HTML incapable mail client";
	// Build args for acceptance of registration
	$argsA = http_build_query(array('regKey' => $newRegKey,		// Registration key
				 												  'status' => LOGIN_ACCEPT_REGISTRATION,	
				 												  'cstatus' => 'NEW',			 	// Current registration status
																  'nickname' => $_POST['nickname']));

	$s = "This email has been sent to you as part of the registration process\n" . 
			 "Your User ID is ". $_POST['nickname'] . "\n" . 
			 "Your Password is ". $_POST['password1'] . "\n" . 			 
	     "paste this link into your browser\n" . 
	 	   "http://" . $localIP . "/church/registration.php?" . $argsA . "\n" .
	 	   " to complete the registration process " .
	 	   "</p>\n";

	// Build args for decline of registration	 	   
	$argsD = http_build_query(array('regKey' => $newRegKey,		// Registration key
				 												  'status' => LOGIN_DECLINE_REGISTRATION,
				 												  'cstatus' => 'NEW',			// Current registration status
																  'nickname' => $_POST['nickname']));			

	$s = $s . "If you have not registered with the Christ Church Website then \n" . 
	     "paste this link into your browser\n" .
	 	   "http://" . $localIP  . "/church/registration.php?" . $argsD . " \n" .	 	   
	 	   " to cancel the registration process.\n" .
	 	   "\n";

		
	
	return $s;	

}


/******************
Returns registration confirmation email HTML
**************************/
function getEmailHTML($newRegKey,$localIP) {
	
	// Build args for acceptance of registration
	$argsA = http_build_query(array('regKey' => $newRegKey,		// Registration key
				 												  'status' => LOGIN_ACCEPT_REGISTRATION,	
				 												  'cstatus' => 'NEW',			 	// Current registration status
																  'nickname' => $_POST['nickname']));

	$s = "<p>This email has been sent to you as part of the registration process</p>\n" . 
			 "<p>Your User ID is ". $_POST['nickname'] . "</p>\n" . 
			 "<p>Your Password is ". $_POST['password1'] . "</p>\n" . 			 
	     "<p>Click on this " . 
	 	   "<a  href=\"http://" . $localIP . "/church/registration.php?" . $argsA . "\">Link</a> " .
	 	   " to complete the registration process " .
	 	   "</p>\n";

	// Build args for decline of registration	 	   
	$argsD = http_build_query(array('regKey' => $newRegKey,		// Registration key
				 												  'status' => LOGIN_DECLINE_REGISTRATION,
				 												  'cstatus' => 'NEW',			// Current registration status
																  'nickname' => $_POST['nickname']));			

	$s = $s . "<p>If you have not registered with the Christ Church Website then click on this " .
	 	   "<a  href=\"http://" . $localIP  . "/church/registration.php?" . $argsD . "\">Link</a> " .	 	   
	 	   " to cancel the registration process.\n" .
	 	   "</p>\n";

	return $s;

}

/*****************
 Creates the emails to get registration confirmation
 Finds the IP in the church.sysconf table where its
 been stashed by the Delphi getIP program which was
 run at startup
 *******************************************/
function setEmailText(&$oMail, $regKey, $htmlemail) {

	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	
	
		
	if (strToUpper($htmlemail) == 'YES') {
		$oMail->IsHTML(True);							// Sets message body type to text/html		
	}
	else {
		$oMail->IsHTML(False);							// Sets message body type to text		
	}
		
	$rset = mysql_query("SELECT ipaddress FROM sysconf");	
	$row = mysql_fetch_assoc($rset);

	
	if (strToUpper($htmlemail) == 'YES') {
		$oMail->AltBody = getEmailText($regKey,$localIP);	// if html is true then this contains plain text for use by email clients
																											// that don't understand html                                
		$oMail->Body    = getEmailHTML($regKey,$localIP);	// HTML text		
	}
	else {
		$oMail->Body = getEmailText($regKey,$localIP);		// Send Plain Text
	}
	
	echo "<pre>\n";
		print_r($htmlemail);
		print_r($regKey);
		print_r($localIP);				
		print_r($oMail->AltBody);
		print_r($oMail->Body);
	echo "</pre>\n";
	
	mysql_free_result($rset);
	
}





?>
