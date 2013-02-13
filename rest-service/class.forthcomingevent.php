<?php
require_once("../dbconnectparms.php");
require_once("../mysql.php");
require_once("../mysqldatetime.php");

/**
 * Description of class
 *
 * Consolidates common Forthcoming Event actions
 * @author cdn
 */
class ForthcomingEvent
{

  const VALIDATION_WARNING = 1;
  const VALIDATION_ERROR	 = 2;

  const INSERT_ERROR	 = 3;
  const UPDATE_ERROR	 = 4;
  const DELETE_ERROR	 = 5;
	const AUTH_ERROR_NO_USERID = 6;
	const AUTH_ERROR_NO_PASSWD = 7;
	const AUTH_ERROR_INVALID_USERID = 8;
	const AUTH_ERROR_INVALID_PASSWD = 9;
	const AUTH_ERROR_INVALID_LOGIN_CREDENTIALS = 10;
	const AUTH_ERROR_REST_DISABLED = 11;

	// offsets for errors contained in $errors sub arrays
	const ERROR_VAR		= 0;
	const ERROR_TYPE	= 1;
	const ERROR_MSG		= 2;
	const ERROR_NO		= 3;


	protected  $errors;
	protected  $result;

	function __construct()
	{
		$this->errors = array();
		$this->result = NULL;
	}

	public function validate($data)
	{
		$this->errors = array();
		$this->result = NULL;
		$timeObj = new mysqltime;
		$dateObj = new mysqldate;
		$result = true;

		// If we are going to delete it we don't care if its valid
		if (isset($data['opcode']))
		{
			if ($data['opcode'] == DELETE_REC)
			{
				return True;
			}
		}

		//event date must be a valid date
		if ((!isset($data['eventdate'])) || (! $dateObj->checkUKdate($data['eventdate']))) 
		{
			$this->addError('eventdate', ForthcomingEvent::VALIDATION_ERROR, 
							"[" . $data['eventdate'] . "] is not a valid date");
			//dispError($data['eventdate'] . " is not a valid date");
			$result = false;
		}

		//event time must be a valid time
		if ((!isset($data['eventtime'])) || (! $timeObj->checkTime($data['eventtime']))) 
		{
			$this->addError('eventtime', ForthcomingEvent::VALIDATION_ERROR, 
							"[" . $data['eventtime'] . "] Event Time not a valid time");
			//dispError($data['eventtime'] . " is not a valid time");
			$result = false;
		}

		//eventname may not be blank
		if ((!isset($data['eventname'])) || (!is_string($data['eventname'])) || (trim($data['eventname']) == "")) 
		{
			$this->addError('eventname', ForthcomingEvent::VALIDATION_ERROR,
							"Event Name may not be blank");
			//dispError("Event Name may not be blank");
			$result = false;
		}

		//eventdesc may or may not not be blank issue warning if blank
		if ((!isset($data['eventdesc'])) || (!is_string($data['eventdesc'])) || (trim($data['eventdesc']) == "")) 
		{
			$this->addError('eventdesc', ForthcomingEvent::VALIDATION_WARNING,
							"Event Description is blank");
			//dispWarn("Warning : Event Description is blank");
		}

		/***
		 CDN 19/01/2009
		 No Validation for new fields
		 ********************************/
		//contribemail,
		//contactname,
		//contactphone,
		//contactemail
		/***
		 CDN 24/06/2009
		 No Validation for new fields
		 ********************************/
		// linkurl

		$this->result = $result;

		return $result;
	}


