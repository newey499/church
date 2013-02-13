<?php
/******************************************

Chris Newey

04 - June - 2008

Completely rewrite:

ISP has rebuilt PHP to disable various system functions which breaks
the phpMailer class which was previously used to generate mail.

Quick and dirty fix to use the PHP mail function instead.

03/12/08 CDN HTML code to build a link
         NOTE: EVERYTHING in lower case
<form action="index.php?displaypage=mailform.php" method="post">
<input type="hidden" name="emailrecipient" 
value="office@christchurchlye.org.uk">
<input type="submit" value="Email">
</form>

22/01/2009 CDN - copied from mailform.php and changed to work in a 
                 javascript popup form 


*******************************************/

require_once("genlib.php");
require_once("dbconnectparms.php");

?>


<html>
<head>

<title>Send email</title>


<!-- CSS Includes -->
<link rel="stylesheet" type="text/css" href="css/layout.css" />
<link rel="stylesheet" type="text/css" href="css/church.css" />		
<!-- End CSS Includes -->



</head>

<body style="margin-left:10px">




<br />

<?php


	// Program Entry Point
	if (isset($_POST['sendemail']))
	{ 
		sendEmailFromUser($_POST['mailTo']);
	}
	else
	{
		displayEmailForm($_GET['mailTo']);	
	}
	return;
?>

<?php

function sendEmailFromUser($recipient)
{

	if (isset($_POST['sendemail']))
	{
		if (! ($isValidEmail = validateEmailForm()) )
		{
			// Validation Failed - redisplay form with error messages
			displayEmailForm($_POST['emailrecipient']);
		}
		else		
		{

			$headers = 'From: ' . $_POST['emailreplyto1']  . "\r\n" .
                 'Reply-To: ' . $_POST['emailreplyto1'] . "\r\n";
	

			if (mail(	$_POST['emailrecipient'],
							  $_POST['emailsubject'],
								$_POST['emailtext'],
								$headers))
			{
				echo "<h2>Mail Sent ok</h2> \n";
				echo "<br /> \n";
				echo "<br /> \n";				
			}
			else
			{
				echo "<h2>Message was not sent</h2>\n";
				echo "Mailer Error: " . $oMail->ErrorInfo . "<br /> \n";
				echo "<br /> \n";
				echo "To " . $_POST['emailrecipient'];
				echo "<br /> \n";
				echo "Subject " . $_POST['emailsubject'];
				echo "<br /> \n";
				echo "message " . $_POST['emailtext'];
				echo "<br /> \n";							
				echo "Headers " . $headers;
				echo "<br /> \n";	
				echo "<br /> \n";	
			}

			print("<input type=\"button\" value=\"Close Window\" onclick=\"javascript:window.close();\">\n");
		
			$_POST = array();
		
			return;
		}

	}


	return;
	
}	

?>



<?php
// ==================================================
function validateEmailForm()
{
	$isValidEmail = true;

	
	if ( empty($_POST['emailrecipient']) )
	{
		$isValidEmail = false;
		dispError("Email Recipient Required <br /> \n", true);
	}
	
	if ( empty($_POST['emailsubject']) )
	{
		$isValidEmail = false;
		dispError("Email Subject Required <br /> \n", true);
	}		
	
	if ( empty($_POST['emailreplyto1']) )
	{
		$isValidEmail = false;
		dispError("Email Reply Address Required <br /> \n", true);
	}				
	
	if ( empty($_POST['emailreplyto2']) )
	{
		$isValidEmail = false;
		dispError("Confirmation of Email Reply Address Required <br /> \n", true);
	}					
	
	
	if ( $isValidEmail && ! ($_POST['emailreplyto1'] == $_POST['emailreplyto2']) )
	{
		$isValidEmail = false;
		dispError("Email Reply Address and confirmation are different<br /> \n", true);
	}						

	$_POST['emailtext'] = trim($_POST['emailtext']);
	if ( empty($_POST['emailtext']) )
	{
		$isValidEmail = false;
		dispError("Email Text Required <br /> \n", true);
	}			

	if (! $isValidEmail)
	{
		print("<br />\n");
	}

	
	return $isValidEmail;
}

// ==================================================
?>



<?php
// ==================================================
function displayEmailForm($recipient)
{
?>

 <h2>Send Email</h2>
 <br /> 

 <form action="mailformpopup.php" method="post" class="emailForm" >

  <input type="hidden" name="sendemail" value="sendemail" > 

	<table border="0" align="left">
	
	<colgroup width="150">
	<colgroup width="*">
	

	<tbody> 
 
 		<tr>
	    <td>
  	  To &nbsp;
  	  </td>

  	  <td> 
  	  <span class="emailFormRecipient" >
  	  <input type="text" size="50" name="emailrecipient" value="<?php print($recipient); ?>"
  	         readonly >  
  	  </span>
  	  </td>
		</tr>

    <tr>
	    <td>
	    Subject &nbsp;
			</td>
	    <td>
	    <input type="text" size="50" name="emailsubject" value="<?php print($_POST['emailsubject']); ?>">
			</td>
		</tr>
    
    <tr>
	    <td>
	    Your email &nbsp;
	    </td>
			<td>
	    <input type="text" size="50" name="emailreplyto1" value="<?php print($_POST['emailreplyto1']); ?>" >  
			</td>
		</tr>

    
    <tr>
	    <td>
	    Confirm email &nbsp;
	    </td>
			<td>
	    <input type="text" size="50" name="emailreplyto2" value="<?php print($_POST['emailreplyto2']); ?>" >  
			</td>
		</tr>

 		<tr>
	 		<td colspan="2">
 			Content (1000 Characters Maximum)    
  		</td>
		</tr>
      
 		<tr>
	 		<td colspan="2">
	    <textarea name="emailtext" rows="20" cols="75" maxlength="1000" ><?php print($_POST['emailtext']); ?></textarea>
			</td>
		</tr>

	 <tr>
	   <td>


		</td>
		<td>
 		  <input type="submit" value="Send"> 
			&nbsp;
			&nbsp;
			<input type="reset">
			&nbsp;
			&nbsp;
			<input type="button" value="Close Window" onclick="javascript:window.close();"> 
		</td>

	</tr>        
   
 	</tbody>
 
 	</table>   
 	 
 </form>
 
<?php
return;
}

// ==================================================
?>


</body>
</html>
