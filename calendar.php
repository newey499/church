<?php
/*************************************************

	calendar.php
	
02/10/2010	CDN		Display event details for any days shown in grid belonging
									to previous or following month	
12/12/11		CDN   Load the forthcoming events first. A Regular Event with the same event date and time
									as a forthcoming event date and time is ignored. This ensures that Xmas and Easter services
									override regular services causing the rgular sevices not to be displayed. This logic assumes that the 
									Church will not schedule a regular event and a one off (forthcoming) event at the same date and time.
29/12/11		CDN   Fix bug that caused first week of month to display incorrectly when first day of month was on a Sunday.
20/04/12		CDN   //$qry .= ") TYPE=HEAP; "; // CDN 20/4/12 - new version of MySQL doesn't support this syntax - new version 										ENGINE=MEMORY
**************************************************/
?>

<?php
	require_once "globals.php";
	require_once "mysql.php";

	mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			or die('Could not connect: ' . mysql_error());

	mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');	

?>

<br />

<h2>Whats on Calendar</h2>




<p>


<?php

	//print("<h1> SESSION [" . getSiteMainPage() . "]</h1>\n");

	/**************************
	print("<form  name=\"calform\" \n");

	// internal anchor #today gets ignored for any month not the current month 'cos the anchor won't exist
	print(" action=\"http://"  . WEBSITE_DOMAIN . "/index.php?displaypage=calendar.php#today\" \n");
	//print(" method=\"post\"> \n");
	print(" method=\"get\"> \n");	
	
	print(" <input type=\"hidden\" name=\"displaypage\" value=\"calendar.php\" >\n");
	print( printCalendarComboBox(6) );
	print("&nbsp;");
	print("<input type=\"submit\" value=\"Change Calendar Month\" " . 
				" onclick=\"alert('calmonth [' + document.getElementById('calmonth').value + ']'); return false; \" >\n");

	print("</form>\n");
	***************************************/
	print("<br />\n");	
	print("<b>Select Month </b>\n");
// TODO Debug	print( printCalendarComboBox(3) );	
	print( printCalendarComboBox(6) );	

?>

</p>

<br />
<br />

<p>

<table style="width:70%;">

	<tr>

	<td style="width:20%;">
			<a href="index.php?displaypage=internal.html&rowid=18">
			Weekly Church Services
			</a>
	</td>

	<td style="width:20%;">
		<a href="index.php?displaypage=dispforthevent.php">
		Forthcoming Events in Detail
		</a>
	</td>

	</tr>

</table>


</p>

<?php

	$mysqlToday = date("Y-m-d");

	$date = new DateTime();
	$endDate = new DateTime();
	$currentDate = new DateTime();

	$year  = date_format($date, 'Y');
	// current month is "month0", next month is "month1". So this adds the required month to the current month
	// to arrive at the correct month number (which may be greater than 12 - see below.	
	// if (! isset($_POST['calmonth']))
	// {
	// 	$_POST['calmonth'] = "month0";
	// }
	//$month = str_ireplace("month", "", $_POST['calmonth']) + date_format($date, 'm');
	if (! isset($_GET['calmonth']))
	{
		$_GET['calmonth'] = "month0";
	}
	$month = str_ireplace("month", "", $_GET['calmonth']) + date_format($date, 'm');	
	$day   = '1';
	// setDate is clever - it handles months > 12 by adding a year and using mod 12 on the month
	// Eg. 	$date->setDate($year, $month, $day) -> 	$date->setDate(2011, 14, 01) -> 2012-02-01
	$date->setDate($year, $month, $day);

	$endDate->setDate($year, $month, $day);
	$endDate->modify("+1 month");
	$currentDate->setDate($year, $month, $day);

	$year  = date_format($date, 'Y');
	$month = date_format($date, 'm');
	$nMonth = date_format($date, 'n');
	$day   = '1';



	$oCalendar = new Calendar($month,$year);

	print("<br />\n");

	$wday = strtoupper(date_format($date, 'l'));
	$woffset = intval(date_format($date, 'w')) - 1;

	if ($woffset > 0)
	{
		$date->modify("- " . $woffset . " day");
	}
	else
	{
		$date->modify("+ " . abs($woffset) . " day");
		// 29/12/11		CDN   Below lines fixes bug that caused first week of month to
		// display incorrectly when first day of month was on a Sunday.
		$date->modify("- " . abs($woffset) . " week");
	}
	$nMonth = date_format($date, 'n');

	print("<h2>" . date_format($currentDate, "F Y") . "</h2>\n");


