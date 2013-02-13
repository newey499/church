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


	$_GET['opcode'] = strtoupper($_GET['opcode']);

	switch ($_GET['opcode'])
	{
		case INSERT_REC:
			$pageTitle = "Insert a new Newsletter";
			break;
		case UPDATE_REC:
			$pageTitle = "Update this Newsletter Entry";
			break;
		case DELETE_REC:
			$pageTitle = "Delete this Newsletter";
			break;
		case NEWSLETTER_PUBLISH:
			$pageTitle = "Email this Newsletter to Subscribers";
			break;
		default:
			break;
	}


	$query = "SELECT	id,
										DATE_FORMAT(publicationdate,'%d/%m/%Y') as pubdate,
						 				title,
										filename,
						 				newslettertext,
										emailed
        		FROM newsletters";


	if ((strcmp(strtoupper($_GET['opcode']), INSERT_REC) == 0)) {
		$query = $query . " LIMIT 0 OFFSET 1";  // This is an insert so only get one row for the meta information
	}
	else {
		$query = $query . " WHERE id = " . $_GET['id'];
	}

	/*************************
	$flds  = array('eventtime' 		=> 'Time',
								 'eventdate' 		=> 'Date',
	               'eventname'		=> 'Event Name',
	               'eventdesc' 		=> 'Description',
							  );
	***********************/



	$pkey = array('id'	=> $_GET['opcode']);

	$obj = new buildForm($_GET['opcode'],$query,$pkey,$flds);

	$obj->addField('emailed','Emailed',"",'Sent out on Mailing List');
	$obj->addField('pubdate','Publication Date','DD/MM/YYYY');
	$obj->addField('title','Title','Mandatory');
	$obj->addField('filename','Filename','Enter if Newsletter on file');
	$obj->addField('newslettertext','Newsletter Text');


	$obj->submitTarget = "sqlnewsletter.php";
	$obj->pkey = array('id' => $_GET['id']);

	$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
	$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page
	$obj->addLink("bnewsletter.php","Newsletters");	// Add a link to take us back to the Home page


	require('smartysetup.php');


	$smarty = new Smarty();
	$smarty->assign('title', $pageTitle);
	$smarty->assign('opcode', $_GET['opcode']);

	$smarty->assign_by_ref('obj', $obj);

	$smarty->display('editnewsletter.tpl');

?>


