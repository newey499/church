<?php
/******************************************************

fcform.php

User friendly add a new forthcoming event form

NOTE SEND TO SOCIAL MEDIA DISABLED DURING TESTING

Modification History
====================

Date		Programmer			Description
20/05/2012	CDN			Don't nned contributors email anymore so just have as hidden fields
*******************************************************/


	require_once("../dbconnectparms.php");
	require_once("../globals.php");
	require_once("../genlib.php");
	require_once("../mysql.php");
	require_once("../dumpquerytotable.php");
	require_once("../class.cdnmail.php");
	require_once("../mysqldatetime.php");
	require_once("../nusoap.php");
	require_once("../rest-service/class.sermonstalks.php");
	require_once("../class.socialmedia.php");
  // How to force a redirect
  //Header("Status: 302");
  //Header("Location: http://www.google.co.uk");


	/******************************
		Start a session. This must be the very first thing done on the page.
	**********************************/
	require_once("../session.php");
	$oSession = new session();

	/* Connect to a MySQL server */
	$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');



?>
<!DOCTYPE html>
<html>
<head>



  <title>Christ Church, Lye, West Midlands UK</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />




  <meta name="keywords" content="christ, church, lye, cofe, evangelical, england" />

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <!-- End CSS Includes -->

  <!-- Javascript Includes -->
  <script type="text/javascript" src="../jscript/church.js" ></script>
  <script type="text/javascript" src="../jscript/genlib.js"></script>
  <script type="text/javascript" src="../jscript/calendarDateInput.js"></script>

  <!-- <script type="text/javascript" src="../jscript/javaxhtml1-0.js" ></script> -->
  <!-- <script type="text/javascript" src="../jscript/jquery-1.2.6.min.js" ></script> -->
  <!-- <script type="text/javascript" src="../jscript/chromejs/chrome.js" ></script> -->
  <!-- End Javascript Includes -->

	<script>
		// CDN 18/01/2009
		// Global variable to hold the Date Object to make it available during validation
		var objDate;
		var oChangeCol = new changeColor('p2', 'red');
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

<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>

<!-- ========================================================================== -->
<!--                               End Top Section                              -->
<!-- ========================================================================== -->


<noscript>
<p>
<em>
Either your browser does not support JavaScript or Javascript has been disabled.
This page requires a Javascript enabled Browser.
</em>
</p>
</noscript>


<br />

<h2>
Add a New Forthcoming Event
</h2>


<p>
	<a href="http://<?php print(WEBSITE_DOMAIN); ?>" >Main Site</a>
	&nbsp;
	&nbsp;
	<a href="http://<?php print(WEBSITE_DOMAIN); ?>/useradmin/index.php" >Site Maintenance Menu</a>
</p>

