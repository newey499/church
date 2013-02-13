<?php
require_once("../dbconnectparms.php");
require_once("../mysql.php");
require_once("../mysqldatetime.php");

/**************************************

03/04/2012	CDN		Change missing mp3 file from error to warning as the file can be uploaded later
									from the CMS systems browse sermons talks bsermonstalks.php file



******************************************/



/**
 * Description of class
 *
 * Consolidates common Sermons/Talks actions
 * @author cdn
 */
class SermonsTalks
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

		//date performed must be a valid date
		if ((!isset($data['dateperformed'])) || (! $dateObj->checkUKdate($data['dateperformed']))) 
		{
			$this->addError('dateperformed', SermonsTalks::VALIDATION_ERROR, 
							"[" . $data['dateperformed'] . "] is not a valid date");
			//dispError($data['eventdate'] . " is not a valid date");
			$result = false;
		}


		//title may not be blank
		if ((!isset($data['title'])) || (!is_string($data['title'])) || (trim($data['title']) == "")) 
		{
			$this->addError('title', SermonsTalks::VALIDATION_ERROR,
							"Title may not be blank");
			//dispError("Event Name may not be blank");
			$result = false;
		}

		// groupno must be a valid +ve int
		if ( 
				 (!isset($data['groupno']))  			|| 
				 (trim($data['groupno']) == "") 	||
         (!is_numeric($data['groupno'])) 	|| 
				 (!is_int((int) $data['groupno'])) 
			 )  
		{ 
			$this->addError('groupno', SermonsTalks::VALIDATION_ERROR,
							"Group Number must be a valid Integer value [" . $data['groupno'] . "]");
			//dispError("Event Name may not be blank");
			$result = false;
		}
		else
		{
			if (intval($data['groupno']) < 0)
			{
				$this->addError('groupno', SermonsTalks::VALIDATION_ERROR,
								"Group Number must be greater than or equal to zero");
				//dispError("Event Name may not be blank");
				$result = false;
			}
		}

		// itemno must be a valid +ve int
		if ( 
				 (!isset($data['itemno']))  			|| 
				 (trim($data['itemno']) == "") 	||
         (!is_numeric($data['itemno'])) 	|| 
				 (!is_int((int) $data['itemno'])) 
			 )  
		{
			$this->addError('itemno', SermonsTalks::VALIDATION_ERROR,
							"Item Number must be a valid Integer value [" . $data['groupno'] . "]");
			//dispError("Event Name may not be blank");
			$result = false;
		}
		else
		{
			if (intval($data['itemno']) < 0)
			{
				$this->addError('itemno', SermonsTalks::VALIDATION_ERROR,
								"Item Number must be greater than or equal to zero");
				//dispError("Event Name may not be blank");
				$result = false;
			}
		}



		//filename must not be blank and the file must exist
		if ((!isset($data['filename'])) || (!is_string($data['filename'])) || (trim($data['filename']) == "")) 
		{
			$this->addError('filename', SermonsTalks::VALIDATION_WARNING,
							"File should not be blank");
			//dispWarn("Warning : Event Description is blank");
			//$result = false; // CDN 3/4/12 Warning only
		}
		else
		{
			// Check that file exists
			// CDN 03/04/2012 Make this a warning rather than an error. See note at top of this file
			if (! file_exists("../mp3s/" . $data['filename']))
			{
				$this->addError('filename', SermonsTalks::VALIDATION_WARNING,
							"File does not exist in mp3 directory");
				//$result = false; // CDN 3/4/12 Warning only
			}
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
		$tmp[SermonsTalks::ERROR_VAR]		= $varName;
		$tmp[SermonsTalks::ERROR_TYPE]	= $errorType;
		$tmp[SermonsTalks::ERROR_MSG]		= $errorMsg;
		$tmp[SermonsTalks::ERROR_NO]		= $errNo;
		$this->errors[] = $tmp;
	}

	public function insertEvent($data)
	{
		$this->errors = array();
		$this->result = NULL;
		$result = true;
		$oDate = new mysqldate();
		$mysqlDateTime = $oDate->fmtDateTime($oDate->UK_DATE, $data['dateperformed'],"11:11");


		$stmnt = sprintf("INSERT INTO sermonstalks 
												(
													dateperformed, 
													filename,
													series, 
													biblebook, 
													bibleref,
													title, 
													preacher, 
													description,
													groupno,
													itemno
                        )
												VALUES ('%s', '%s', '%s', '%s', '%s',
                                '%s', '%s', '%s', '%s', '%s'
                               )",
												mysql_real_escape_string($eventdate),
												mysql_real_escape_string($_POST['filename']),																
												mysql_real_escape_string($_POST['series']),									
												mysql_real_escape_string($_POST['biblebook']),	
												mysql_real_escape_string($_POST['bibleref']),	
												mysql_real_escape_string($_POST['title']),	
												mysql_real_escape_string($_POST['preacher']),
												mysql_real_escape_string($_POST['description']),
												mysql_real_escape_string($_POST['groupno']),
												mysql_real_escape_string($_POST['itemno'])
						);

		if (! $this->result = $this->performQuery($stmnt))
		{
			$this->addError('none', SermonsTalks::INSERT_ERROR, mysql_error());
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

		$stmnt = sprintf("UPDATE sermonstalks 
												SET 
													dateperformed = '%s', 
													filename = '%s',
													series = '%s', 
													biblebook = '%s', 
													bibleref = '%s',
													title = '%s', 
													preacher = '%s', 
													description = '%s',
													groupno = %s,
													itemno = %s
											WHERE id=%d",
												mysql_real_escape_string($eventdate),
												mysql_real_escape_string($_POST['filename']),																
												mysql_real_escape_string($_POST['series']),									
												mysql_real_escape_string($_POST['biblebook']),	
												mysql_real_escape_string($_POST['bibleref']),	
												mysql_real_escape_string($_POST['title']),	
												mysql_real_escape_string($_POST['preacher']),
												mysql_real_escape_string($_POST['description']),
												mysql_real_escape_string($_POST['groupno']),
												mysql_real_escape_string($_POST['itemno']),
												$_POST['id']
										);

		$this->result = $this->performQuery($stmnt);
		if (! $this->result)
		{
			$this->addError('none', SermonsTalks::UPDATE_ERROR, mysql_error());
		}
		else
		{
			if (!  (mysql_affected_rows() == 1))
			{
				$this->result = false;
				$this->addError('none', SermonsTalks::UPDATE_ERROR,
								"Update operation modified [" . mysql_affected_rows() . "] rows");
				$this->addError('none', SermonsTalks::UPDATE_ERROR,
								"Query Executed [" . $stmnt . "]");				
			}
		}



		return $result;
	}


	public function deleteEvent($data)
	{
		$this->errors = array();
		$this->result = true;

		$stmnt = sprintf("DELETE FROM sermonstalks
									WHERE id=%d",
									$data['id']
									);

		if (! $this->result = $this->performQuery($stmnt))
		{
			$this->addError('none', SermonsTalks::DELETE_ERROR, "XX" . mysql_error());
			$this->addError('none', SermonsTalks::DELETE_ERROR,
							"Query Executed [" . $stmnt . "]");
		}
		else
		{
			if (! (mysql_affected_rows() == 1))
			{
				$this->result = false;
				$this->addError('none', SermonsTalks::DELETE_ERROR,
								"Delete operation removed [" . mysql_affected_rows() . "] rows");
				$this->addError('none', SermonsTalks::DELETE_ERROR,
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
