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

// Returns a form object set up as required
function buildFormObject() {

	// If the object isn't in the session then create the object
	// and stash it in the session. If its an insert or a delete always
	// force a new object
	if	($_GET['opcode'] == VALIDATE_WRITE_REC) {
		// There should already be an object stashed in the session
		$obj = $_SESSION['oMenus'];
	}
	else {

		$query = "SELECT	id,
											itemtype,
								 			menucol,
											itemgroup,
											itemorder,
											isvisible,
											prompt,
											target,
											content,
                      lastupdate
						  FROM menutitles ";

		if ((strcmp(strtoupper($_GET['opcode']), INSERT_REC) == 0)) {
			$isInsert = True;
			$query = $query . " LIMIT 0 OFFSET 1";  // This is an insert so only get one row for the meta information
		}
		else {
			$isInsert = False;
			$query = $query . " WHERE id = " . $_GET['id'];
		}


		$pkey = array('id'	=> $_GET['opcode']);

		$obj = new buildForm($_GET['opcode'],$query,$pkey,$flds);

/*******
		$obj->addField('menucol','Column',
						($isInsert ? 'LEFT' : $_POST['menucol']),
						'LEFT or RIGHT (Required)');
***********/
		$obj->addField('itemgroup','Group',
						($isInsert ? '0' : $_POST['itemgroup']),
						'Menu Group (Integer >= 0)');
/***************
		$obj->addField('itemorder','Order',
						($isInsert ? '0' : $_POST['itemorder']),
						'Item order within Group (Integer >= 0)');
*************/
		$obj->addField('isvisible','Visible',
						($isInsert ? 'YES' : $_POST['isvisible']),
						'Set to NO to hide');
		$obj->addField('prompt','Prompt',$_POST['prompt'],'Prompt for menu link (Required)');
		$obj->addField('target','Target',$_POST['adr3'],'Name of URL/file to link to');
/*******************
		$obj->addField('content','Content',$_POST['content'],
					'If a value is not entered for Target<br /> then the text entered here is displayed');
********************/

		//$obj->addMessageCol('contact','qwerty');
		//$obj->addMessageCol('town','TOWN');

		//$obj->addErrorMessageCol('mobile','mobile error');

		// $obj->submitTarget = "sqlorg.php";
		$args = http_build_query(array('opcode' => VALIDATE_WRITE_REC,
																	 'subcode' => $_GET['opcode']	));
		$obj->submitTarget = "editmenutitles.php?$args";
		$obj->pkey = array('id' => $_GET['id']);

		$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
		$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page
		$obj->addLink("bmenutitles.php","Menu Title Entries");	// Add a link to take us back to the Home page

		// Increase the size of the text entry area
		$obj->textarearows = 20;
		$obj->textareacols = 80;


		// Stash the object in the session
		$_SESSION['oMenus'] = $obj;
	}

	return $obj;
}

/*********************
 Validate fields to be written to row
 ****************************************/
function validate($opcode) {

	$timeObj = new mysqltime;
	$dateObj = new mysqldate;
	$result = true;

	// If we are going to delete it we don't care if its valid
	if ($opcode == DELETE_REC) {
		return True;
	}

	// menucol must be LEFT or RIGHT
/*********************
	if ((!isset($_POST['menucol'])) ||
			(!is_string($_POST['menucol'])) ||
			(! ( (strcmp($_POST['menucol'], 'LEFT') == 0) || (strcmp($_POST['menucol'], 'RIGHT') == 0) ) )
			) {
		$_SESSION['oMenus']->addErrorMessageCol('menucol','*');
		$result = false;
	}
	else {
		$_SESSION['oMenus']->delErrorMessageCol('menucol');
	}
********************/

	// isvisible must be YES or NO
	if ((!isset($_POST['isvisible'])) ||
			(!is_string($_POST['isvisible'])) ||
			(! ( (strcmp($_POST['isvisible'], 'YES') == 0) || (strcmp($_POST['isvisible'], 'NO') == 0) ) )
			) {
		$_SESSION['oMenus']->addErrorMessageCol('isvisible','*');
		$result = false;
	}
	else {
		$_SESSION['oMenus']->delErrorMessageCol('isvisible');
	}



	// Prompt may not be blank
	if ((!isset($_POST['prompt'])) || (!is_string($_POST['prompt'])) || (trim($_POST['prompt']) == "")) {
		$_SESSION['oMenus']->addErrorMessageCol('prompt','*');
		$result = false;
	}
	else {
		$_SESSION['oMenus']->delErrorMessageCol('prompt');
	}


	// Item Group must be numeric and >= 0
	if ((!isset($_POST['itemgroup'])) ||
			(!ctype_digit($_POST['itemgroup']))
		 ) {
		$_SESSION['oMenus']->addErrorMessageCol('itemgroup','*');
		$result = false;
	}
	else {
		$_SESSION['oMenus']->delErrorMessageCol('itemgroup');
	}


/*******************
	// Item order must be numeric and >= 0
	if ((!isset($_POST['itemorder'])) ||
			(!ctype_digit($_POST['itemorder']))
		 ) {
		$_SESSION['oMenus']->addErrorMessageCol('itemorder','*');
		$result = false;
	}
	else {
		$_SESSION['oMenus']->delErrorMessageCol('itemorder');
	}
****************/


	// Something must be in either target or content


	return $result;
}



