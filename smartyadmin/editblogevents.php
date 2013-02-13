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

	switch ($_GET['opcode']) {
		case INSERT_REC:
			$pageTitle = "Insert a new Blog Entry";
			break;
		case UPDATE_REC:
			$pageTitle = "Update this existing Blog Entry";
			break;
		case DELETE_REC:
			$pageTitle = "Delete this Blog Entry";
			break;
		default:
			break;
	}


	$query = "SELECT	id,
										DATE_FORMAT(stamp,'%d/%m/%Y') as stampdate,
							 			date_format(stamp,'%H:%i') as stamptime,
										headline,
										blogentry
					  FROM blog ";

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

	$obj->addField('stamptime','Time','HH:MM');
	$obj->addField('stampdate','Date','DD/MM/YYYY');
	$obj->addField('headline','Headline','Mandatory');
	$obj->addField('blogentry','Blog Entry');


	$obj->submitTarget = "sqlblog.php";
	$obj->pkey = array('id' => $_GET['id']);

	$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
	$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page
	$obj->addLink($_SERVER['HTTP_REFERER'],"Blog Entries");	// Add a link to take us back to the Browse Blog page

	$obj->textarearows = 20;
	$obj->textareacols = 80;

	require('smartysetup.php');

	$smarty = new Smarty();
	$smarty->assign('title', $pageTitle);
	$smarty->assign('opcode', $_GET['opcode']);

	$smarty->assign_by_ref('obj', $obj);

	$smarty->display('editblogevents.tpl');

?>



