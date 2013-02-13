<?php

print "<h1>Webmaster</h1>";

require_once("globals.php");
require_once("mysql.php");
require_once("class.cdnmail.php");

// For Email text
$textAreaRows = 20;
$textAreaCols = 49;

$oMail = new cdnphpmail();

if (! isset($_GET['opcode'])) {
	$opcode = cdnphpmail::MAIL_OPCODE_COMPOSE;
}


switch ($_GET['opcode']) {
	case cdnphpmail::MAIL_OPCODE_COMPOSE:
		composeMail($oMail,$textAreaRows,$textAreaCols,False);
		break;

	case cdnphpmail::MAIL_OPCODE_SEND:
		composeMail($oMail,$textAreaRows,$textAreaCols,True);		
		break;		
		
	default:
		throw new exception("Unrecognised opcode [" . $opcode . "passed to webmaster.php");
		break;
}
		





function composeMail(cdnphpmail $oMail,$textAreaRows, $textAreaCols, $validate=False) {
	
	$args = http_build_query(array('opcode' => cdnphpmail::MAIL_OPCODE_SEND,
															 	 'displaypage' => 'webmaster.php'
																)
													);

	$validEmail = true;													
													
	dispError("* = Required");
	
	print("<FORM STYLE=\"margin-left:20px;\" ACTION=\"index.php?" . $args . "\" METHOD=\"POST\"	>\n");
	//print("<FORM class=\"cdnform\" ACTION=\"index.php?" . $args . "\" METHOD=\"POST\"	>\n");
	
	print("<table id=\"emailtable\" >\n");
	
		print("<tr id=\"emailtable\">\n");
			print("<td>\n");
				print("Your email<br>address\n");
				dispError('*', True);
			print("</td>\n");

			print("<td>\n");
				print("<INPUT TYPE=\"TEXT\" " .  
							"STYLE=\"border:1px solid;\" " . 
							"VALUE=\"" . $_POST['emailreplyaddress'] . "\" " . 
							"NAME=\"emailreplyaddress\" " . 
							"SIZE=50 " .
							"MAXLENGTH=50>\n");
			print("</td>\n");		
			
			print("<td id=\"emailtable\" >\n");
				if ($validate && empty($_POST['emailreplyaddress'])) {
					dispError("Please enter your Email address\n");  
					$validEmail = false;
				}
			print("</td>\n");				
		print("</tr>\n");

		
		print("<tr id=\"emailtable\">\n");
			print("<td>\n");
				print("Subject\n");
				dispError('*', True);				
			print("</td>\n");	

			print("<td>\n");
				print("<INPUT TYPE=\"TEXT\" " .
							"STYLE=\"border:1px solid;\" " . 				
							"VALUE=\"" . $_POST['emailsubject'] . "\" " .
				      "NAME=\"emailsubject\" " .
				      "SIZE=50 " .
				      "MAXLENGTH=50>\n");
			print("</td>\n");			

			print("<td id=\"emailtable\" >\n");
				if ($validate && empty($_POST['emailsubject'])) {
					dispError("Please provide a Subject\n");  
					$validEmail = false;
				}
			print("</td>\n");					
		print("</tr>\n");


		print("<tr id=\"emailtable\">\n");
			print("<td>\n");
				print("Text\n");
				dispError('*', True);				
			print("</td>\n");
					
			print("<td>\n");
				print("<textarea STYLE=\"border:1px solid;\" rows =\"$textAreaRows\" cols=\"$textAreaCols\" name=\"emailtext\">\n");
					print($_POST['emailtext']);
				print("</textarea>\n");
			print("</td>\n");
			
			print("<td id=\"emailtable\" >\n");
				if ($validate && empty($_POST['emailtext'])) {
					dispError("Please enter some message text\n");  
					$validEmail = False;					
				}
				
				if ($validate && ! $validEmail) {
					dispError("Please correct the problem entries and try to send the email again\n");	
				}
			print("</td>\n");					
		print("</tr>\n");

	print("</table>\n");

	if ($validate && $validEmail) {
		// Send the email

		//$oMail->IsHTML(True);					// Sets message body type to text/html
		//$oMail->AltBody = $emailtext;	// if html is true then this contains plain text for use by email clients
		//															// that don't understand html                                
		//$oMail->Body    = $emailtext;	// HTML text	
		
		$oMail->IsHTML(False);										// Don't send HTML mail
		$oMail->Body    = $_POST['emailtext'];		// send plain text
				
		$oMail->Subject = 'From ' . $_POST['emailreplyaddress'] . ' | ' . $_POST['emailsubject'];

				
		// From whatever the user entered for their email address
		$oMail->from 	   =	$_POST['emailreplyaddress'];
		$oMail->fromName =	$_POST['emailreplyaddress'];
		$oMail->sender	 =	$_POST['emailreplyaddress'];
		
		// Send to webmaster
		$oMail->addAddress($oMail->webmasterEmail);
		
		if ($oMail->Send()) {
			print("Email Sent OK\n");	
		}		
		else {
			print("A problem occurred when sending the email - Please try again later<br>\n");
			print("$oMail->ErrorInfo\n");
			
		}
	}
	else {
		// Allow the email to be resubmitted
		print("<p>\n");
		print("<INPUT STYLE=\"margin-left:58px;\" TYPE=\"SUBMIT\" VALUE=\"Send\">\n");
		print("&nbsp;\n");
		print("<INPUT STYLE=\"margin-left:228px;\" TYPE=\"RESET\" VALUE=\"Reset\">\n");
		print("<p>\n");
	}

	print("</form>\n");
} // End of composeMail


?>

