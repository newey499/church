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



<?php

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
	if ((!isset($_POST['eventdate'])) || (! $dateObj->checkUKdate($_POST['eventdate']))) {
		dispError($_POST['eventdate'] . " is not a valid date");
		$result = false;
	}

	//event time must be a valid time
	if ((!isset($_POST['eventtime'])) || (! $timeObj->checkTime($_POST['eventtime']))) {
		dispError($_POST['eventtime'] . " is not a valid time");
		$result = false;
	}
	
	//eventname may not be blank
	if ((!isset($_POST['eventname'])) || (!is_string($_POST['eventname'])) || (trim($_POST['eventname']) == "")) {
		dispError("Event Name may not be blank");		
		$result = false;	
	}
	
	//eventdesc may or may not not be blank issue warning if blank
	if ((!isset($_POST['eventdesc'])) || (!is_string($_POST['eventdesc'])) || (trim($_POST['eventdesc']) == "")) {
		dispWarn("Warning : Event Description is blank");		
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
                         isvisible, linkurl 
                        )
												VALUES ('%s', '%s', '%s',
                                '%s', '%s', '%s', '%s',
                                '%s', '%s'
                               )",
												mysql_real_escape_string($eventdate),
												mysql_real_escape_string($_POST['eventname']),																
												mysql_real_escape_string($_POST['eventdesc']),									
												mysql_real_escape_string($_POST['contribemail']),	
												mysql_real_escape_string($_POST['contactname']),	
												mysql_real_escape_string($_POST['contactphone']),	
												mysql_real_escape_string($_POST['contactemail']),
												mysql_real_escape_string($_POST['isvisible']),
												mysql_real_escape_string($_POST['linkurl'])
											); 
			$msg = "New record created OK"; 
			break;
			
		case UPDATE_REC:		
			$stmnt = sprintf("UPDATE forthcomingevents 
											SET eventdate = '%s',eventname = '%s',eventdesc='%s',
                      contribemail = '%s', contactname = '%s', 
                      contactphone = '%s', contactemail = '%s',
                      isvisible = '%s', linkurl = '%s'  
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
    setLastupdatedDate( date('d/m/Y'), date('H:i'));
		touchMenuItem("forthcoming events");
		touchMenuItem("whats on calendar");
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

class sqlForthEvents
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
			print("<p>Return to Forthcoming Event <a href=\"editforthevents.php?$args\">Edit Page</a></p>\n");		
		}

		
		print("<p>Return to <a href=\"bforthevent.php?$args\">Forthcoming Events</a>" . 
				  "&nbsp&nbsp<a href=\"admin.php?$args\">Admin</a>" . 	
					"</p>\n");

	}
}

$obj = new sqlForthEvents();
	
require('smartysetup.php');

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);

$smarty->assign_by_ref('updateObject', $obj);

$smarty->display('sqlforthevents.tpl');


?>