?>

<p>

<table class="calendar">


<?php

	$oDateToday = new DateTime();

	while (date_format($date, 'Ymd') < date_format($endDate, 'Ymd'))
	{
		printDayHeader();		// Monday Tuesday etc.	
	
		print("<tr  class='calendar'>\n");

		// process 7 days of events
		for ($dow= 1; $dow <= 7; $dow++)
		{
			// grey out any dates not in selected month
			if (date_format($currentDate, 'm') != date_format($date, 'm') )
			{
				print("<td  class='calendardim' style='text-align:center;' >\n");
				//print($date->format("d-m-Y") . "\n");
				//print("<br />\n");
				//print("<br />\n");
			}
			else
			{
				print("<td  class='calendar'  style='text-align:center;'>\n");
			}
			
			if (date_format($date, "Ymd") == date_format($oDateToday, "Ymd"))
			{
				print("<a name='today' id='anchortoday' ></a> \n");
				print("<span style='color:red;'\n");
				print("<b>Today <br />" . $date->format("d-m-Y") . "</b> \n");
				print("</span> \n");
			}
			else
			{
				print($date->format("d-m-Y") . "\n");
			}
			print("<br />\n");

			$tmp = $oCalendar->getEventStr($date);
			if (empty($tmp))
			{
				$tmp = "<br />\n";
				$tmp = "<br />\n";
			}
			print("<span style='font-size:0.9em; text-align:left' >\n");
			print($tmp . "\n");
			print("</span>\n");

				
			print("</td>\n");

			$date->modify("+1 day");

		}

		print("</tr>\n");
	}

?>

</table>


</p>

<br />


<?php



// $oDate - must be a datetime object
function weekOfMonth($oDate)
{
	$weekNo = ($oDate->format("d") / 7);
	$week = 0;

  if ($weekNo <= 1)
	{
		$week = 1;
	}
	else if ($weekNo <= 2)
	{
		$week = 2;
	}
	else if ($weekNo <= 3)
	{
		$week = 3;
	}
	else if ($weekNo <= 4)
	{
		$week = 4;
	}
	else if ($weekNo <= 5)
	{
		$week = 5;
	} else if ($weekNo <= 6)
	{
		$week = 6;
	}

	return $week;
}





?>


