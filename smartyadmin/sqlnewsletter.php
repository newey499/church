<?php
	require_once "../globals.php";
	require_once("../mysql.php");
	require_once("../buildform.php");
	require_once("../emailxmit.php");
	/******************************
		Start a session. This must be the very first thing done on the page.

		The session Classes constructor also opens the database.
	**********************************/
	require_once("../session.php");
	$oSession = new session();


	$_POST['opcode'] = strtoupper($_POST['opcode']);

	switch ($_POST['opcode']) {
		case INSERT_REC:
			$pageTitle="Insert a new Newsletter entry";
			break;
		case UPDATE_REC:
			$pageTitle="Update this Newsletter entry";
			break;
		case DELETE_REC:
			$pageTitle="Delete this Newsletter Entry";
			break;
		case NEWSLETTER_PUBLISH:
			$pageTitle="Publish this Newsletter";
			break;

		default:
			$pageTitle="Unexpected opcode " . $_POST['opcode'];
			break;
	}



/*********************
 Validate fields to be written to row

 ****************************************/
function validate() {

	$dateObj = new mysqldate;
	$result = true;
	$fnameEmpty;
	$txtEmpty;

	// If we are going to delete it we don't care if its valid
	if ($_POST['opcode'] == DELETE_REC) {
		return True;
	}

	//event date must be a valid date
	if ((!isset($_POST['pubdate'])) || (! $dateObj->checkUKdate($_POST['pubdate']))) {
		dispError($_POST['pubdate'] . " is not a valid date");
		$result = false;
	}


	//eventname may not be blank
	if ((!isset($_POST['title'])) || (!is_string($_POST['title'])) || (trim($_POST['title']) == "")) {
		dispError("Title may not be blank");
		$result = false;
	}

	//eventdesc may or may not not be blank issue warning if blank
	$fnameEmpty =  ((!isset($_POST['filename'])) || (!is_string($_POST['filename'])) || (trim($_POST['filename']) == ""));

	//eventdesc may or may not not be blank issue warning if blank
	$txtEmpty =  ((!isset($_POST['newslettertext'])) || (!is_string($_POST['newslettertext'])) || (trim($_POST['newslettertext']) == ""));

	if ($fnameEmpty && $txtEmpty)
	{
		dispWarn("Filename or text must be supplied");
		$result = false;
	}


	return $result;
}




/********************
 Add/Amend/Delete SQL
 ***********************/
function doSQL($mysqli) {

	$dateObj = new mysqldate;

	$stampdate = $dateObj->fmtDateTime($dateObj->UK_DATE, $_POST['pubdate']);

	switch ($_POST['opcode']) {
		case INSERT_REC:
			$stmnt = sprintf("INSERT INTO newsletters
												(emailed,publicationdate,title,filename,newslettertext)
												VALUES ('%s', '%s', '%s', '%s', '%s')",
												mysql_real_escape_string($_POST['emailed']),
												mysql_real_escape_string($stampdate),
												mysql_real_escape_string($_POST['title']),
												mysql_real_escape_string($_POST['filename']),
												mysql_real_escape_string($_POST['newslettertext'])
												);
			$msg = "New record created OK";
			break;

		case UPDATE_REC:
			$stmnt = sprintf("UPDATE newsletters
												SET emailed = '%s', publicationdate = '%s', title = '%s',
												    filename ='%s', newslettertext = '%s'
												WHERE id=%d",
												mysql_real_escape_string($_POST['emailed']),
												mysql_real_escape_string($stampdate),
												mysql_real_escape_string($_POST['title']),
												mysql_real_escape_string($_POST['filename']),
												mysql_real_escape_string($_POST['newslettertext']),
												$_POST['id']
												);
			$msg = "Record updated OK";
			break;

		case DELETE_REC:
			$stmnt = sprintf("DELETE FROM newsletters
											 WHERE id=%d",
											 $_POST['id']
											);

			$msg = "Record Deleted OK";
			break;

		case NEWSLETTER_PUBLISH:
			publishNewsLetter($_POST['id']);
			return;
			break;



		default:
			break;
	}

	if (mysql_query($stmnt)) {
		print("<p>" . $msg . "</p>\n");
	}
	else {
		print("<p>" . mysql_error() . "</p>\n");
	}

	return;
}



