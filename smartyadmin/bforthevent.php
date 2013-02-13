<?php
require_once "../globals.php";
include_once "../mysql.php";
require_once "../dumpquerytotable.php";

/******************************
	Start a session. This must be the very first thing done on the page.

	The session Classes constructor also opens the database.
**********************************/
require_once("../session.php");
$oSession = new session();

$pageTitle = "Browse Forthcoming Events";



if ( isset($_GET['opcode']) && ($_GET['opcode'] == FC_EVENT_MARK_VISIBLE) )
{
	$str = "SELECT	id, " .
		 		 "orgid, " .
				 "DATE_FORMAT(eventdate,'%d/%m/%Y') as eventdate, " .
				 "date_format(eventdate,'%H:%i') as eventtime,	" .
				 "eventname, " .
				 "eventdesc, " .
         "contribemail, " .
         "contactname, " .
         "contactphone, " .
         "contactemail, " .
         "isvisible " .
				 "FROM forthcomingevents ";

	$qry = $str . sprintf(" WHERE id = %d", $_GET['id']);

	if (! $result = mysql_query($qry))
	{
		print("<h2>SELECT Failed on id [" . $id . "]</h2>\n");
		return;
	}

	$row = mysql_fetch_assoc($result);

	// Mark Item as visible if it isn't already
	if ($row['isvisible'] == 'YES')
	{
		print("<h4>" . $row['eventname'] . " is already visible.</h4>\n");
	}
	else
	{
		$qry = sprintf("UPDATE forthcomingevents SET isvisible = 'YES' WHERE id = %d",
									 $_GET['id']);

		if (! mysql_query($qry))
		{
			print("<h4>UPDATE Failed on id [" . $id . "]</h4>\n");
		}
		else
		{
			print("<h4>Forthcoming Event [" . $row['eventname']  . "] has been Flagged as Visible</h4>\n");
			if (! empty($row['contribemail']))
			{
				// Status updated - Send Email to Contributor and copy to Webmaster.
				$from = 'webmaster@christchurchlye.org.uk';
				$headers  = 'From: ' . $from . "\r\n";
				$headers .= 'Reply-To: ' . $from . "\r\n";
				// PHP Bug/Feature  - "Bcc:" doesn't work reliably
				//$headers .= 'Bcc:' . $from . "\r\n";

				$msg = "Your request for the forthcoming event\n[" .
    	    		 $row['eventname'] . '] at ' .
               $row['eventtime'] . ' on ' . $row['eventdate'] . "\n" .
							 "\nto be posted on the Christ Church Website has been completed.\n" .
    			     "The event is now visible on the website.\n";


				$subject = 'Request for Forthcoming Event';

				$mail1 = mail($row['contribemail'],
										  $subject,
											$msg,
											$headers);

				$msg .= "\n\nCopy of email sent to [" . $row['contribemail'] . "] regarding\n" .
      			    "the creation of a new forthcoming event.\n";

				$subject  = "Webmaster copy of: " . $subject;
				$headers  = 'From: ' . $from . "\r\n";
				$headers .= 'Reply-To: ' . $row['contribemail'] . "\r\n";

				$mail2 = mail($from,
										  $subject,
											$msg,
											$headers);

				if ( $mail1 && $mail2 )
				{
					print("<h4>Email sent to Contributor at " . $row['contribemail'] . "</h4>\n");
				}
				else
				{
					print("<h4>Email Error - Message was not sent to Contributor " .
                $row['contribemail'] . "</h4>\n");
				}
			}
		}
	}


	mysql_free_result($result);
}



/******************
 Ensure that the columns you want to display and the columns that comprise the tables
 primary key are in the select statement
***********************/

$qry = "SELECT	id,
								orgid,
								DATE_FORMAT(eventdate,'%d/%m/%Y') as dispeventdate,
							 	date_format(eventdate,'%H:%i') as eventtime,
							 	eventname,
							 	eventdesc
        FROM forthcomingevents
        ORDER BY eventdate";

$obj = new dumpQryToTable($oSession->dbHandle,$qry);

$obj->borderSize = 1;													// Size of border around table
$obj->insertTarget 	= "editforthevents.php";		// Insert new row link points to this target
$obj->editTarget 		= "editforthevents.php";		// Update row link points to this target
$obj->deleteTarget 	= "editforthevents.php";		// Delete row link points to this target

/**********
Add names of columns in primary key
***********************/
$obj->addPrimaryKeyColumn('id');
//$obj->addPrimaryKeyColumn('next pk col name');
//$obj->addPrimaryKeyColumn('next pk col name');

/*********
Columns to display in table
Add columns - args are 	1) column name
												2) Column Header Title
****************/
//$obj->addColumn("id","Id");
$obj->addColumn("dispeventdate","Event Date");
$obj->addColumn("eventtime","Event Time");
$obj->addColumn("eventname","Event Name");
//$obj->addColumn("eventdesc","Event Description");


/*********
Columns to display in table
Add columns - args are 	1) url of page to link to
												2) Column Header Title
The link will have the primary key values appended as arguments
****************/
$obj->addColumnLink('Make Visible','bforthevent.php?opcode=' . FC_EVENT_MARK_VISIBLE, 'Make Visible');


//$obj->tableClass = "error";						// CSS Class for table
//$obj->tableId = "centercontent";      // CSS Id for table
//$obj->thClass = "error";							// CSS Class for table header
//$obj->tdClass = "error";         			// CSS Class for table data row

$obj->addLink("admin.php","Admin");	// Add a link to take us back to the Admin page
$obj->addLink("../index.php","Home");			// Add a link to take us back to the Home page


//echo "<div class=\"cdnform\">\n";
//$obj->exec();														// Build the table
//echo "</div>\n";

require('smartysetup.php');

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);

// if you want to use the traditional object parameter format, pass a boolean of false
//$smarty->register_object('browseObject',$obj);
//$smarty->register_object('foobar',$obj,null,false);
$smarty->assign_by_ref('browseObject', $obj);

$smarty->display('bforthevent.tpl');




?>


