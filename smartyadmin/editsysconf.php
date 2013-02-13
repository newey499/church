<?php
	/******************************
	  Need to include classes that are passed in $_SESSION
	  before instantiating the session
	  ********************************************/
	require_once("../buildform.php");
	require_once "../globals.php";
	require_once("../mysql.php");
	require_once("../genlib.php");

	/******************************
		Start a session. This must be the very first thing done on the page.
	**********************************/
	require_once("../session.php");
	$oSession = new session();

	require_once('smartysetup.php');

	$pageTitle = "System Configuration";

// Returns a form object set up as required
function buildFormObject() {

	// If the object isn't in the session then create the object
	// and stash it in the session. If its an insert or a delete always
	// force a new object
	if	($_GET['opcode'] == VALIDATE_WRITE_REC) {
		// There should already be an object stashed in the session
		$obj = $_SESSION['oMenus'];
	}
	else
  {
		// sysconf only ever has one row
    // lastupdated is not edited here - it has its own page
		// lastupdated.php
		$query = "SELECT	id,
											lastupdated,
								 			menutypes,
											showmenuhdr
						  FROM sysconf
							WHERE id = 1";

		$pkey = array('id'	=> 1);

		$obj = new buildForm($_GET['opcode'],$query,$pkey,$flds);

		$obj->addField('menutypes','Menu Type',
						($isInsert ? 'MENU_PAGE' : $_POST['menutypes']),
						'Select');
		$obj->addField('showmenuhdr','Menu Hdrs',
						($isInsert ? 'NO' : $_POST['showmenuhdr']),
						'Whether to display Menu Hdrs/Titles');

		//$obj->addMessageCol('contact','qwerty');
		//$obj->addMessageCol('town','TOWN');

		//$obj->addErrorMessageCol('mobile','mobile error');

		// $obj->submitTarget = "sqlorg.php";
		$args = http_build_query(array('opcode' => VALIDATE_WRITE_REC,
																	 'subcode' => $_GET['opcode']	));
		$obj->submitTarget = "editsysconf.php?$args";
		$obj->pkey = array('id' => $_GET['id']);

		//$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
		//$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page

		// Increase the size of the text entry area
		$obj->textarearows = 20;
		$obj->textareacols = 80;


		// Stash the object in the session
		//$_SESSION['oMenus'] = $obj;
	}

	return $obj;
}

/*********************
 Validate fields to be written to row
 ****************************************/
function validate($opcode)
{
	return true;
}



/********************
 Add/Amend/Delete SQL
 ***********************/
function doSQL($mysqli, $opcode, &$msg)
{


	switch ($opcode) {
		case INSERT_REC:
			$stmnt = sprintf("INSERT INTO sysconf
												(
													menutypes, showmenuhdr
												)
												VALUES
												(
													'%s', '%s'
												)",
												mysql_real_escape_string($_POST['menutypes']),
												mysql_real_escape_string($_POST['showmenuhdr'])
												);
			$msg .= "<h4>New record created OK</h4>";
			break;

		case UPDATE_REC:
			$stmnt = sprintf("UPDATE sysconf
												SET
													menutypes = '%s',
													showmenuhdr = '%s'
												WHERE id=%d",
												mysql_real_escape_string($_POST['menutypes']),
												mysql_real_escape_string($_POST['showmenuhdr']),
												$_POST['id']
												);
			$msg .= "<h4>Record updated OK</h4>";
			break;

		case DELETE_REC:
			$stmnt = sprintf("DELETE FROM sysconf
											 WHERE id=%d",
											 $_POST['id']
											);

			$msg .= "<h4>Record Deleted OK</h4>";
			break;
		default:
			break;
	}

	if (mysql_query($stmnt)) {
    setLastupdatedDate( date('d/m/Y'), date('H:i'));
		//print("<p>" . $msg . "</p>\n");
		return True;
	}
	else {
		$msg .= "<p>" . mysql_error() . "</p>";
		return False;
	}

}



/****************************************************************
 ****************************************************************

	Script Entry Point
	==================

 **************************************************************************
 **************************************************************************/

// if the $_POST array is filled then try and write the update
// otherwise display the form
if ($_POST['update'] == UPDATE_REC)
{
	$_GET['opcode'] = VALIDATE_WRITE_REC;
}
else
{
	$_GET['opcode'] = UPDATE_REC;
}



$obj = buildFormObject();


switch ($_GET['opcode']) {
	case INSERT_REC:
		$msg = "<h3>Insert new Sysconf</h3>";
		$msg .= "<h4>Not Supported Only ever one row on Sysconf table</h4>";
		//$obj->exec($opcode);
		break;

	case UPDATE_REC:
		$msg = "<h3>Update Sysconf</h3>";
		//$obj->exec($opcode);
		break;

	case DELETE_REC:
		$msg = "<h3>Delete Sysconf</h3>";
		$msg .= "<h4>Not Supported Must always be one row on Sysconf table</h4>";
		//$obj->exec($opcode);
		break;

	case VALIDATE_WRITE_REC:
		$msg = "<h3>Update Sysconf</h3>";
		/* Connect to a MySQL server */
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

		if (validate($_GET['subcode'])){
			if (doSQL($dbHandle, $_GET['subcode'], $msg)) {
				unset($_SESSION['oMenus']);
			}
		}
		else {
			$msg = "<h3>Problems with form entries indicated by " .
						 "<SPAN id=\"error\">*</SPAN>" .
						 "</h3>";
			$obj->refreshFromPost = True;
			$obj->exec($opcode);
		}
		break;

		default:
			die('Invalid option ['  . $opcode . '] passed to ' . $_SERVER['SCRIPT_NAME']);
			break;
}


$smarty = new Smarty();
$smarty->assign('_GET', $_GET);
$smarty->assign('title', $pageTitle);
$smarty->assign('msg', $msg);
$smarty->assign_by_ref('obj', $obj);
$smarty->display('editsysconf.tpl');




?>