function publishNewsLetter($id)
{
	$oEmail;

	$qryNl = " SELECT id, publicationdate, title, filename, newslettertext, " .
	         " emailed, DATE_FORMAT(publicationdate,'%d/%m/%Y') as pubdate " .
	         " FROM newsletters ";
	$qryNl = $qryNl . sprintf(" WHERE id = %d", $id);

	$qrySubscriber = sprintf(" SELECT id, email, nickname, emailtype, password, status " .
	                         " FROM subscribers " .
									         " WHERE status = '%s' ",
									         "Current");


	if (! ($resultNl = mysql_query($qryNl) ))
	{
		print("<p>" . mysql_error() . "</p>\n");
		return false;
	}

	$newsletterRow = mysql_fetch_assoc($resultNl);
	print("<p>" . "Newsletter Title: " . $newsletterRow['title'] . "</p>\n");

	if (! ($resultSub = mysql_query($qrySubscriber) ))
	{
		mysql_free_result($resultNl);
		print("<p>" . mysql_error() . "</p>\n");
		return false;
	}

	// File takes priority over blob text
	if (! empty($newsletterRow['filename']) )
	{
	 	if (! file($newsletterRow['filename']) )
		{
			print "Error: Newsletter Content File [" . $newsletterRow['filename'] . "] does not exist<br />";
			return false;
		}
		else
		{
			$text = file_get_contents( $newsletterRow['filename'] );
		}
	}
	else
	{
		$text = $newsletterRow['newslettertext'];
	}

	$oEmail = new emailNewsletter( $text,
	                               $text,
																 $newsletterRow['pubdate'],
																 $newsletterRow['title']
															 );

	echo "<p>";
	echo "Sending Newsletter to:- <br />";
	while ($row = mysql_fetch_assoc($resultSub))
	{
		if (! $oEmail->exec($row['email'], $row['emailtype']))
		{
			echo "Failed to send email to " . $row['email'] . " Type " .  $row['emailtype'] . "<br />";
		}
		else
		{
   		echo $row['emailtype'] . " " . $row['email'] . " Type " .  $row['emailtype'] . "<br />";
		}
	}
	echo "</p>";

	mysql_free_result($resultNl);
	mysql_free_result($resultSub);

	$qryUpdate = sprintf(" UPDATE newsletters " .
	                     " SET emailed = 'Yes' " .
	                     " WHERE id = %d", $id);

	if (! mysql_query($qryUpdate))
	{
		print "<p>";
		print "Failed to update Newsletter Published Status";
		print "</p>";
		return;
	}


	print "<p>";
	print "Newsletter Published OK";
	print "</p>";
	return true;
}

?>

<?php
	/*****************
	 Main Processing
	 **********************/
class sqlNewsletter
{
	function __construct()
	{

	}
	function __destruct()
	{

	}

	public function exec()
	{
		$_POST['opcode'] = strtoupper($_POST['opcode']);

		/* Connect to a MySQL server */
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


		if (validate()){
			doSQL($dbHandle);
		}
		else {
			$args = http_build_query(array('opcode' => $_POST['opcode']) + array('id' => $_POST['id']));
			print("<p>Return to Newsletter entries <a href=\"editnewsletter.php?$args\">Edit Page</a></p>\n");
		}


		print("<p>Return to <a href=\"bnewsletter.php?$args\">Newsletter Entries</a>" .
					"&nbsp&nbsp<a href=\"admin.php?$args\">Admin</a>" .
					"</p>\n");

	}

}


$obj = new sqlNewsletter();

require('smartysetup.php');

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);

$smarty->assign_by_ref('updateObject', $obj);

$smarty->display('sqlnewsletter.tpl');


?>


