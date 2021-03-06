<?php
	require_once "../globals.php";
	require_once("../mysql.php");
	require_once("../buildform.php");
	require_once("../rest-service/class.forthcomingevent.php");
	require_once("../class.socialmedia.php");

	$_POST['opcode'] = strtoupper($_POST['opcode']);

	switch ($_POST['opcode']) {
		case INSERT_REC:
			$pageTitle="Insert a new Forthcoming Event";
			break;
		case UPDATE_REC:
			$pageTitle="Update this existing Forthcoming Event";
			break;
		case DELETE_REC:
			$pageTitle="Delete this Forthcoming Event";
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

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <!-- End CSS Includes -->


</head>
<body style="margin-top:20px; margin-left:20px;">

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">


<!-- ------------------ HEADER --------------------------------------- -->
<?php
require_once('topbanner.php');

print "<H3>$pageTitle</H3>\n";
?>
<hr />
<!-- ---------------- END HEADER ------------------------------------- -->


<?php

/*********************
 Validate fields to be written to row

 ****************************************/
function validate()
{
	$oEvent = new ForthcomingEvent();

	$timeObj = new mysqltime;
	$dateObj = new mysqldate;
	$result = $oEvent->validate($_POST);

	/*****************************************
	CDN 17/4/11 This validation is now done in the validate method of the
							ForthcomingEvent Class.

	The loop below is called unconditionally because when returning a warning alone the validate
	method return true.
	********************************************/
	foreach($oEvent->getErrors() as $arr)
	{
		switch ($arr[ForthcomingEvent::ERROR_TYPE])
		{
			case (ForthcomingEvent::VALIDATION_ERROR) :
				dispError($arr[ForthcomingEvent::ERROR_MSG]);
				break;

			case (ForthcomingEvent::VALIDATION_WARNING) :
				dispWarn($arr[ForthcomingEvent::ERROR_MSG]);
				break;

			default:

		}

	}

	return $result;
}




/********************
 Add/Amend/Delete SQL
 ***********************/
function doSQL($mysqli) {

	$dateObj = new mysqldate;

	$eventdate = $dateObj->fmtDateTime($dateObj->UK_DATE, $_POST['eventdate'], $_POST['eventtime']);


	switch ($_POST['opcode']) {
		case INSERT_REC:
			$stmnt = sprintf("INSERT INTO forthcomingevents
												(eventdate,eventname,eventdesc,
                         contribemail, contactname, contactphone, contactemail,
                         isvisible, linkurl, tempfiles,
                         twittertext, sendtotwitter, sendtofacebook, sendtogoogleplus,
                         senttosocialmedia, lastupdated
                        )
						VALUES ('%s', '%s', '%s',
                                '%s', '%s', '%s', '%s',
                                '%s', '%s', '%s'
                               )",
						mysql_real_escape_string($eventdate),
						mysql_real_escape_string($_POST['eventname']),
						mysql_real_escape_string($_POST['eventdesc']),
						mysql_real_escape_string($_POST['contribemail']),
						mysql_real_escape_string($_POST['contactname']),
						mysql_real_escape_string($_POST['contactphone']),
						mysql_real_escape_string($_POST['contactemail']),
						mysql_real_escape_string($_POST['isvisible']),
						mysql_real_escape_string($_POST['linkurl']),
						mysql_real_escape_string($_POST['tempfiles']),
                        mysql_real_escape_string($_POST['twittertext']),
                        mysql_real_escape_string($_POST['sendtotwitter']),
                        mysql_real_escape_string($_POST['sendtofacebook']),
                        mysql_real_escape_string($_POST['sendtogoogleplus']),
                        mysql_real_escape_string($_POST['senttosocialmedia']),
                        date('Y-m-d H:i:s')
											);
			$msg = "New record created OK";
			break;

		case UPDATE_REC:
			$stmnt = sprintf("UPDATE forthcomingevents
											SET eventdate = '%s',eventname = '%s',eventdesc='%s',
                      contribemail = '%s', contactname = '%s',
                      contactphone = '%s', contactemail = '%s',
                      isvisible = '%s', linkurl = '%s', tempfiles = '%s',
                      twittertext = '%s',
                      sendtotwitter = '%s',
                      sendtofacebook = '%s',
                      sendtogoogleplus = '%s',
                      senttosocialmedia = '%s',
                      lastupdated = '%s'
					WHERE id=%d",
					mysql_real_escape_string($eventdate),
					mysql_real_escape_string($_POST['eventname']),
					mysql_real_escape_string($_POST['eventdesc']),
					mysql_real_escape_string($_POST['contribemail']),
					mysql_real_escape_string($_POST['contactname']),
					mysql_real_escape_string($_POST['contactphone']),
					mysql_real_escape_string($_POST['contactemail']),
					mysql_real_escape_string($_POST['isvisible']),
					mysql_real_escape_string($_POST['linkurl']),
					mysql_real_escape_string($_POST['tempfiles']),
                    mysql_real_escape_string($_POST['twittertext']),
                    mysql_real_escape_string($_POST['sendtotwitter']),
                    mysql_real_escape_string($_POST['sendtofacebook']),
                    mysql_real_escape_string($_POST['sendtogoogleplus']),
                    mysql_real_escape_string($_POST['senttosocialmedia']),
                    //mysql_real_escape_string($_POST['lastupdated']),
                    date('Y-m-d H:i:s'),
					$_POST['id']
					);

			$msg = "Record updated OK";
			break;

		case DELETE_REC:
			$stmnt = sprintf("DELETE FROM forthcomingevents
												WHERE id=%d",
												$_POST['id']
												);
			$msg = "Record Deleted OK";
			break;

		default:
			break;
	}


	if (mysql_query($stmnt))
	{
		if ($_POST['opcode'] == INSERT_REC)
		{
			$_POST['id'] = mysql_insert_id();
		}

		setLastupdatedDate( date('d/m/Y'), date('H:i'));
		touchMenuItem("forthcoming events");
		touchMenuItem("whats on calendar");
		print("<p>" . $msg . "</p>\n");

		$msgSocial = "";

		if (isset($_POST['opcode']) &&
			( ($_POST['opcode'] == INSERT_REC) || ($_POST['opcode'] == UPDATE_REC) ) )
		{
			if (empty($_POST['twittertext']))
			{
				print("<p>Twitter message is empty - cannot send an empty message</p>");
			}
			else
			{
				$oSocialMedia = new SocialMedia(
							$_POST[SocialMedia::CHKBOX_TWITTER] == "YES" ? true : false,
							$_POST[SocialMedia::CHKBOX_FACEBOOK] == "YES" ? true : false,
							$_POST[SocialMedia::CHKBOX_GOOGLEPLUS] == "YES" ? true : false );

				$cursorRow = $oSocialMedia->setMenuPrompt("forthcoming events");
				$msgSocial = $_POST['twittertext'] . " " . $oSocialMedia->getShortUrl();


				$oSocialMedia->exec($msgSocial);
				print("<p>" . $msgSocial . "</p>\n");
				//print_r($_POST);
			}
		}
		else
		{
			print("<p>Social Media not sent.<br />" . print_r($_POST) . "</p>");
		}


	}
	else {
		print("<p>" . mysql_error() . "</p>\n");
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

	$args = "";
	if (validate()){
		doSQL($dbHandle);
	}
	else {
		$args = http_build_query(array('opcode' => $_POST['opcode']) + array('id' => $_POST['id']));
		print("<p>Return to Forthcoming Event <a href=\"editforthevents.php?$args\">Edit Page</a></p>\n");
	}


	print("<p>Return to <a href=\"bforthevent.php?$args\">Forthcoming Events</a>" .
		    "&nbsp&nbsp<a href=\"index.php?$args\">Admin</a>" .
				"</p>\n");


?>

<!-- ------------------ FOOTER --------------------------------------- -->
<?php
	require_once('footer.php');
?>
<!-- ---------------- END FOOTER ------------------------------------- -->


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>