<?php
if (isset($_POST['submit']))
{


	if ($_POST['feChkEventTime'] == 'feChkEventTime')
	{
		//print("Time WILL be included");
	}
	else
	{
		//print('Time will NOT be included - mapped to "11:11"');
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
									"   contactname, contactphone, contactemail, isvisible, " .
									"   twittertext, sendtotwitter, sendtofacebook, sendtogoogleplus, " .
									"   senttosocialmedia, lastupdated, created " .
									" ) " .
									"VALUES " .
									" ( " .
									"   '%s', '%s', '%s', '%s', " .
									"   '%s', '%s', '%s', '%s', " .
									"   '%s', '%s', '%s', '%s', " .
									"   '%s', '%s', '%s' " .
									" ) ",
				  $eventDate,
                  mysql_real_escape_string(strip_html_tags($_POST['feName'])),
                  mysql_real_escape_string(strip_html_tags($_POST['feDesc'])),
                  mysql_real_escape_string(strip_html_tags($_POST['feEmail1'])),
				  mysql_real_escape_string(strip_html_tags($_POST['contactName'])),
                  mysql_real_escape_string(strip_html_tags($_POST['contactPhone'])),
                  mysql_real_escape_string(strip_html_tags($_POST['contactEmail'])),
                  'YES',   // isvisible flag
                  mysql_real_escape_string(strip_html_tags($_POST['twittertext'])),
                  (isset($_POST['sendtotwitter']) ? "YES": "NO"),
                  (isset($_POST['sendtofacebook']) ? "YES": "NO"),
                  (isset($_POST['sendtogoogleplus']) ? "YES": "NO"),
                  'NO',
                  date('Y-m-d H:i:00'),
                  date('Y-m-d H:i:00')
                );


	$result = mysql_query($qry);

	if (!$result)
	{
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $qry;
		die($message);
	}
	else
	{
		// Get the primary key (autoincrement column "id")
		if (! $cursor = mysql_query("SELECT LAST_INSERT_ID() as newId"))
		{
			die("Failed to get Id of just created forthcoming event");
		}

		$row = mysql_fetch_assoc($cursor);
		$newId = $row['newId'];

		print("<p>");
		print("<h4>Event [" . $_POST['feName'] . "] to take place on [" .
		       $eventDate . "] Added.<br /> new Row ID [" . $newId . "]</h4>");
		print("</p>");

		if ( isset($_POST['sendtotwitter']) ||
             isset($_POST['sendtofacebook']) ||
             isset($_POST['sendtogoogleplus']) )
        {
			print("<p>");
			print("<h4>Contacting Social Media Sites</h4>");
			print("</p>");

			/*****************
			Contact Twitter et al
			*************************/
			touchMenuItem("sermons and talks");
			$oSocialMedia = new SocialMedia(
						isset($_POST['sendtotwitter']) ? true : false,
						isset($_POST['sendtofacebook']) ? true : false,
						isset($_POST['sendtogoogleplus']) ? true : false );

		    $dt = new DateTime($eventDate);
        $dtStr = date_format($dt, 'g:ia D jS M Y'); // Eg 9:45am Mon 7th Jun 2012

			$cursorRow = $oSocialMedia->setMenuPrompt("forthcoming events");
			//$msg = $oSocialMedia->getMenuPrompt() .
			$msg =  $_POST['twittertext'] .
					": " . $dtStr .
					": " . $oSocialMedia->getShortUrl();

			/****************************************
			Don't do the send during testing
			print("<h4>Development Testing $oSocialMedia->exec($msg); DISABLED</h4>");
			*****************************************/
			$oSocialMedia->exec($msg);

			print("<p>" . $msg . "</p>\n");


			$qry = ' update forthcomingevents ' .
				   ' set senttosocialmedia = "YES", ' .
			       '     lastupdated = "' . date('Y-m-d H:i:00') . '" ' .
			       ' where id = ' . $newId;

			if (! mysql_query($qry) )
			{
				die(mysql_error() . " [" . $qry . "]");
			}

			print("<p>");
			print("<h4>Sent to Social Media Sites</h4>");
			print("</p>");

			// Update the website timestamp
			setLastupdatedDate( date('d/m/Y'), date('H:i'));
        }



		/*************************************************

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

		**************************************************************/

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


<ul>





<form name="feInputForm" id="feInputForm" class="feInputForm" action="fcform.php"
			onsubmit="return feValidateForm(objDate)"  method="post">


<table class="feform">


<tr class="feform">
	<td class="feform">
		Specify Event Time:
	</td>

	<td class="feform">
		<input type="checkbox"
					 id='feChkEventTime' name="feChkEventTime" value="feChkEventTime" checked
					 alt="Include a Time for the Event"
					 title="Include a Time for the Event"
					 onclick="enableDisableTimeCapture('feChkEventTime', 'feHours', 'feMinutes', 'feDesc')"
		>
	</td>
</tr>

<tr class="feform">

	<td class="feform">
		Event Time:
	</td>

	<td class="feform">
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


<tr class="feform">
	<td class="feform">
		Event Date:
	</td>

	<td class="feform">
		<br />
		<?php
		print("<script>var objDate = DateInput('feEventDate', true, 'YYYY-MM-DD')</script>\n");
		print("<br />\n");
		?>
	</td>

</tr>

<tr class="feform">
	<td class="feform">
		Event Name:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="feName" name="feName"
					 alt="Minimum Six characters"
					 title="Minimum Six characters"
		>
	</td>
</tr>

<tr class="feform">
	<td class="feform">
		Event Description:
	</td>

	<td class="feform">
		<textarea name="feDesc" id="feDesc" rows="20" cols="60" value=""
						  alt="Minimum Ten characters"
					 		title="Minimum Ten characters"
 		></textarea>
	</td>
</tr>


<tr class="feform">
	<td class="feform">
		Event Contact Name:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="contactName" name="contactName"
					 alt="Name of Event Contact"
					 title="Name of Event Contact"
		>
	</td>
</tr>


<tr class="feform">
	<td class="feform">
		Event Contact Email:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="contactEmail" name="contactEmail"
					 alt="Email Address for Event Contact"
					 title="Email Address for Event Contact"
		>
	</td>
</tr>


<tr class="feform">
	<td class="feform">
		Event Contact Phone Number:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="contactPhone" name="contactPhone"
					 alt="Optional Event Contact Phone Number"
					 title="Optional Event Contact Phone Number"
		>
	</td>
</tr>



<!-- ========================================================================= -->
<!-- CDN 20/05/2012                                                            -->
<!-- Don't nned contributors email anymore so just have as hidden fields       -->
<!-- ========================================================================= -->
<input type="hidden" size="52" maxlength="100" value="" id="feEmail1" name="feEmail1"
				alt="Contributor Email Address"
				title="Contributor Email Address"
>

<input type="hidden" size="52" maxlength="100" value="" id="feEmail2" name="feEmail2"
				alt="Confirmation of Contributor Email Address"
				title="Confirmation of Contributor Email Address"
>
<!--
<tr class="feform">
	<td class="feform">
		Contributors Email:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="feEmail1" name="feEmail1"
					 alt="Contributor Email Address"
					 title="Contributor Email Address"
		>
	</td>
</tr>


<tr class="feform">
	<td class="feform">
		Confirm Contributors Email:
	</td>

	<td class="feform">
		<input type="text" size="52" maxlength="100" value="" id="feEmail2" name="feEmail2"
					 alt="Confirmation of Contributor Email Address"
					 title="Confirmation of Contributor Email Address"
		>
	</td>
</tr>
-->
<!-- ================================================================== -->



<tr class="feform">

	<td class="feform">
		Tweet Text:
	</td>
	<td class="feform">
		<input type="text" size="80" maxlength="140"
			   placeholder="enter the text for the Tweet of this event (max 140 characters)"
					 id='twittertext' name="twittertext" value=""
					 alt="Tweet text"
					 title="Tweet text"
		>
	</td>
</tr>

<tr class="feform">

	<td class="feform">
		Send to Twitter:
	</td>
	<td class="feform">
		<input type="checkbox"
					 id='sendtotwitter' name="sendtotwitter" value="YES"
					 alt="Send this event to Twitter"
					 title="Send this event to Twitter"
		>
	</td>
</tr>

<tr class="feform">
	<td class="feform">
		Send to Facebook:
	</td>
	<td class="feform">
		<input type="checkbox"
					 id='sendtofacebook' name="sendtofacebook" value="YES"
					 alt="Send this event to Facebook"
					 title="Send this event to Facebook"
		>
	</td>
</tr>

<tr class="feform">
	<td class="feform">
		Send to Google+:
	</td>
	<td class="feform">
		<input type="checkbox"
					 id='sendtogoogleplus' name="sendtogoogleplus" value="YES"
					 alt="Send this event to Google Plus"
					 title="Send this event to Google Plus"
		>
	</td>
</tr>


<tr class="feform">
	<td class="feform">
	</td>

	<td class="feform">
		<input type="submit" name="submit" value="Submit New Event">
	</td>
</tr>

</table>


</form>

<?php
// NOTE closing brace of else clause used to produce form
}

?>





<!-- ========================================================================== -->
<!--                          Start Footer Content Section                      -->
<!-- ========================================================================== -->
<div class="footer">
<div class="innertube">

<p>
	<a href="http://<?php print(WEBSITE_DOMAIN); ?>" >Main Site</a>
	&nbsp;
	&nbsp;
	<a href="http://<?php print(WEBSITE_DOMAIN); ?>/useradmin/index.php" >Site Maintenance Menu</a>
</p>

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
