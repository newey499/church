<?php
/*************************************************


class.allevents.php
	
02/10/2010	CDN		Display event details for any days shown in grid belonging
									to previous or following month	
10/03/2011  CDN   Create from calendar.php to produce xml output instead of html
10/03/2011  CDN   Add support for extracting events for a single day as well as
                  a calendar month
**************************************************/
require_once("../globals.php");;
require_once("../mysql.php");;

require_once('class.events.php');
require_once('class.rest-service.php');
require_once('class.rest.exception.php');

  /*******************
	mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	
*******************************/







/*****************************

Class calendar

Creates a table combining rows 
from forthcomingevents and regularevents tables
for a given month and year.

Temporary table is deleted by destructor

Subclass this object and override the getEventStr
method to change the html the object spits back.

CDN 		27/02/2010  Fix to ensure class honours isvisible flag setting on regular and forthcoming tables when loading
										a months events into the temporary calendar table.
CDN			01/06/2011	Allow selection of source tables
*************************************/
Class Calendar
{
	/******************
	 Properties
	*********************/
	private $month;
	private $year;
	private $day;
	// development switch to leave the temporary
	//table in place during testing
	// set false to create table - set true in production to use memory table
	private $useMemoryTable = false;

	// CDN			01/06/2011	Allow selection of source tables
	const TABLE_NONE = 0;
	const TABLE_FORTHCOMING = 1;
	const TABLE_REGULAR = 2;
	const TABLE_ALL = 3;

	private $sourceTableFlags = 0;

	function __construct($month, $year, $day = 1, $sourceTables = Calendar::TABLE_ALL)
	{
		$this->name  = "CalendarClass";
		$this->sourceTableFlags = $sourceTables;
		$this->day   = $day;   //  now supplied as arg - defaults to 1
		$this->month = intval($month);
		$this->year  = intval($year);
		$this->createTable();

		if ($this->sourceTableFlags & Calendar::TABLE_FORTHCOMING)	// Bitwise AND
		{
			$this->loadForthcomingevents();
			//throw new RestException("Debug - Calendar::TABLE_FORTHCOMING");
		}

		if ($this->sourceTableFlags & Calendar::TABLE_REGULAR)	// Bitwise AND
		{
			$this->loadRegularevents();
			//throw new RestException("Debug - Calendar::TABLE_REGULAR");
		}
		
	}

	function __destruct() 
	{
		if (! $this->useMemoryTable)
		{
			// Don't drop the table - it will only have been created as a table (as opposed
			// to an in-memory table) for debug purposes
			// $this->dropTable();
		}
	}


	/******************
	 Methods
	 **********************/

	public function getSourceTableFlags()
	{
		return $this->sourceTableFlags;
	}

	protected function createTable() 
	{
		$this->dropTable();

		if ($this->useMemoryTable)
		{
			$qry  =  "CREATE TEMPORARY TABLE calendar ";
		}
		else
		{
			$qry  =  "CREATE TABLE calendar ";
		}
		
		$qry .= "( ";
		$qry .= "  id integer NOT NULL auto_increment primary key, ";
		$qry .= "  parentid integer NOT NULL, ";
		$qry .= "	 eventsource enum('FORTHCOMING', 'REGULAR') NOT NULL, ";
		$qry .= "  eventdate date not null, ";
		$qry .= "  eventtime time NOT NULL COMMENT 'Timeof 11:11 (php global FC_HIDE_TIME) is flag for do not display time',  ";
		$qry .= "  eventname varchar(250) NOT NULL, ";
		$qry .= "  linkurl varchar(250), ";
		$qry .= "  isvisible enum('YES', 'NO') DEFAULT 'YES' ";		
		$qry .= ") TYPE=HEAP; ";

		if (mysql_query($qry) == FALSE)
		{
			die("Failed to create temporary calendar table");
		}

	}

	protected function dropTable()
	{
		if (mysql_query('DROP TABLE IF EXISTS calendar') == FALSE)
		{
      // Not a problem - table may not exist when this method is called
			//die("Failed to drop calendar table");
		}
	}


	protected function loadForthcomingevents()
	{

		$qry  = "INSERT INTO calendar ";
		$qry .= " (parentid, eventsource, eventdate, eventtime, eventname, linkurl, isvisible ) ";
		$qry .= "SELECT id, 'FORTHCOMING', eventdate, eventdate as eventtime, eventname, linkurl, isvisible ";
		$qry .= "FROM forthcomingevents ";
		$qry .= "WHERE ( ";
		$qry .= "        month(eventdate) = " . $this->month ;
		$qry .= "      )";
		$qry .= "      AND year(eventdate) = " . $this->year;
		$qry .= "      AND isvisible = 'YES' ";


		if (mysql_query($qry) == FALSE)
		{
			die("Failed to Import rows from forthcomingevents");
		}

	}

	protected function loadRegularevents()
	{
		$date = new DateTime();
		$endDate = new DateTime();

		$this->day   = '1';

		$endDate->setDate($this->year, $this->month + 1, $this->day);
		$endDate->modify("-1 day");  // pick up last seven days of previous month

		$date->setDate($this->year, $this->month, $this->day);

    /*****************
		print("<h2>" . "$count, this month[" . $this->month .
			  "] date month [" . date_format($date, 'm') . "] " . 
			  " termination date [" . date_format($endDate, 'Y-m-d') . "]</h2>\n");
    *********************/

		$count = 0;

		// while ( ( date_format($date, 'm') <= $this->month) || ( ($this->month + 1 ) == date_format($date, 'm'))  )
		while ( $this->processDate($date, $endDate)  )
		{
			for ($dow= 1; $dow <= 7; $dow++)
			{
				$this->getRegularEvent($date);
				$date->modify("+1 day");
        // Stop if gone past end of required month
        if (! $this->processDate($date, $endDate))
        {
          break;
        }

/***********
				print("$count, this month[" . $this->month .
					  "] date month [" . date_format($date, 'm') .
					  "] <br />\n");
****************/
				$count = $count + 1;



			}
		}


	}


	// $oDate - must be a datetime object
	protected function getRegularEvent($oDate)
	{
		$mysqlToday = date("Y-m-d");
		$str = "";
		$dow = strtoupper($oDate->format("l")); // MONDAY, TUESDAY etc
		$wom = $this->weekOfMonth($oDate);  // Week of month - 1,2 etc
		$oNullDate = new dateTime('0000-00-00');

		$qry = "SELECT id, dayofweek, weekofmonth, startdate, enddate, eventtime, eventname," . 
		       "       eventdesc, isvisible, linkurl " . 
	         " , DATEDIFF(startdate, '" . $mysqlToday . "') AS diffstart " . 
	         " , DATEDIFF(enddate, '" . $mysqlToday . "') AS diffend " . 
		       "FROM regularevents " . 
		       "WHERE " . 
					 "  ( " . 
					 "    startdate <= '" . $oDate->format("Y-m-d") . "' " . 
					 "  ) " .
					 "  AND " . 
					 "  ( " . 
					 "  	(enddate IS NULL) " . 
					 "  	OR (enddate IS NOT NULL AND " . 
					 "       ( enddate = '0000-00-00' OR '" . $oDate->format("Y-m-d") . "'  < enddate ) ) " . 
					 "  ) " . 
					 "  AND " .
					 "  ( " .
	         "    ( dayofweek = 'ALL' OR dayofweek = '" . $dow . "' ) " . 
					 "    AND " . 
					 "    ( weekofmonth = 'ALL' OR weekofmonth = 'WEEK" . $wom . "' ) " . 
					 "  ) " . 
					 "  AND " .
					 "  ( " .	
					 "    isvisible = 'YES' " . 				 
					 "  ) " . 					 
		       "ORDER BY eventtime";  

		$res = mysql_query($qry);

		if (mysql_query($qry) == FALSE)
		{
			die("Failed to Import rows from regularevents");
		}

		while ($row = mysql_fetch_assoc($res)) 
		{
			$qry  = "INSERT INTO calendar ";
			$qry .= " (parentid, eventsource, eventdate, eventtime, eventname, linkurl, isvisible) ";
			$qry .= "VALUES ";
			$qry .= " ( ";
			$qry .= " " . $row['id'] . ", ";
			$qry .= " 'REGULAR', ";
			$qry .= "'" . date_format($oDate, "Y-m-d") . "', ";
			$qry .= "'" . substr($row['eventtime'],0,5) . "', ";
			$qry .= "'" . $row['eventname'] . "', ";
			$qry .= "'" . $row['linkurl'] . "', ";
			$qry .= "'" . $row['isvisible'] . "' ";			
			$qry .= " ) ";

      //print("REGULAR Event date [". date_format($oDate, "Y-m-d") . "]<br />\n");
			if (mysql_query($qry) == FALSE)
			{
				die("Insert on calendar table failed");
			}

		}


		mysql_free_result($res);

		return $str;

	}


	// $oDate - must be a datetime object
	private function weekOfMonth($oDate)
	{
		$weekNo = ($oDate->format("d") / 7);
		$week = 0;

	  if ($weekNo <= 1)
			$week = 1;
		else if ($weekNo <= 2)
			$week = 2;
		else if ($weekNo <= 3)
			$week = 3;
		else if ($weekNo <= 4)
			$week = 4;
		else if ($weekNo <= 5)
			$week = 5;
		else if ($weekNo > 5)  // No months with more than 5 weeks
			die("Impossible Week Number [" . $weekNo .
          "] based on date [" . $oDate->format("Y-m-d") . "]");

		return $week;
	}



	// $oDate - must be a datetime object
	public function getEventStr(Events $oEvents, $oDate)
	{
		$str = "";
		$dow = strtoupper($oDate->format("l")); // Day of Week MONDAY, TUESDAY etc
		$wom = $this->weekOfMonth($oDate);  // Week of month - 1,2 etc

		$qry = " SELECT id, parentid, eventsource, eventdate, eventtime, eventname, " .
					 "        DATE_FORMAT(eventtime, '%l:%i %p') as disptime, linkurl " . 
		       " FROM calendar " . 
		       " WHERE eventdate = '" . date_format($oDate, 'Y-m-d') . "' " .
		       " ORDER BY eventtime";  
	
		$res = mysql_query($qry);


		while ($row = mysql_fetch_assoc($res)) 
		{
      $oEvents->request->writeElementStart("event");

      $oEvents->request->writeElement("id", $row['id']);
      $oEvents->request->writeDateElement("eventdate", $row['eventdate']);
      $oEvents->request->writeTimeElement("eventtime", substr($row['eventtime'],0,5));

			if ($row['eventsource'] == 'FORTHCOMING')
			{
        $oEvents->request->writeElement("eventsource", $row['eventsource']);
				if (! empty($row['linkurl']) )
				{
          if (strtoupper(substr($row['linkurl'], 0, 4)) == "HTTP")
          {
            $str = $row['linkurl'];
          }
          else
          {
            $str = "http://" . MYSQL_SERVER . "/" . $row['linkurl'];
          }
    
					$str .= "#articleid" . $row['parentid'];	// internal link on target page
          $oEvents->request->writeElement("linkurl", htmlentities($str));
				}

			}
      else if ($row['eventsource'] == 'REGULAR')
			{
        $oEvents->request->writeElement("eventsource", $row['eventsource']);  
				if (! empty($row['linkurl']) )
				{
          if (strtoupper(substr($row['linkurl'], 0, 4)) == "HTTP")
          {
            $str = $row['linkurl'];
          }
          else
          {
            $str = "http://" . MYSQL_SERVER . "/" . $row['linkurl'];
          }
          $oEvents->request->writeElement("linkurl", htmlentities($str));
				}
			}


      //  FC_HIDE_TIME (11:11) is a magic time that means do not display time
			if (substr($row['eventtime'],0,5) !=  FC_HIDE_TIME ) 
			{
				//$str .= substr($row['eventtime'],0,5) . "<br />\n";
				$str = $row['disptime'];
        $oEvents->request->writeElement("disptime", $str);
			}

      $oEvents->request->writeElement("eventname", $row['eventname']);



      $oEvents->request->writeElementEnd("event");
		}

    //print($str);

		mysql_free_result($res);

		return $str;
	}

/**********************
Expects two DateTime objects - returns true if $oDateCurrent <= $oDateEnd else returns false
******************************/
protected function processDate($oDateCurrent, $oDateEnd)
{
	$result = true;

	$current = date_format($oDateCurrent, 'Ymd');
	$end = date_format($oDateEnd, 'Ymd');

	$intResult = strcmp($current, $end);
	$result = ( $intResult <= 0 );

	//print("diff [" . $intResult . "]<br />\n");

	return $result;
}




  // ====================================================================
  public function writeAllEventsForMonth(Events $oEvents)
  {

    $mysqlToday = date("Y-m-d");

    $date = new DateTime();
    $endDate = new DateTime();
    $currentDate = new DateTime();

    $year  = $this->year;
    //$month = str_ireplace("month", "", $_POST['calmonth']) + date_format($date, 'm');
    $month = $this->month;
    $day   = '1';
    $date->setDate($year, $month, $day);

    $endDate->setDate($year, $month, $day);
    $endDate->modify("+1 month");
    $currentDate->setDate($year, $month, $day);

    $year  = date_format($date, 'Y');
    $month = date_format($date, 'm');
    $nMonth = date_format($date, 'n');
    $day   = '1';

    $oCalendar = new Calendar($month,$year);

    $wday = strtoupper(date_format($date, 'l'));
    $woffset = intval(date_format($date, 'w')) - 1;

    $date->modify("-" . $woffset . " day");
    $nMonth = date_format($date, 'n');

    $oDateToday = new DateTime();


    $oEvents->request->writeElementStart("alleventsyearmonth");
    $oEvents->request->writeElement("year",  $this->year);
    $oEvents->request->writeElement("month", $this->month);
    $oEvents->request->writeElementEnd("alleventsyearmonth");

    while (date_format($date, 'Ymd') < date_format($endDate, 'Ymd'))
    {
      // process 7 days of events
      for ($dow= 1; $dow <= 7; $dow++)
      {
        $tmp = $oCalendar->getEventStr($oEvents, $date);

        $date->modify("+1 day");

      }

    }
  }


  // ====================================================================
  public function writeRegularEventsForMonth(Events $oEvents)
  {

    $mysqlToday = date("Y-m-d");

    $date = new DateTime();
    $endDate = new DateTime();
    $currentDate = new DateTime();

    $year  = $this->year;
    //$month = str_ireplace("month", "", $_POST['calmonth']) + date_format($date, 'm');
    $month = $this->month;
    $day   = '1';
    $date->setDate($year, $month, $day);

    $endDate->setDate($year, $month, $day);
    $endDate->modify("+1 month");
    $currentDate->setDate($year, $month, $day);

    $year  = date_format($date, 'Y');
    $month = date_format($date, 'm');
    $nMonth = date_format($date, 'n');
    $day   = '1';

    $oCalendar = new Calendar($month,$year);

    $wday = strtoupper(date_format($date, 'l'));
    $woffset = intval(date_format($date, 'w')) - 1;

    $date->modify("-" . $woffset . " day");
    $nMonth = date_format($date, 'n');

    $oDateToday = new DateTime();

    $oEvents->request->writeElementStart("alleventsyearmonth");
    $oEvents->request->writeElement("year",  $this->year);
    $oEvents->request->writeElement("month", $this->month);
    //$oEvents->request->writeElement("day", "0");
    $oEvents->request->writeElementEnd("alleventsyearmonth");
		
    while (date_format($date, 'Ymd') < date_format($endDate, 'Ymd'))
    {
      // process 7 days of events
      for ($dow= 1; $dow <= 7; $dow++)
      {
        $tmp = $oCalendar->getEventStr($oEvents, $date);

        $date->modify("+1 day");

      }

    }
  }

  // ====================================================================
  public function writeAllEventsForDay(Events $oEvents, $day)
  {

    $date = new DateTime();

    $date->setDate($this->year, $this->month, $day);

    $oEvents->request->writeElementStart("alleventsyearmonthday");
    $oEvents->request->writeElement("year",  $this->year);
    $oEvents->request->writeElement("month", $this->month);
    $oEvents->request->writeElement("day", $day);
    $oEvents->request->writeElementEnd("alleventsyearmonthday");

    $oCalendar = new Calendar($this->month, $this->year);
    $oCalendar->getEventStr($oEvents, $date);

  }


  // ====================================================================
  public function writeRegularEventsForDay(Events $oEvents, $day)
  {

    $date = new DateTime();

    $date->setDate($this->year, $this->month, $day);

    $oEvents->request->writeElementStart("regulareventsyearmonthday");
    $oEvents->request->writeElement("year",  $this->year);
    $oEvents->request->writeElement("month", $this->month);
    $oEvents->request->writeElement("day", $day);
    $oEvents->request->writeElementEnd("regulareventsyearmonthday");

    $oCalendar = new Calendar($this->month, $this->year, 1, Calendar::TABLE_REGULAR);
    $oCalendar->getEventStr($oEvents, $date);

  }


} // End Class Calendar
  // =========================================================================



//==================================================================



//==================================================================
//==================================================================


?>
