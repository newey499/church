<?php
/***********************************************

class.events.php

Date				Programmer				Description
10/03/2012	CDN 				Allow json to be passed for in pathElements[2] for production of all events in JSON format

*************************************************/
require_once("../dbconnectparms.php");
require_once("../globals.php");
require_once("../genlib.php");

require_once("../mysql.php");
require_once("../mysqldatetime.php");

require_once('class.login.php');
require_once('class.rest.exception.php');
require_once('class.rest-service.php');
require_once('class.allevents.php');
require_once('class.forthcomingevent.php');

/**
 * Description of class
 *
 * @author cdn
 */
class Events
{

  public $mysqlj;   // Database handle
  public $request;  // Request details
	public $oLogin;   // Log in Validation class used when request received that
										// attempts to update the database

	function __construct(RestService $request)
	{
    $this->request = $request;

    /* Connect to a MySQL server */
    $this->mysqlj = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
       or die('Could not connect: ' . mysql_error());

    mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

	}

	function __destruct()
	{
    /* Close the connection and free the memory used*/
    mysql_close($this->mysqlj);
	}


public function handleAll()
{

	switch ($this->request->method)
	{
		case 'GET' :
			$this->handleAllEvents();
			break;

		case 'POST' :
			$this->throwError('POST is not supported for All Events', 100);
			break;

		case 'PUT' :
			$this->throwError('PUT is not supported for All Events', 101);
			break;

		case 'DELETE' :
			$this->throwError('DELETE is not supported for All Events', 102);
			break;

		default :
			$this->throwError("Unsupported service requested URL [" .
								$this->request->url .
								"] Method [" . $this->request->method . "]", 103);
			break;

	}
}


public function handleRegular()
{
	switch ($this->request->method)
	{
		case 'GET' :
			$this->handleRegularEvents();
			break;

		case 'POST' :
			$this->throwError('POST is not supported for Regular Events', 200);
			break;

		case 'PUT' :
			$this->throwError('PUT is not supported for Regular Events', 201);
			break;

		case 'DELETE' :
			$this->throwError('DELETE is not supported for Regular Events', 202);
			break;

		default :
			$this->throwError("Unsupported service requested URL [" .
								$this->request->url .
								"] Method [" . $this->request->method . "]", 203);
			break;

	}

}


  public function handleOneOff()
  {
		switch ($this->request->method)
		{
			case "GET":
				$this->handleOneOffGetEvents();
				break;

			case "POST":

				$oEvent = new ForthcomingEvent();

				//TODO validate login details
				if (! $this->validateLogin($_POST, $oEvent))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Insert One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Authorisation failed");
					$this->reportErrors($oEvent);
					$this->request->writeElementEnd("result");
					break;
				}
				
				if ($oEvent->validate($_POST))
				{
					if ($oEvent->insertEvent($_POST))
					{
						$this->request->writeElementStart("result");
						$this->request->writeElement("operation", "Insert One Off Event");
						$this->request->writeElement("success", "TRUE");
						$this->request->writeElement("id", mysql_insert_id()); // report primary key of new row
						$this->request->writeElementEnd("result");
					}
					else
					{
						$this->request->writeElementStart("result");
						$this->request->writeElement("operation", "Insert One Off Event");
						$this->request->writeElement("success", "FALSE");
						$this->request->writeElement("message", "Validation succeded");
						$this->request->writeElement("message", "Insert on Database failed");
						$this->request->writeElementEnd("result");
					}
				}
				else
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Insert One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Validation failed");
					$this->reportErrors($oEvent);
					$this->request->writeElementEnd("result");
				}

				break;

			case "PUT":

				$oEvent = new ForthcomingEvent();