	public function getResult()
	{
		return $this->result;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function addError($varName, $errorType, $errorMsg, $errNo = 0)
	{
		$tmp = array();
		$tmp[ForthcomingEvent::ERROR_VAR]		= $varName;
		$tmp[ForthcomingEvent::ERROR_TYPE]	= $errorType;
		$tmp[ForthcomingEvent::ERROR_MSG]		= $errorMsg;
		$tmp[ForthcomingEvent::ERROR_NO]		= $errNo;
		$this->errors[] = $tmp;
	}

	public function insertEvent($data)
	{
		$this->errors = array();
		$this->result = NULL;
		$result = true;
		$oDate = new mysqldate();
		$mysqlDateTime = $oDate->fmtDateTime($oDate->UK_DATE, $data['eventdate'],$data['eventtime']);


		$stmnt = sprintf("INSERT INTO forthcomingevents
											(eventdate,eventname,eventdesc,
											 contribemail, contactname, contactphone, contactemail,
											 isvisible, linkurl
											)
											VALUES ('%s', '%s', '%s',
															'%s', '%s', '%s', '%s',
															'%s', '%s'
														 )",
											mysql_real_escape_string($mysqlDateTime),
											mysql_real_escape_string($data['eventname']),
											mysql_real_escape_string($data['eventdesc']),
											mysql_real_escape_string($data['contribemail']),
											mysql_real_escape_string($data['contactname']),
											mysql_real_escape_string($data['contactphone']),
											mysql_real_escape_string($data['contactemail']),
											mysql_real_escape_string($data['isvisible']),
											mysql_real_escape_string($data['linkurl'])
						);

		if (! $this->result = $this->performQuery($stmnt))
		{
			$this->addError('none', ForthcomingEvent::INSERT_ERROR, mysql_error());
		}


		return $result;
	}


	public function updateEvent($data)
	{
		$this->errors = array();
		$this->result = NULL;
		$result = true;
		$oDate = new mysqldate();
		$mysqlDateTime = $oDate->fmtDateTime($oDate->UK_DATE, $data['eventdate'],$data['eventtime']);

		$stmnt = sprintf("UPDATE forthcomingevents
										SET eventdate = '%s',eventname = '%s',eventdesc='%s',
										contribemail = '%s', contactname = '%s',
										contactphone = '%s', contactemail = '%s',
										isvisible = '%s', linkurl = '%s'
										WHERE id=%d",
										mysql_real_escape_string($mysqlDateTime),
										mysql_real_escape_string($data['eventname']),
										mysql_real_escape_string($data['eventdesc']),
										mysql_real_escape_string($data['contribemail']),
										mysql_real_escape_string($data['contactname']),
										mysql_real_escape_string($data['contactphone']),
										mysql_real_escape_string($data['contactemail']),
										mysql_real_escape_string($data['isvisible']),
										mysql_real_escape_string($data['linkurl']),
										$data['id']
										);

		$this->result = $this->performQuery($stmnt);
		if (! $this->result)
		{
			$this->addError('none', ForthcomingEvent::UPDATE_ERROR, mysql_error());
		}
		else
		{
			if (!  (mysql_affected_rows() == 1))
			{
				$this->result = false;
				$this->addError('none', ForthcomingEvent::UPDATE_ERROR,
								"Update operation modified [" . mysql_affected_rows() . "] rows");
				$this->addError('none', ForthcomingEvent::UPDATE_ERROR,
								"Query Executed [" . $stmnt . "]");				
			}
		}



		return $result;
	}


	public function deleteEvent($data)
	{
		$this->errors = array();
		$this->result = true;

		$stmnt = sprintf("DELETE FROM forthcomingevents
									WHERE id=%d",
									$data['id']
									);

		if (! $this->result = $this->performQuery($stmnt))
		{
			$this->addError('none', ForthcomingEvent::DELETE_ERROR, "XX" . mysql_error());
			$this->addError('none', ForthcomingEvent::DELETE_ERROR,
							"Query Executed [" . $stmnt . "]");
		}
		else
		{
			if (! (mysql_affected_rows() == 1))
			{
				$this->result = false;
				$this->addError('none', ForthcomingEvent::DELETE_ERROR,
								"Delete operation removed [" . mysql_affected_rows() . "] rows");
				$this->addError('none', ForthcomingEvent::DELETE_ERROR,
								"Query Executed [" . $stmnt . "]");
			}
		}



		return $this->result;
	}


	protected function performQuery($qry)
	{
		/* Connect to a MySQL server */
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			 or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

		$result = mysql_query($qry);

		if ($result)
		{
			//setLastupdatedDate( date('d/m/Y'), date('H:i'));
			//touchMenuItem("forthcoming events");
			//touchMenuItem("whats on calendar");
			//print("<p>" . "performQuery returned true"  . "</p>\n");
		}
		else
		{
			//print("<p>" . mysql_error() . "</p>\n");
		}


		return $result;
	}


}
?>
