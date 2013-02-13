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
			$title = "Insert a new Forthcoming Event";
			break;
		case UPDATE_REC:		
			$title = "Update this existing Forthcoming Event";
			break;
		case DELETE_REC:		
			$title = "Delete this Forthcoming Event";
			break;
		default: 			
			break;
	}
?>



	

<?php
	
	$query = "SELECT	id,
										orgid,
										DATE_FORMAT(eventdate,'%d/%m/%Y') as eventdate,
							 			date_format(eventdate,'%H:%i') as eventtime,									
										eventname,
										eventdesc,
                    contribemail,
                    contactname,
                    contactphone,
                    contactemail,
                    isvisible,
										linkurl 
					  FROM forthcomingevents"; 
					  
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

	$obj->addField('isvisible','Visible');	
	$obj->addField('eventtime','Time','HH:MM', 'Use 11:11 to suppress Display of Time');	
	$obj->addField('eventdate','Date','DD/MM/YYYY');
	$obj->addField('linkurl','Link URL');
	$obj->addField('eventname','Event Name','Mandatory');
	$obj->addField('eventdesc','Description');
	$obj->addField('contribemail',"Contributor Email");
	$obj->addField('contactname',"Contact Name");
	$obj->addField('contactphone',"Contact Phone");
	$obj->addField('contactemail',"Contact Email");


	$obj->submitTarget = "sqlforthevents.php";
	$obj->pkey = array('id' => $_GET['id']); 		
	
	$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
	$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page	
	$obj->addLink($_SERVER['HTTP_REFERER'],"Forthcoming Events");			// Add a link to take us back to the browse page		

	// Increase the size of the text entry area
	$obj->textarearows = 20;
	$obj->textareacols = 80;	
	
	/**********
	echo "<div class=\"cdnform\">\n";
	$obj->exec($opcode);
	echo "</div>\n";
	**************/

	require('smartysetup.php');

	$smarty = new Smarty();
	$smarty->assign('title', $title);
	$smarty->assign('opcode', $_GET['opcode']);

	$smarty->assign_by_ref('obj', $obj);

	$smarty->display('editforthevents.tpl');

?>
</P>