<?php

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
*************************************/
Class Calendar
{
	/******************
	 Properties
	*********************/
	private $month;
	private $year;
	private $day;
	private $useTempTable = TRUE;   // Use a temporary table for live use
	//private $useTempTable = FALSE;  // development switch to leave the temporary table in existence during testing

	function __construct($month, $year) 
	{
		$this->name  = "CalendarClass";
		$this->day   = 1;
		$this->month = intval($month);
		$this->year  = intval($year);

		// print("<h1>month [$month] year [$year]</h1>");

		/********
			CDN 12/12/11
			Load the forthcoming events first. A Regular Event with the same event date and time
			as a forthcoming event date and time is ignored. This ensures that Xmas and Easter services
			override regular services causing the rgular sevices not to be displayed.
		*****************/
		$this->createTable();
		$this->loadForthcomingevents();	
		$this->loadRegularevents();
		//mysql_query("CALL buildCalendarTableRows('" . $year . "-" . $month . "-01')");


	}

	function __destruct() 
	{
		if ($this->useTempTable)
		{
			$this->dropTable();
		}
	}


	/******************
	 Methods
	*********************/

	protected function createTable() 
	{
		$this->dropTable();

		if ($this->useTempTable)
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
		//$qry .= ") TYPE=HEAP; "; // CDN 20/4/12 - new version of MySQL doesn't support this syntax
		$qry .= ") ENGINE=MEMORY; ";

		if (mysql_query($qry) == FALSE)
		{
			die("Failed to create temporary calendar table");
		}

	}

	protected function dropTable()
	{
		if (mysql_query('DROP TABLE IF EXISTS calendar') == FALSE)
		{
			die("Failed to drop calendar table");
		}
	}


	protected function loadForthcomingevents()
	{
		/*************
		02/08/2010	CDN		Get following month as well so as to display events at start of 
											following month instead of leaving blank
		************************/
		$qry  = "INSERT INTO calendar ";
		$qry .= " (parentid, eventsource, eventdate, eventtime, eventname, linkurl, isvisible ) ";
		$qry .= "SELECT id, 'FORTHCOMING', eventdate, eventdate as eventtime, eventname, linkurl, isvisible ";
		$qry .= "FROM forthcomingevents ";
		$qry .= "WHERE ( ";
		$qry .= "        month(eventdate) = " . $this->month ;
		$qry .= "        OR "; 
		$qry .= "        month(eventdate) + 1 = " . $this->month + 1;		
		$qry .= "      )";
		//$qry .= "      AND year(eventdate) = " . $this->year;
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

		// 15/12/2011 CDN Pick up first seven days of next month
		//$endDate->setDate($this->year, $this->month + 1, $this->day);
		$endDate->setDate($this->year, $this->month + 1, 7);

		//$date->setDate($this->year, $this->month, $this->day);
		$date->setDate($this->year, $this->month, 1);
		$date->modify("-7 day");  // pick up last seven days of previous month

		/********
		print("$count, this month[" . $this->month .
			  "] date month [" . date_format($date, 'm') . "] " . 
			  " termination date [" . date_format($endDate, 'Y-m-d') . "]<br />\n");
		*************/
		$count = 0;

		// while ( ( date_format($date, 'm') <= $this->month) || ( ($this->month + 1 ) == date_format($date, 'm'))  )
		while ( processDate($date, $endDate)  )
		{
			for ($dow= 1; $dow <= 7; $dow++)
			{
				$this->getRegularEvent($date);
				$date->modify("+1 day");

				/*******
				print("$count, this month[" . $this->month .
					  "] date month [" . date_format($date, 'm') .
					  "] <br />\n");
				**********/
				$count = $count + 1;



			}
		}


	}


	// $oDate - must be a datetime object
	protected function getRegularEvent($oDate)
	{
		$str = "";
		$dow = strtoupper($oDate->format("l")); // MONDAY, TUESDAY etc
		$wom = $this->weekOfMonth($oDate);  // Week of month - 1,2 etc
		$oNullDate = new dateTime('0000-00-00');
		$mySqlDate = $oDate->format("Y-m-d");					// YYYY-MM-DD

		$qry = "SELECT id, dayofweek, weekofmonth, startdate, enddate, eventtime, eventname," . 
		       "       eventdesc, isvisible, linkurl " . 
	         //" , DATEDIFF(startdate, '" . $mysqlToday . "') AS diffstart " . 
	         //" , DATEDIFF(enddate, '" . $mysqlToday . "') AS diffend " . 
	         " , DATEDIFF(startdate, '" . $oDate->format("Y-m-d") . "') AS diffstart " . 
	         " , DATEDIFF(enddate, '" . $oDate->format("Y-m-d") . "') AS diffend " . 
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
			//print_r($row);


			// 12/12/11		CDN   A Regular Event with the same event date and time
			// 									as a forthcoming event is ignored. This ensures that Xmas and Easter services
			// 									override regular services causing the rgular sevices not to be displayed.

			$qryForthcomingExists = sprintf("SELECT * FROM forthcomingevents " .
			  															" WHERE date(eventdate) = '%s' " .
    																	"       AND time(eventdate) = '%s' ",
																			date_format($oDate, "Y-m-d"),
																			$row['eventtime']											
																		 );

			$resForthCheck = mysql_query($qryForthcomingExists);

			if (mysql_num_rows($resForthCheck) == 0)
			{

				$qry  = "INSERT INTO calendar ";
				$qry .= " (parentid, eventsource, eventdate, eventtime, eventname, linkurl, isvisible) ";
				$qry .= "VALUES ";
				$qry .= " ( ";
				$qry .= " " . $row['id'] . ", ";
				$qry .= " 'REGULAR', ";
				$qry .= "'" . date_format($oDate, "Y-m-d") . "', ";
				$qry .= "'" . $row['eventtime'] . "', ";
				$qry .= "'" . $row['eventname'] . "', ";
				$qry .= "'" . $row['linkurl'] . "', ";
				$qry .= "'" . $row['isvisible'] . "' ";			
				$qry .= " ) ";

				if (mysql_query($qry) == FALSE)
				{
					die("Insert on calendar table failed");
				}

			}

		}

		mysql_free_result($resForthCheck);
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
	public function getEventStr($oDate)
	{
		$str = "";
		$dow = strtoupper($oDate->format("l")); // Day of Week MONDAY, TUESDAY etc
		$wom = weekOfMonth($oDate);  // Week of month - 1,2 etc

		$qry = " SELECT id, parentid, eventsource, eventdate, eventtime, eventname, " .
					 "        DATE_FORMAT(eventtime, '%l:%i %p') as disptime, linkurl " . 
		       " FROM calendar " . 
		       " WHERE eventdate = '" . date_format($oDate, 'Y-m-d') . "' " .
		       " ORDER BY eventtime";  
	
		if (! $res = mysql_query($qry))
		{
			die("<h1>SELECT on calendar table failed</h1>");
		}

		while ($row = mysql_fetch_assoc($res)) 
		{
			$id = "id" . $row['id'];
			if ($row['eventsource'] == 'FORTHCOMING')
			{
				print("<div id=\"" . $id . "\" class=\"tip\">" . "Click to see event details" . "</div>\n");
				if (! empty($row['linkurl']) )
				{
					$str .= "<a href='" . $row['linkurl'];
					$str .= "#articleid" . $row['parentid'] . "' ";	// internal link on target page
				}
				else
				{
					$str .= "<a href='" . "index.php?displaypage=dispforthevent.php";
					$str .= "#articleid" . $row['parentid'] . "' ";	// internal link on target page
				}

				$str .= " onmouseout='popUp(event,\"" . $id . "\")'" . 
								" onmouseover='popUp(event,\"" . $id . "\")'  > \n"; // onclick='return false'
			} else if ($row['eventsource'] == 'REGULAR')
			{ 
				if (! empty($row['linkurl']) )
				{
					print("<div id=\"" . $id . "\" class=\"tip\">" . "Click to see event details" . "</div>\n");
					$str .= "<a href='" . $row['linkurl'] . "' ";
					$str .= " onmouseout='popUp(event,\"" . $id . "\")'" . 
									" onmouseover='popUp(event,\"" . $id . "\")' > \n"; // onclick='return false'
				}
			}

			$str .= "<h4>\n";
      //  FC_HIDE_TIME (11:11) is a magic time that means do not display time
			if (substr($row['eventtime'],0,5) !=  FC_HIDE_TIME ) 
			{
				//$str .= substr($row['eventtime'],0,5) . "<br />\n";
				$str .= $row['disptime'] . "<br />\n";
			}
			$str .= $row['eventname'] . "<br />\n";
			$str .= "</h4>\n";
			if ($row['eventsource'] == 'FORTHCOMING')
			{
				$str  .= "</a>\n";
			}	else if ($row['eventsource'] == 'REGULAR')
			{ // don't use a tool tip for regular events
				//$str  .= "</a>\n";
			}
		}

		mysql_free_result($res);

		return $str;
	}





} // End Class Calendar
  // =========================================================================
