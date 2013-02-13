<?php
/***************************************************************

Chris Newey

12/01/2005

class mysqdatetime

utility object to manipulate format of dates and times between
uk and mysql formats.



Modification History
====================

Date			Programmer							Description
12/01/05	CDN						Created
25/02/05 CDN						Written for PHP 5 - take it back to 4.3
****************************************************************/

/****
Splits a MySQL date time field into a date of the format DD/MM/YYYY
and a time of the format HH:MM

Note $ddmmyyyy and $hhmm contain the extracted values and are passed by reference
*********/
function splitMySqlDateTime($dateTime, &$ddmmyyyy, &$hhmm)
{
	$ddmmyyyy = substr($dateTime,0,10);
	$hhmm = substr($dateTime,11,5);
	$oDate = new mysqldate();

	$oDate->date = $ddmmyyyy;

	$ddmmyyyy = $oDate->MySqlDateToUk($ddmmyyyy);

	return $hhmm . " " . $ddmmyyyy;
}


/***************
 Handles dates - MySQL and UK format
******************************/
class mysqltime {

var $sqltime;

// =================================================
// Validates time as HH:MM
function checkTime($time) {

	$time = trim($time);
	if (strlen($time) != 5) {
		return false;
	}


	/*
	24 hr time validate function

      	0[0-9]        ----> can enter 00,01,...09
	or    1[0-9]        ----> can enter 10,11,...19
	or    2[0-3]        ----> can enter 20,21,...23
      	:             ----> then must have colon
      	[0-5][0-9]    ----> then can enter 00,01,...59
	*/

	if (ereg("((^0[0-9]|1[0-9]|2[0-3]):[0-5][0-9])", $time)) {
		$this->sqltime = $time;
		$retval = True;
	}
	else {
		$this->sqltime = NULL;
		$retval = False;
	}

	return $retval;
}


}	// end of class mysqltime


/***************
 Handles dates - MySQL and UK format
******************************/
class mysqldate {


var $MYSQL_DATE                  = 1;
var $UK_DATE                     = 2;

var $date;	// date in MySQL format - YYYY-MM-DD

// ===============================================
// Synonym for isValidDate($dateType, $date) defined below
function  storeDate($dateType, $date) {
	return $this->isValidDate($dateType, $date);
}

function fmtDateTime($dateType,$date,$time = '') {
	$tObj = new mysqltime;
	$retval = NULL;

	if ($this->isValidDate($dateType,$date) && (empty($time) ? true : $tObj->checkTime($time)) )
	{
		 if ($dateType == $this->UK_DATE) {
			 $date = $this->UkDateToMySql($date);
		 }
		 if (! empty($time) )
		 {
		 	 $retval = $date . " " . $time . ':00';	// MySQL requires tenths and hundredths of a second
		 }
		 else
		 {
		 	 $retval = $date ;	// MySQL Date only
		 }
	}

	return $retval;
}

// ===============================================
// Checks a MySQL or UK date
// If the date is valid its stored in the self::date property
function isValidDate($dateType, $date) {

	$retval = false;


		switch ($dateType) {

			case $this->MYSQL_DATE:
				if ($retval = $this->checkMySQLdate($date)) {
					$this->date = $date;
				}
				else {
					$this->date = NULL;
				}
				break;

			case $this->UK_DATE:
				if ($retval = $this->checkUKdate($date)) {
					$this->date = $this->UkDateToMySql($date);
				}
				else
					$this->date = NULL;
				break;

			default:
				die("XX $dateType does not represent a valid date type");
				break;
		}




	return $retval;
}


// =================================================
// Validates date as valid uk date
// Expects date to be passed as DD/MM/YYYY
// Date delimiters - usually "/" are ignored
function checkUKdate($date) {



$date = trim($date);
if (strlen($date) != 10) {
	return false;
}

// check for numerics and slashes
//            D    D  /  M    M  /  Y    Y    Y    Y
if (!ereg("^[0-9][0-9]/[0-9][0-9]/[0-9][0-9][0-9][0-9]",$date)) {
	return false;
}

$day 	 = (int) substr($date,0,2);
$month = (int) substr($date,3,2);
$year	 = (int) substr($date,6,4);

return checkdate($month, $day, $year);
}

// ============================================
// Validates date as valid MySQL date
// Expects date to be passed as YYYY-MM-DD
function checkMySQLdate($date) {

$date = trim($date);
if (strlen($date) != 10) {
	return false;
}

// check for numerics and dashes
//            Y    Y    Y    Y  -  M    M  -  D    D
if (!ereg("^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]",$date)) {
	return false;
}

$day 	 = (int) substr($date,8,2);
$month = (int) substr($date,5,2);
$year	 = (int) substr($date,0,4);

return checkdate($month, $day, $year);
}



// Converts DD/MM/YYYY to YYYY-MM-DD
function UkDateToMySql($ukDate) {

if (! $this->checkUKdate($ukDate)) {
	return false;
}

$day 	 = substr($ukDate,0,2);
$month = substr($ukDate,3,2);
$year	 = substr($ukDate,6,4);

return ($year . '-' . $month . '-' . $day);
}

// Converts YYYY-MM-DD to DD/MM/YYYY
function MySqlDateToUk($mysqlDate) {

if (! $this->checkMySQLdate($mysqlDate)) {
	return false;
}


$day 	 = substr($mysqlDate,8,2);
$month = substr($mysqlDate,5,2);
$year	 = substr($mysqlDate,0,4);


return ($day . '/' . $month . '/' . $year);
}

} // End of class mysqldate







?>