/********************
 Add/Amend/Delete SQL
 ***********************/
class doSQL
{

 	function __construct()
 	{

 	}

  function __destruct()
 	{

 	}

	public function exec($mysqli, $opcode)
	{


		switch ($opcode) {
			case INSERT_REC:
				$stmnt = sprintf("INSERT INTO menutitles
													(	itemtype, itemgroup, isvisible,
														prompt,target,lastupdate
													)
													VALUES ('%s', %d, '%s',
																	'%s','%s', '%s')",
													'MENU_TITLE',
													mysql_real_escape_string($_POST['itemgroup']),
													mysql_real_escape_string($_POST['isvisible']),
													mysql_real_escape_string($_POST['prompt']),
													mysql_real_escape_string($_POST['target']),
													date('Y-m-d')
													);
				$msg = "New record created OK";
				break;

			case UPDATE_REC:
				$stmnt = sprintf("UPDATE menutitles
													SET
														itemtype = '%s',
														itemgroup = '%d',
														isvisible = '%s',
														prompt = '%s',
														target = '%s',
														lastupdate = '%s'
													WHERE id=%d",
													'MENU_TITLE',
													mysql_real_escape_string($_POST['itemgroup']),
													mysql_real_escape_string($_POST['isvisible']),
													mysql_real_escape_string($_POST['prompt']),
													mysql_real_escape_string($_POST['target']),
													date('Y-m-d'),
													$_POST['id']
													);
				$msg = "Record updated OK";
				break;

			case DELETE_REC:
				$stmnt = sprintf("DELETE FROM menutitles
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
			$smarty = new Smarty();
			$smarty->assign('message', $msg);
			$smarty->assign('back', "bmenutitles.php");
			$smarty->display('success.tpl');
			return True;
		}
		else
		{
			$smarty = new Smarty();
			$smarty->assign('message', mysql_error());
			$smarty->assign('back', "bmenutitles.php");
			$smarty->display('saved.tpl');
			return False;
		}

	}

}


function displayPage($pageTitle, $obj)
{

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);
$smarty->assign('opcode', $_GET['opcode']);
$smarty->assign_by_ref('obj', $obj);
$smarty->display('editmenutitles.tpl');

}


/****************************************************************
 ****************************************************************

	Script Entry Point
	==================

 **************************************************************************
 **************************************************************************/

$obj = buildFormObject();


switch ($_GET['opcode'])
{
	case INSERT_REC:
		$pageTitle = "Insert a new Menu Title Entry";
		displayPage($pageTitle, $obj);
		break;

	case UPDATE_REC:
		$pageTitle = "Update this existing Menu Title Entry";
		displayPage($pageTitle, $obj);
		break;

	case DELETE_REC:
		$pageTitle = "Delete this Menu Title Entry";
		displayPage($pageTitle, $obj);
		break;

	case VALIDATE_WRITE_REC:
		$pageTitle =  "Update Menu Title Table";

		if (validate($_GET['subcode']))
		{
			$oDoSQL = new doSQL();

			if ($oDoSQL->exec($dbHandle, $_GET['subcode'])) {
				unset($_SESSION['oMenus']);
				/**********************
				print "<SCRIPT LANGUAGE=JavaScript>\n";
				print "window.location.href =\"borgs.php\"\n";
				print "</SCRIPT>\n";
				***************************/
				buildMenuItem("admin.php","Admin");	// Add a link to take us back to the Admin page
				buildMenuItem("../index.php","Home");			// Add a link to take us back to the Home page
				buildMenuItem("bmenutitles.php","Menu Title Entries");	// Add a link to take us back to the Home page
			}
		}
		else {
			$pageTitle = "Problems with form entries indicated by " .
									 "<SPAN id=\"error\">*</SPAN>";

			$obj->refreshFromPost = True;

		}
		break;

		default:
			die('Invalid option ['  . $opcode . '] passed to ' . $_SERVER['SCRIPT_NAME']);
			break;
}


?>