?>


<?php
//==================================================================

/****************

Produces an html combox box with options to select a 
month from the current monthe to current month + $noOfMonths

called from calendar.php
*********************************************/
function printCalendarComboBox($noOfMonths = 2)
{
	$date = new DateTime();
	$finput = "";
	
	$finput =  "<select name=\"calmonth\"";
	$finput .= " id=\"calmonth\" ";
	//$finput .= " onchange=\"alert('cbx calmonth [' + document.getElementById('calmonth').value + ']'); return false; \" ";
	$finput .= " onchange=\"changeCalendarMonth(document.getElementById('calmonth').value, ";
	$finput .= " '" . getSiteMainPage() . "' ); return false; \" ";	
	$finput .= " >\n";

	for ($i = 0; $i < $noOfMonths; $i++)
	{
		// $finput .= $date->format("d-m-Y H:i:s") . "<br />\n";
		$month = "month" . $i;

//debug TODO
$tempMonth = $month;
//print("<h2>month [" . $month . "] tempmonth [" . $tempMonth . "]</h2>");

		$finput .= "<option value=\"" . $month . "\" ";

		//if (isset($_POST['calmonth']) )
		if (isset($_GET['calmonth']) )		
		{
			//if ($month == $_POST['calmonth']) 
			if ($month == $_GET['calmonth']) 			
			{
				$finput = $finput . " selected ";  
			}
		}


		$finput .= " >";
		$finput .= $date->format("M Y") . "</option>\n";

		//print("<h2>printed month [" . $month . "] tempmonth [" . $tempMonth . "] " .
    //  "[" . $finput . "] addedmonth = [" . $i . "]</h2>");

		$date->modify("+1 month");

	}

	$finput = $finput . "</select>\n\n";

	
	return $finput;
}

