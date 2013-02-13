<?php

	require_once("dbconnectparms.php");
	require_once("globals.php");
	require_once("genlib.php");
	require_once("mysql.php");
	require_once("dumpquerytotable.php");
	require_once("class.cdnmail.php");
	require_once("mysqldatetime.php");
	require_once("nusoap.php");

  // How to force a redirect
  //Header("Status: 302");
  //Header("Location: http://www.google.co.uk"); 

	/***********
	Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
	Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
	****************/
	$internetExplorer = isInternetExplorer();
	//$internetExplorer = TRUE;
	if ($internetExplorer)
	{
		// Force a redirect
		//Header("Status: 302");
		//Header("Location: indexie.php"); 
	}

 // No Caching
 Header("Cache-Control: no-cache");
 Header("Cache-Control: no-store");
 Header("Cache-Control: must-revalidate");
 Header("Cache-control: private"); 		// Needed to work around a bug in IE 5	

	/******************************
		Start a session. This must be the very first thing done on the page.
	**********************************/
	require_once("session.php");
	$oSession = new session();

	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	

	
	/***********
	Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
	Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
	****************/
	$internetExplorer = isInternetExplorer();
	//$internetExplorer = TRUE;
	if ($internetExplorer)
	{
		print("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\ " .
	        "\"http://www.w3.org/TR/html4/loose.dtd\">");
	}
	else
	{
		print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
		print("<?xml-stylesheet type=\"text/xsl\" href=\"copy.xsl\"?>\n");
		print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' .
				  '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	}
?>


<?php


/***********
Internet Explorer up to and including IE 7 does not support XHTML 1.0 Transitional
Typical Microsoft frigging Crap - Firefox, Chrome and Safari don't have a problem
****************/
if ($internetExplorer)
{
	print("<html>\n");
}
else
{
	print('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');
}
?>


<head>

<?php

if ($internetExplorer)
{
	print("  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />\n");
	print("  <meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\" />\n");
}
else
{
	print("  <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\" />\n");
	print('<meta http-equiv="Content-Style-Type" content="text/css" />' . "\n");
}

?>

  <title>Christ Church, Lye, West Midlands UK</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />	

  <meta name="keywords" content="christ, church, lye, cofe, evangelical, england" />	

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" />
  <link rel="stylesheet" type="text/css" href="../css/church.css" />		
  <!-- End CSS Includes -->

  <!-- Javascript Includes -->
  <script type="text/javascript" src="jscript/church.js" ></script> 
  <script type="text/javascript" src="jscript/genlib.js" ></script> 
  <script type="text/javascript" src="jscript/calendarDateInput.js"></script>

  <!-- <script type="text/javascript" src="jscript/javaxhtml1-0.js" ></script> -->
  <!-- <script type="text/javascript" src="jscript/jquery-1.2.6.min.js" ></script> -->
  <!-- <script type="text/javascript" src="jscript/chromejs/chrome.js" ></script> -->

  <!-- End Javascript Includes -->

	<script>
		// CDN 18/01/2009 
		// Global variable to hold the Date Object to make it available during validation
		var objDate;

	</script> 


</head>


<body> 

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<!-- ========================================================================== -->
<!--                             Start Top Section                              --> 
<!-- ========================================================================== -->
<div id="topsection">
<div class="innertube">

<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>

</div>  <!-- End <div class="innertube"> -->
</div>  <!-- End <div id="topsection"> -->

<!-- This extra pointless div is needed to get IE out of the crap -->
<!-- Without it the top banner is not displayed                   -->
<!--[if IE ]>
	<div>
	</div>
<![endif]-->


<!-- ========================================================================== -->
<!--                               End Top Section                              --> 
<!-- ========================================================================== -->




<!-- ========================================================================== -->
<!--                          Start Center Content Section                      --> 
<!-- ========================================================================== -->
<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">


<noscript> 
<p>
<em>
Either your browser does not support JavaScript or Javascript has been disabled.
This page requires a Javascript enabled Browser.
</em>
</p>
</noscript> 


<!-- ========================================================================== -->
<!--   Check for obsolete Microsoft crap as opposed to current Microsoft crap   -->
<!-- ========================================================================== -->
<!--[if IE 4 ]>
  <h4>Internet Explorer 4 is obsolete and should be upgraded.</h4>
<![endif]-->
<!--[if IE 5 ]>
  <h4>Internet Explorer 5 is obsolete and should be upgraded.</h4>
<![endif]-->
<!--[if IE 6 ]>
  <h4>Internet Explorer 6 is obsolete and should be upgraded.</h4>
<![endif]-->

<br />

<h2>
Add a New Forthcoming Event
</h2>


<?php
if (isset($_POST['submit']))
{


	if ($_POST['feChkEventTime'] == 'feChkEventTime')
	{
		//print("Time WILL be included");
	}
	else
	{
		//print('Time will NOT be included - mapped to FC_HIDE_TIME ("11:11")' );
		$_POST['feHours'] = "11";
		$_POST['feMinutes'] = "11";
	}

	$eventDate = $_POST['feEventDate'] . " " . 
							 $_POST['feHours'] . ":" . $_POST['feMinutes'];


  // Debug Diagnostics
  // =================
	/************************
	print("<p>\n");

	print("feChkEventTime [" . $_POST['feChkEventTime'] . "]\n");
	print("<br />\n");


	print("<br />\n");

	print("feEventDate [" . $_POST['feEventDate'] . "]\n");
	print("<br />\n");

	print("feHours [" . $_POST['feHours'] . "]\n");
	print("<br />\n");

	print("feMinutes [" . $_POST['feMinutes'] . "]\n");
	print("<br />\n");

	print('$eventDate [' . $eventDate . "]\n");
	print("<br />\n");

	print("feName [" . $_POST['feName'] . "]\n");
	print("<br />\n");

	print("feDesc [" . $_POST['feDesc'] . "]\n");
	print("<br />\n");

	print("contactName [" . $_POST['contactName'] . "]\n");
	print("<br />\n");

	print("contactPhone [" . $_POST['contactPhone'] . "]\n");
	print("<br />\n");

	print("contactEmail [" . $_POST['contactEmail'] . "]\n");
	print("<br />\n");

	print("feEmail1 [" . $_POST['feEmail1'] . "] (Contributor)\n");
	print("<br />\n");

	print("feEmail2 [" . $_POST['feEmail2'] . "] (Contributor)\n");
	print("<br />\n");

	print("</p>\n");
	******************************/

	/* Connect to a MySQL server */ 
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	


	$qry = sprintf( "INSERT INTO forthcomingevents " . 
									" ( " . 
									"   eventdate, eventname, eventdesc, contribemail, " . 
									"   contactname, contactphone, contactemail, isvisible " . 									
									" ) " . 
									"VALUES " . 
									" ( " . 	
									"   '%s', '%s', '%s', '%s', " . 
									"   '%s', '%s', '%s', '%s' " . 
									" ) ",
									$eventDate, 
                  mysql_real_escape_string(strip_html_tags($_POST['feName'])),
                  mysql_real_escape_string(strip_html_tags($_POST['feDesc'])), 
                  mysql_real_escape_string(strip_html_tags($_POST['feEmail1'])), 
									mysql_real_escape_string(strip_html_tags($_POST['contactName'])), 
                  mysql_real_escape_string(strip_html_tags($_POST['contactPhone'])), 
                  mysql_real_escape_string(strip_html_tags($_POST['contactEmail'])),
                  'NO'   // isvisible flag
                );


	$result = mysql_query($qry);

	if (!$result) 
	{
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $qry;
    die($message);
	}


	// Email Webmaster and contributor
	$from = 'webmaster@christchurchlye.org.uk';
	$headers  = 'From: ' . $from . "\r\n";
	$headers .= 'Reply-To: ' . $from . "\r\n";
	// PHP Bug/Feature  - "Bcc:" doesn't work reliably
	//$headers .= 'Bcc:' . $from . "\r\n";  

	$msg = "Your request for the forthcoming event\n[" . 
         $_POST['feName'] . ' on ' . $eventDate . 
				 "]\nto be posted on the Christ Church Website has been actioned.\n" .
         "The event will have HTML markup inserted to ensure that the layout\n" .
         "is consistent with other forthcoming events, once this has been done\n" . 
         "you will be emailed to notify you that the event is now visible on the website\n";	

	$subject = 'Request for Forthcoming Event';

	$mail1 = mail(	$_POST['feEmail1'],
					  $subject,
						$msg,
						$headers);

	$msg .= "\n\nCopy of email sent to [" . $_POST['feEmail1'] . "] regarding\n" .
          "the creation of a new forthcoming event.\n"; 

	$subject  = "Webmaster copy of: " . $subject;
	$headers  = 'From: ' . $from . "\r\n";
	$headers .= 'Reply-To: ' . $_POST['feEmail1'] . "\r\n";

	$mail2 = mail($from,
					  $subject,
						$msg,
						$headers);

	if ( $mail1 && $mail2 )
	{
		echo "<h4>Confirmation Email Sent to " . $_POST['feEmail1'] . "</h4> \n";
		echo "<br /> \n";
		echo "<br /> \n";				
	}
	else
	{
		echo "<h2>Message was not sent</h2>\n";
		echo "Mailer Error <br />\n";
		echo "<br /> \n";
		echo "To " . $_POST['emailrecipient'];
		echo "<br /> \n";
		echo "Subject " . $_POST['emailsubject'];
		echo "<br /> \n";
		echo "message " . $msg;
		echo "<br /> \n";							
		echo "Headers " . $headers;
		echo "<br /> \n";	
		echo "<br /> \n";	
	}




}


// NOTE the else causes the form to be produced
else
{
?>

<h4>
What to include
</h4>

<ul>
	<li>
	Date and Optional Time of the Event.
	</li>

	<li>
	A name for the Event.
	</li>

	<li>
	In the Event Description text area provide.
		<ul>

			<li>
			A description of the event.
			</li>

	
			<li>
			The location of the event.
			</li>

		</ul>

	</li>

	<li>
			Name of a contact that will handle enquiries regarding the event.
	</li>
	
	<li>
		 Email address of a contact that will handle enquiries regarding the event.
	</li>

	<li>
		 Optional telephone number that people interested in the event can use to obtain further details.
	</li>


	<li>
		An email address for the contributor of the forthcoming event.
    The email has to be confirmed by entering it twice.
    <br />
    A confirmation that the request has been processed
    will be emailed to this address.
	</li>
	

<ul>





<form name="feInputForm" id="feInputForm" action="openfcform.php" 
			onsubmit="return feValidateForm(objDate)"  method="post">


<table class="feForm">

<tr class="feForm">

	<td>
		Specify Event Time: 
	</td>

	<td>
		<input type="checkbox" 
					 id='feChkEventTime' name="feChkEventTime" value="feChkEventTime" checked
					 alt="Include a Time for the Event" 
					 title="Include a Time for the Event"
					 onclick="enableDisableTimeCapture('feChkEventTime', 'feHours', 'feMinutes', 'feDesc')"  
		>
	</td>
</tr>

<tr class="feForm">

	<td>
		Event Time: 
	</td>

	<td>		
		&nbsp;
		<SELECT name="feHours" id="feHours"
						alt="Hours" 
						title="Hours"
		>
			<?php
			for ($i=0; $i<24; $i++)
			{
				print("<OPTION>" . sprintf("%02s",$i) . "\n");
			}
			?>
		</SELECT>
		&nbsp;
		&nbsp;
		<SELECT name="feMinutes" id="feMinutes"
						alt="Minutes" 
						title="Minutes"
		>
			<?php
			for ($i=0; $i<60; $i++)
			{
				print("<OPTION>" . sprintf("%02s",$i) . "\n");
			}
			?>
		</SELECT>
	</td>
	<br />
</tr>


<tr class="feForm">
	<td>
		Event Date: 
	</td>

	<td>
		<br />
		<?php 
		print("<script>var objDate = DateInput('feEventDate', true, 'YYYY-MM-DD') </script>\n"); 
		print("<br />\n");
		?>
	</td>

</tr>

<tr class="feForm">
	<td>
		Event Name: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="feName" name="feName"
					 alt="Minimum Six characters" 
					 title="Minimum Six characters"
		>
	</td>
</tr>

<tr class="feForm">
	<td>
		Event Description: 
	</td>

	<td>
		<textarea name="feDesc" id="feDesc" rows="20" cols="60" value=""
						  alt="Minimum Ten characters" 
					 		title="Minimum Ten characters"
 		></textarea>
	</td>
</tr>


<tr class="feForm">
	<td>
		Event Contact Name: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="contactName" name="contactName"
					 alt="Name of Event Contact" 
					 title="Name of Event Contact"
		>
	</td>
</tr>


<tr class="feForm">
	<td>
		Event Contact Email: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="contactEmail" name="contactEmail"
					 alt="Email Address for Event Contact" 
					 title="Email Address for Event Contact"
		>
	</td>
</tr>


<tr class="feForm">
	<td>
		Event Contact Phone Number: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="contactPhone" name="contactPhone"
					 alt="Optional Event Contact Phone Number" 
					 title="Optional Event Contact Phone Number"
		>
	</td>
</tr>




<tr class="feForm">
	<td>
		Contributors Email: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="feEmail1" name="feEmail1"
					 alt="Contributor Email Address" 
					 title="Contributor Email Address"
		>
	</td>
</tr>


<tr class="feForm">

	<td>
		Confirm Contributors Email: 
	</td>

	<td>
		<input type="text" size="52" maxlength="100" value="" id="feEmail2" name="feEmail2"
					 alt="Confirmation of Contributor Email Address" 
					 title="Confirmation of Contributor Email Address"
		>
	</td>
</tr>


<tr class="feForm">
	<td>
	</td>

	<td>
		<input type="submit" name="submit" value="Submit New Event">
	</td>
</tr>

</table>


</form>

<?php
// NOTE closing brace of else clause used to produce form
}

?>



</div>  <!-- END <div class="innertube"> -->
</div>  <!-- END <div class="contentcolumn"> -->
</div>  <!-- END <div class="contentwrapper"> -->
<!-- ========================================================================== -->
<!--                            End Center Content Section                      --> 
<!-- ========================================================================== -->




<!-- ========================================================================== -->
<!--                          Start Left Column Content Section                 --> 
<!-- ========================================================================== -->
<div id="leftcolumn">
<div class="innertube">

<p>
<a href="http://<?php print(WEBSITE_DOMAIN); ?>" >Back to Main Site</a>
</p>

<p>
<!-- <a href="http://<?php print(WEBSITE_DOMAIN); ?>/admin/admin.php" >Site Maintenance Menu</a> -->
</p>


<form action="../index.php?displaypage=mailform.php" method="post"
			name="emailForm" id="emailForm">
<input type="hidden" name="emailrecipient" 
value="Webmaster@christchurchlye.org.uk">
<input type="submit" value="Email Webmaster">
</form>


<?php
// Only display form entry help if the form is being filled in
if (! isset($_POST['submit']))
{
?>


<p>
<h4>Specify Event Time</h4>

If this box is checked then the time specified below is assumed to be the time that
the event takes place.
<br />
If this box is not checked then any time specified below is ignored and no time is stored for 
the event.
</p>

<p>
<h4>Email Address</h4>

Your Email Address is needed as the information provided about the event is reviewed and
has HTML markup code manually inserted to make the appearance consistent with the
rest of the website.
<br />
If any queries arise regarding the Event Details then your email address serves as a
point of contact.

</p>

<?php
// End of  - Only display form entry help if the form is being filled in
}
?>


</div>  <!-- END <div class="innertube"> --> 
</div>  <!-- END <div id="leftcolumn"> -->
<!-- ========================================================================== -->
<!--                            End Left Column Content Section                 --> 
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                          Start Right Column Content Section                --> 
<!-- ========================================================================== -->
<div id="rightcolumn">
<div class="innertube">




</div> <!-- END <div class="innertube"> -->
</div> <!-- END <div id="rightcolumn"> -->
<!-- ========================================================================== -->
<!--                            End Right Column Content Section                --> 
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                          Start Footer Content Section                      --> 
<!-- ========================================================================== -->
<div class="footer">
<div class="innertube">


</div> <!-- END <div class="innertube"> -->
</div> <!-- END <div id="footer"> -->
<!-- ========================================================================== -->
<!--                            End Footer Content Section                      --> 
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->
</div>  <!-- END <div id="maincontainer"> -->



</body>

</html>
