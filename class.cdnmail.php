<?php
/****************************************

Chris Newey

Wednesday 04/06/2008

Completely rewrite to use PHP Mail function instead
of phpmailer class.

Needed because website has rebuilt PHP to stop
exec being used.

*******************************************/


// Subclass phpMailer class in order to provide some defaults
class cdnphpmail {

	var $MAIL_EMPTY_TO_ADDRESS			=		  1;		// Field can't be NULL 
	var $MAIL_EMPTY_SUBJECT				=		  2;		// Field can't be NULL 
	var $MAIL_EMPTY_TEXT						=		  3;		// Field can't be NULL 	
	var $MAIL_EMPTY_REPLY_ADDRESS	=			4;		// Reply Address can't be NULL
	
	var $MAIL_OPCODE_SEND					=			4;		// Used by calling webpage
	var $MAIL_OPCODE_COMPOSE				=			5;		// Used by calling webpage
	
	
	var $SMTPAuth = true;     								// turn on SMTP authentication
	var $Username = "cn004h9097";  					// SMTP username
	var $Password = "charlton";			 				// SMTP password	
	var $Host     = "smtp.blueyonder.co.uk";
  var $Mailer   = "smtp";            	    // Alternative to IsSMTP()
  var $webmasterEmail = "cdnwebsite@blueyonder.co.uk";
  var $websiteName = "No Site name";
	
  
  var $pageTarget;												// URL to jump to after performing requested task
  
	  // Set default variables for all new objects
  var $From     = "cdnwebsite@blueyonder.co.uk";
  var $FromName = "Website";
  var $WordWrap = 75;

  // Replace the default error_handler
  function error_handler($msg) {
  	print("File - class.cdnmail.php<br>");				
    print("Error - Class cdnphpmail<br>");
    printf("Description: %s<br>", $msg);
    exit;
  }
  
  // Constructor
  function cdnphpmail() 
	{
		// set email format to Plain Text
  }

	/***********
	 CDN 08-June-2008
	Use PHP's built in mail function
	****************************/
	function Send()
	{
    $sendTo = "";
    for($i = 0; $i < count($this->to); $i++)
    {
      if($i != 0) 
			{ 
				$to .= ", "; 
			}
      $sendTo .= $this->to[$i][0];
    }

		$headers = 'From: ' . $this->From . "\r\n" .
               'Reply-To: ' . $this->From . "\r\n";

		return mail($sendTo,$this->Subject,$this->Body, $headers);

	}


}