				//TODO validate login details
				if (! ( is_numeric($this->request->putArgs['id']) &&
								is_int((int) $this->request->putArgs['id']) ))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Update One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Primary Key [" .
																			 $this->request->putArgs['id'].
																			 "] does not exist");
					$this->request->writeElement("message", "Update on Database failed");
					$this->request->writeElementEnd("result");
					break;
				}

				if (! $this->idExists($this->request->putArgs['id'], "forthcomingevents"))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Update One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Primary Key [" .
																			 $this->request->putArgs['id'].
																			 "] does not exist");
					$this->request->writeElement("message", "Update on Database failed");
					$this->request->writeElementEnd("result");
					break;
				}

				if ($oEvent->validate($this->request->putArgs))
				{
					if ($oEvent->updateEvent($this->request->putArgs))
					{
						$this->request->writeElementStart("result");
						$this->request->writeElement("operation", "Update One Off Event");
						$this->request->writeElement("success", "TRUE");
						$this->request->writeElement("id", $this->request->putArgs['id']); // report primary key of new row
						$this->request->writeElementEnd("result");

					}
					else
					{
						$this->request->writeElementStart("result");
						$this->request->writeElement("operation", "Update One Off Event");
						$this->request->writeElement("success", "FALSE");
						$this->request->writeElement("message", "Validation succeded");
						$this->request->writeElement("message", "Update on Database failed");
						$this->request->writeElementEnd("result");
					}
				}
				else
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Update One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Validation failed");
					$this->reportErrors($oEvent);
					$this->request->writeElementEnd("result");
				}
				break;

			case "DELETE":
				$oEvent = new ForthcomingEvent();


				//TODO validate login details

				if (! ( is_numeric($this->request->deleteArgs['id']) &&
								is_int((int) $this->request->deleteArgs['id']) ))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Delete One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Primary Key [" .
																			 $this->request->deleteArgs['id'].
																			 "] does not exist");
					$this->request->writeElement("message", "Delete on Database failed");
					$this->request->writeElementEnd("result");
					break;
				}

				if (! $this->idExists($this->request->deleteArgs['id'], "forthcomingevents"))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Delete One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("message", "Primary Key [" .
																			 $this->request->deleteArgs['id'].
																			 "] does not exist");
					$this->request->writeElement("message", "Delete on Database failed");
					$this->request->writeElementEnd("result");
					break;
				}


				if ($oEvent->deleteEvent($this->request->deleteArgs))
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Delete One Off Event");
					$this->request->writeElement("success", "TRUE");
					$this->request->writeElement("id", $this->request->deleteArgs['id']);
					$this->request->writeElementEnd("result");

				}
				else
				{
					$this->request->writeElementStart("result");
					$this->request->writeElement("operation", "Delete One Off Event");
					$this->request->writeElement("success", "FALSE");
					$this->request->writeElement("id", $this->request->deleteArgs['id']);
					$this->request->writeElement("message", "Delete on Database failed");
					$this->request->writeElementEnd("result");
				}
				break;
				
			default:
				$this->throwError("Unsupported service requested URL [" .
									$this->request->url .
									"] Method [" . $this->request->method . "]", 303);
				break;
		}


  }


  protected function handleRegularEvents()
  {
		if ( (! isset($this->request->pathElements[2])) ||
         (strToUpper($this->request->pathElements[2]) === "JSON")
			 )
		{
      $this->writeAllRegularEvents();
		}
		else
		{
			switch (strtoupper($this->request->pathElements[2]))
			{
				case "ID" :
					if (! isset($this->request->pathElements[3]))
					{
						$this->throwError("ID number not in URI", 9);
					}
					else
					{
						if ($this->isValidInt($this->request->pathElements[3], 1,  PHP_INT_MAX))
						{
							$this->writeRegularEvent($this->request->pathElements[3]);
						}
						else
						{
							$this->throwError("Error 1 Individual Regular Event Invalid Record Number [" .
										$this->request->pathElements[3] . "]", 8);
						}
					}
					break;

				case "DATE" :
					if (! isset($this->request->pathElements[3]))
					{
						$this->throwError("Year number not in URI", 10);
					}
					if (! isset($this->request->pathElements[4]))
					{
						$this->throwError("Month number not in URI", 11);
					}

					$this->writeRegularEventsByDate();

					break;

				default :
					if ($this->isValidInt($this->request->pathElements[2], 1,  PHP_INT_MAX))
					{
						$this->writeRegularEvent($this->request->pathElements[2]);
					}
					else
					{
						$this->throwError("Error 2 Individual Regular Event Invalid Record Number [" .
									$this->request->pathElements[2] . "]", 8);
					}
					break;
			}

		}

  }

  protected function writeRegularEvent($id)
  {
    $qry = "SELECT id, dayofweek, weekofmonth, startdate, enddate, " .
           "       eventtime, eventname, eventdesc, linkurl, isvisible " .
           "FROM regularevents " . 
					 'WHERE isvisible = "YES" ' .
				   "   AND id = " . $id . " " .
           "ORDER BY weekofmonth, dayofweek, eventtime ";

    $result = mysql_query($qry);
    if (! $result)
    {
          $this->throwError("Query failed", 9);
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeRegularEventElement($row);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);

  }


  protected function writeAllRegularEvents()
  {
    $qry = "SELECT id, dayofweek, weekofmonth, startdate, enddate, " .
           "       eventtime, eventname, eventdesc, linkurl, isvisible " .
           "FROM regularevents " .
					 'WHERE isvisible = "YES" ' .
					 "ORDER BY weekofmonth, dayofweek, eventtime ";

    $result = mysql_query($qry);
    if (! $result)
    {
          $this->throwError("Query failed, 10");
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeRegularEventElement($row);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);
  }


	protected function writeRegularEventsByDate()
  {
		// Check Year
		if (! isset($this->request->pathElements[3]))
		{
			$this->throwError("Year not provided", 14);
		}
		if (!	$this->isValidInt($this->request->pathElements[3], date("Y"),  PHP_INT_MAX))
		{
			$this->throwError("Invalid Year [" . $this->request->pathElements[3] .
												"] - must be this year or later", 14);
		}

		// Check Month
		/*
		 * Allow the month to be optional
		if (! isset($this->request->pathElements[4]))
		{
			$this->throwError("Month not provided", 15);
		}
		****************/
		if (isset($this->request->pathElements[4]))
		{
			if (!	$this->isValidInt($this->request->pathElements[4], 1,  12))
			{
				$this->throwError("Invalid Month [" . $this->request->pathElements[4] .
													"]", 15);
			}
		}


		// Check Day
		if (isset($this->request->pathElements[5]))
		{
			$daysInMonth = cal_days_in_month(CAL_GREGORIAN,
																			 $this->request->pathElements[4],
																			 $this->request->pathElements[3]);
			if (!	$this->isValidInt($this->request->pathElements[5], 1,  $daysInMonth))
			{
				$this->throwError("Invalid Day [" . $this->request->pathElements[5] .
													"]", 16);
			}
		}


		$day = 1;
		if (isset($this->request->pathElements[5]))
		{
			$day = $this->request->pathElements[5];
		}

		$oCal = new Calendar( $this->request->pathElements[4],		// Month
													$this->request->pathElements[3],		// Year
													$day,
													Calendar::TABLE_REGULAR);

		//$this->throwError("Calendar with TABLE_REGULAR table flags [" . $oCal->getSourceTableFlags() . "]");

		if (isset($this->request->pathElements[5]))
		{
			$oCal->writeRegularEventsForDay($this, $day);
		}
		else
		{
			$oCal->writeRegularEventsForMonth($this);
		}
  }

  protected function handleOneOffGetEvents()
  {
		// CDN 10/03/2012
		// Allow json to be passed for in pathElements[2] for production of all events in JSON format

		if ( (isset($this->request->pathElements[2])) &&
         (strToUpper($this->request->pathElements[2]) === "JSON")
			 )
		{
      $this->writeAllOneOffEvents();
			return;
		}

    if (! empty($this->request->pathElements[2]))
    {
			switch (strtoupper($this->request->pathElements[2]))
			{
				case "ID" :
					if ($this->isValidInt($this->request->pathElements[3], 1,  PHP_INT_MAX))
					{
						$this->writeOneOffEvent($this->request->pathElements[3]);
					}
					else
					{
						$this->throwError("Error 3 Individual One Off Event Invalid Record Number [" .
								$this->request->pathElements[3] . "]", 12);
					}
					break;

				case "DATE" :

					// Check Year
					if (! isset($this->request->pathElements[3]))
					{
						$this->throwError("Year not provided", 14);
					}
					if (!	$this->isValidInt($this->request->pathElements[3], date("Y"),  PHP_INT_MAX))
					{
						$this->throwError("Invalid Year [" . $this->request->pathElements[3] .
															"] - must be this year or later", 14);
					}

					// Check Month
					/*
					 * Allow the month to be optional
					if (! isset($this->request->pathElements[4]))
					{
						$this->throwError("Month not provided", 15);
					}
					****************/
					if (isset($this->request->pathElements[4]))
					{
						if (!	$this->isValidInt($this->request->pathElements[4], 1,  12))
						{
							$this->throwError("Invalid Month [" . $this->request->pathElements[4] .
																"]", 15);
						}
					}


					// Check Day
					if (isset($this->request->pathElements[5]))
					{
						$daysInMonth = cal_days_in_month(CAL_GREGORIAN,
																						 $this->request->pathElements[4],
																						 $this->request->pathElements[3]);
						if (!	$this->isValidInt($this->request->pathElements[5], 1,  $daysInMonth))
						{
							$this->throwError("Invalid Day [" . $this->request->pathElements[5] .
																"]", 16);
						}
					}

					//$this->throwError("DEBUG - about to process Date args", 17);
					//$this->writeOneOffEvent($this->request->pathElements[3]);
					$this->writeOneOffEventByDate($this->request->pathElements[3]);
					break;

				default:	// Assume the number passed is an id number
					if ($this->isValidInt($this->request->pathElements[2], 1,  PHP_INT_MAX))
					{
						$this->writeOneOffEvent($this->request->pathElements[2]);
					}
					else
					{
						$this->throwError("Error 4 Individual One Off Event Invalid Record Number [" .
								$this->request->pathElements[2] . "]", 31);
					}
					break;
			}


    }
    else
    {
      $this->writeAllOneOffEvents();
    }

  }

  protected function writeOneOffEventByDate()
  {
    $qry = "SELECT id, " .
           "DATE(eventdate) AS eventdate, " .
           "DATE_FORMAT(eventdate, '%H:%i') AS eventtime, " .
           "eventname, eventdesc, orgid, " .
           "contribemail, contactname, contactphone, contactemail, " .
           "isvisible, linkurl " .
           "FROM forthcomingevents " . 
           'WHERE isvisible = "YES" ';

		if (isset($this->request->pathElements[3]))
		{
			$qry .= "WHERE YEAR(eventdate) = " . $this->request->pathElements[3] . " ";
		}
		if (isset($this->request->pathElements[4]))
		{
			$qry .= "AND MONTH(eventdate) = " . $this->request->pathElements[4] . " ";
		}
		if (isset($this->request->pathElements[5]))
		{
			$qry .= "AND DAYOFMONTH(eventdate) = " . $this->request->pathElements[5] . " ";
		}
		$qry .= " ORDER BY eventdate ";

    $result = mysql_query($qry);
    if (! $result)
    {
      $this->throwError("Query failed [" . mysql_error() . "] [" . $qry . "]", 121);
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeOneOffEventElement($row);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);

  }



  protected function writeOneOffEvent($id)
  {
    $qry = "SELECT id, " . 
           "DATE(eventdate) AS eventdate, " .
           "DATE_FORMAT(eventdate, '%H:%i') AS eventtime, " .
           "eventname, eventdesc, orgid, " .
           "contribemail, contactname, contactphone, contactemail, " .
           "isvisible, linkurl " .
           "FROM forthcomingevents " .
           'WHERE isvisible = "YES" ' .
           "  AND id = " . $id . " " .
					 "ORDER BY eventdate";

    $result = mysql_query($qry);
    if (! $result)
    {
      $this->throwError("Query failed[" . mysql_error() . "]", 122);
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeOneOffEventElement($row);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);

  }


  protected function writeAllOneOffEvents()
  {
    $qry = "SELECT id, " . 
           "DATE(eventdate) AS eventdate, " .
           "DATE_FORMAT(eventdate, '%H:%i') AS eventtime, " .
           "eventname, eventdesc, orgid, " .
           "contribemail, contactname, contactphone, contactemail, " .
           "isvisible, linkurl " .
           "FROM forthcomingevents " . 
					 'WHERE isvisible = "YES" ' .
					 "ORDER BY eventdate ";

    $result = mysql_query($qry);
    if (! $result)
    {
      $this->throwError("Query failed", 13);
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeOneOffEventElement($row);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);
  }


  public function throwError($errMsg, $errorNo = 0)
  {
    throw new RestException($errMsg, $errorNo);
  }


  protected function writeError($errMsg, $print = true)
  {
    $res = "<error>" .
           $errMsg .
           "</error>\n";

    if ($print)
    {
      //print($res);
			$this->request->writeElement('error', $errMsg);
    }

    return $res;
  }


  protected function writeRegularEventElement($row)
  {
    $this->request->writeElementStart("regularevent");
    $this->request->writeElement("id",          $row['id']);
    $this->request->writeElement("dayofweek",   $row['dayofweek']);
    $this->request->writeElement("weekofmonth", $row['weekofmonth']);
    $this->request->writeDateElement("startdate",   $row['startdate']);
    $this->request->writeDateElement("enddate",     $row['enddate']);
    $this->request->writeTimeElement("eventtime",   substr($row['eventtime'],0,5));
    $this->request->writeElement("eventname",   $row['eventname']);
    $this->request->writeElement("eventdesc",   $row['eventdesc']);
    $this->request->writeElement("linkurl",
             htmlentities("http://" . MYSQL_SERVER . "/" . $row['linkurl']));
    $this->request->writeElement("isvisible",   $row['isvisible']);
    $this->request->writeElementEnd("regularevent");

  }


  protected function writeOneOffEventElement($row)
  {
    $this->request->writeElementStart("oneoffevent");
    $this->request->writeElement("id",           $row['id']);
    $this->request->writeDateElement("eventdate",    $row['eventdate']);
    $this->request->writeTimeElement("eventtime",    substr($row['eventtime'],0,5));
    $this->request->writeElement("eventname",    $row['eventname']);
    $this->request->writeElement("eventdesc",    $row['eventdesc']);
    $this->request->writeElement("orgid",        $row['orgid']);
    $this->request->writeElement("contribemail", 
            htmlentities($row['contribemail']));
    $this->request->writeElement("contactname",  $row['contactname']);
    $this->request->writeElement("contactphone", $row['contactphone']);
    $this->request->writeElement("contactemail",
            htmlentities($row['contactemail']));
    $this->request->writeElement("isvisible",    $row['isvisible']);
    $this->request->writeElement("linkurl",
             htmlentities("http://" . MYSQL_SERVER . "/" . $row['linkurl']));
    $this->request->writeElementEnd("oneoffevent");
  }


