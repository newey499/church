<?php
	require_once "../globals.php";
	require_once("../mysql.php");
	require_once("../buildform.php");
	/******************************
		Start a session. This must be the very first thing done on the page.

		The session Classes constructor also opens the database.
	**********************************/
	require_once("../session.php");
	$oSession = new session();


	$_POST['opcode'] = strtoupper($_POST['opcode']);

	switch ($_POST['opcode']) {
		case INSERT_REC:
			$pageTitle="Insert a new Blog entry";
			break;
		case UPDATE_REC:
			$pageTitle="Update this existing Blog entry";
			break;
		case DELETE_REC:
			$pageTitle="Delete this Blog Entry";
			break;
		default:
			$pageTitle="Unexpected opcode " . $_POST['opcode'];
			break;
	}



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

	//event date must be a valid date
	if ((!isset($_POST['stampdate'])) || (! $dateObj->checkUKdate($_POST['stampdate']))) {
		dispError($_POST['stampdate'] . " is not a valid date");
		$result = false;
	}

	//event time must be a valid time
	if ((!isset($_POST['stamptime'])) || (! $timeObj->checkTime($_POST['stamptime']))) {
		dispError($_POST['stamptime'] . " is not a valid time");
		$result = false;
	}

	//eventname may not be blank
	if ((!isset($_POST['headline'])) || (!is_string($_POST['headline'])) || (trim($_POST['headline']) == "")) {
		dispError("Headline may not be blank");
		$result = false;
	}

	//eventdesc may or may not not be blank issue warning if blank
	if ((!isset($_POST['blogentry'])) || (!is_string($_POST['blogentry'])) || (trim($_POST['blogentry']) == "")) {
		dispWarn("Blog Entry may not be blank");
		$result = false;
	}

	return $result;
}




/********************
 Add/Amend/Delete SQL
 ***********************/
function doSQL($mysqli) {

	$dateObj = new mysqldate;

	$stampdate = $dateObj->fmtDateTime($dateObj->UK_DATE, $_POST['stampdate'], $_POST['stamptime']);


	switch ($_POST['opcode']) {
		case INSERT_REC:
			$stmnt = sprintf("INSERT INTO blog
												(stamp,headline,blogentry)
												VALUES ('%s', '%s', '%s')",
												mysql_real_escape_string($stampdate),
												mysql_real_escape_string($_POST['headline']),
												mysql_real_escape_string($_POST['blogentry'])
												);
			$msg = "New record created OK";
			break;

		case UPDATE_REC:
			$stmnt = sprintf("UPDATE blog
												SET stamp = '%s',headline = '%s',blogentry='%s'
												WHERE id=%d",
												mysql_real_escape_string($stampdate),
												mysql_real_escape_string($_POST['headline']),
												mysql_real_escape_string($_POST['blogentry']),
												$_POST['id']
												);
			$msg = "Record updated OK";
			break;

		case DELETE_REC:
			$stmnt = sprintf("DELETE FROM blog
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
class sqlBlogEvents
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

	if (validate()){
		doSQL($dbHandle);
	}
	else {
		$args = http_build_query(array('opcode' => $_POST['opcode']) + array('id' => $_POST['id']));
		print("<p>Return to Blog entries <a href=\"editblogevents.php?$args\">Edit Page</a></p>\n");
	}


	print("<p>Return to <a href=\"bblog.php?$args\">Blog Entries</a>" .
		    "&nbsp&nbsp<a href=\"admin.php?$args\">Admin</a>" .
				"</p>\n");

	}

}

$obj = new sqlBlogEvents();

require('smartysetup.php');

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);

$smarty->assign_by_ref('updateObject', $obj);

$smarty->display('sqlblog.tpl');



?>