//==================================================================


//==================================================================
function printDayHeader()		// Monday Tuesday etc. in a table row
{

	$str =	'<tr class="calendar">
							<th  class="calendar" >
					      <b>
								Monday
					      </b>
							</th>
							<th  class="calendar" >
								<b>
								Tuesday
								</b>
							</th>
							<th  class="calendar" >
								<b>
								Wednesday
								</b>
							</th>
							<th  class="calendar" >
								<b>
								Thursday
								</b>
							</th>
							<th  class="calendar" >
								<b>
								Friday
								</b>
							</th>
							<th  class="calendar" >
								<b>
								Saturday
								</b>
							</th>
							<th  class="calendar" >
								<b>
								Sunday
								</b>
							</th>
					</tr>';
					
	print $str;
}
//==================================================================


//==================================================================
/**********************
Expects two DateTime objects - returns true if $oDateCurrent <= $oDateEnd else returns false
******************************/
function processDate($oDateCurrent, $oDateEnd)
{
	$result = true;

	$current = date_format($oDateCurrent, 'Ymd');
	$end = date_format($oDateEnd, 'Ymd');

	$intResult = strcmp($current, $end);
	$result = ( $intResult <= 0 );

	//print("diff [" . $intResult . "]<br />\n");

	return $result;
}


//==================================================================


//==================================================================
function getSiteMainPage()
{
	$result = "error.php";
	if (isset($_SESSION['siteMainPage']))
	{
		$result = $_SESSION['siteMainPage'];
	}
	else
	{
		$result = "index.php";
	}

	//print("<h1>getSiteMainPage [" . $result  . "]</h1>\n");

	return $result;
}
//==================================================================


?>