protected function isValidInt($value, $min, $max)
{
  $result = false;

  if (! empty($value))
  {
    if ( is_numeric($value) && is_int((int) $value) )
    {
      if (($value >= $min) && ($value <= $max))
      {
        $result = true;
      }
    }
  }

  return $result;
}


  protected function handleAllEvents()
  {
		if (! isset($this->request->pathElements[2]))
		{
				$this->throwError("INVALID year - no year specified", 15);
		}
		else
		{
			if (! $this->isValidInt($this->request->pathElements[2], (date('Y') -1), (date('Y') +1)))
			{
				$this->throwError("INVALID year  [" . $this->request->pathElements[2] . "]", 14);
			}
		}

		if (! isset($this->request->pathElements[3]))
		{
				$this->throwError("INVALID month - no month specified]", 15);
		}
		else
		{
			if (! $this->isValidInt($this->request->pathElements[3], 1, 12))
			{
					$this->throwError("INVALID month  [" . $this->request->pathElements[3] . "]", 15);
			}
    }


    if (! empty($this->request->pathElements[4]))
    {
      // YYYY/MM/DD Passed - process events for year, month and day
      // checkdate ( int $month , int $day , int $year )
      if (! checkdate ( $this->request->pathElements[3],
                        $this->request->pathElements[4],
                        $this->request->pathElements[2]))
      {
        $this->throwError("INVALID day  [" . $this->request->pathElements[4] . "]", 16);
      }

      // YYYY/MM Passed - process events for year and month
      $oCalendar = new Calendar($this->request->pathElements[3],
                                $this->request->pathElements[2]);
      $oCalendar->writeAllEventsForDay($this, $this->request->pathElements[4]);
    }
    else
    {
      // YYYY/MM Passed - process events for year and month
      $oCalendar = new Calendar($this->request->pathElements[3],
                                $this->request->pathElements[2]);

      $oCalendar->writeAllEventsForMonth($this);
    }

  }

	public function reportErrors(ForthcomingEvent	$oEvent)
	{
		$errors = $oEvent->getErrors();
		foreach($errors as $item)
		{
			$this->request->writeElementStart("validationfault");
			$this->request->writeElement("fieldname",     $item[ForthcomingEvent::ERROR_VAR]);
			switch ( $item[ForthcomingEvent::ERROR_TYPE])
			{
				case ForthcomingEvent::VALIDATION_WARNING :
					$this->request->writeElement("type",    "warning");
					break;
				case ForthcomingEvent::VALIDATION_ERROR	 :
					$this->request->writeElement("type",    "error");
					break;
				default:
					$this->request->writeElement("type",    "unkown [" .
													$item[ForthcomingEvent::ERROR_TYPE] . "]");
					break;
			}

			$this->request->writeElement("message", $item[ForthcomingEvent::ERROR_MSG]);
			$this->request->writeElement("number",  $item[ForthcomingEvent::ERROR_NO]);
			$this->request->writeElementEnd("validationfault");
		}

	}


	public function idExists($id, $table)
	{
		$result = true;
		$qry = "SELECT id from " . $table . " WHERE id = " . $id;

		$result = mysql_query($qry);

		if (!( $result == false))
		{
			if (! (mysql_num_rows($result) == 1))
			{
				$result = false;
			}
		}

		return $result;
	}


	public function validateLogin($args, ForthcomingEvent $oEvent)
	{
		$result = true;

		// $oEvent->addError($varName, $errorType, $errorMsg, $errNo = 0);
		if (! isset($args['userid']))
		{
			$oEvent->addError("userid", ForthcomingEvent::AUTH_ERROR_NO_USERID,
												"User Id not defined",1);
			$result = false;
		}
		if (! isset($args['password']))
		{
			$oEvent->addError("password", ForthcomingEvent::AUTH_ERROR_NO_PASSWD,
												"User Id not defined",2);
			$result = false;
		}

		if ($result)
		{
			if (empty($args['userid']))
			{
				$oEvent->addError("userid", ForthcomingEvent::AUTH_ERROR_INVALID_USERID,
													"User Id not provided",3);
				$result = false;
			}
			if (empty($args['password']))
			{
				$oEvent->addError("password", ForthcomingEvent::AUTH_ERROR_INVALID_USERID,
													"password not provided",4);
				$result = false;
			}
		}

		$oLogin = new Login();

		if ($result)
		{
			if (! $oLogin->isValidUser($args['userid'], $args['password']))
			{
				$oEvent->addError("useridpassword", ForthcomingEvent::AUTH_ERROR_INVALID_LOGIN_CREDENTIALS,
													"Invalid user id and password combination",4);
				$result = false;
			}
		}

		if ($result)
		{
			if (! $oLogin->isRestEnabled($args['userid'], $args['password']))
			{
				$oEvent->addError("restenabled", ForthcomingEvent::AUTH_ERROR_REST_DISABLED,
													"user does not have access to REST update sevices",5);
				$result = false;
			}
		}

		return $result;
	}


	// Sermons and Talks
  public function handleSermonsAndTalks()
  {
      $this->writeAllSermonsAndTalks();
  }

 protected function writeAllSermonsAndTalks() 
 {
		$path = "";
		$qry = "SELECT	id, 
										filename,
										dateperformed, 
										series, 
										biblebook, 
										bibleref,
										title, 
										preacher, 
										description,
										groupno,
										itemno
				    FROM sermonstalks
				    ORDER BY groupno, itemno DESC";  


    $result = mysql_query($qry);
    if (! $result)
    {
          $this->throwError("Query failed", 9);
      return;
    }

		$this->request->writeElement('recordcount', mysql_num_rows($result));
		$path = htmlentities("http://" . WEBSITE_DOMAIN . "/" . "mp3s");
		$this->request->writeElement('path', $path);
    if (mysql_num_rows($result) == 0)
    {
      mysql_free_result($result);
      return;
    }

    /* Fetch the results of the query */
    while( $row = mysql_fetch_assoc($result) )
    {
      $this->writeSermonsAndTalksElement($row, $path);
    }

    /* Destroy the result set and free the memory used */
    mysql_free_result($result);

  }


  protected function writeSermonsAndTalksElement($row, $path)
  {
    $this->request->writeElementStart("sandt");
    $this->request->writeElement("id",          $row['id']);
    $this->request->writeElement("filename",   $row['filename']);
    $this->request->writeDateElement("dateperformed", $row['dateperformed']);
    $this->request->writeElement("series",   $row['series']);
    $this->request->writeElement("biblebook",     $row['biblebook']);
    $this->request->writeElement("bibleref",   $row['bibleref']);
    $this->request->writeElement("title",   $row['title']);
    $this->request->writeElement("preacher",   $row['preacher']);
    $this->request->writeElement("description",   ($row['description']));
    $this->request->writeElement("groupno",   $row['groupno']);
    $this->request->writeElement("itemno",   $row['itemno']);
    $this->request->writeElement("linkurl",
             htmlentities($path . "/" . $row['filename']));
    $this->request->writeElementEnd("sandt");

  }


} // End Class
  //========================================================================

?>
