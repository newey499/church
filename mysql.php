<?php
/*************************

	Functions useful for working with MySQL databases
  =================================================

Modification History - Code affected marked by comments with programmer's initials and date
===========================================================================================

	Date			Programmer											Description
22/11/04 	CDN						Created
08/12/04	CDN						mysqli field object returns garbage for the max_length for varchar. 
												Bug notified and to be fixed.
												Also mysqli object is to be enhanced to return the columns design 
			




	
***************************************/
define("INSERT_REC",    "I");
define("UPDATE_REC",    "U");
define("DELETE_REC",    "D");
define("VALIDATE_WRITE_REC",			"W");
define("PAGE_UP",       "PU");
define("PAGE_DOWN",     "PD");

// Enhancement to mysqli object
require_once("mysqlj.php");

// UK/MySQL Date and Time handling objects
require_once("mysqldatetime.php");

// These values are returned by MySQL version 4.17
//			$meta = mysqli_fetch_field_direct($this->result, $i);
//			$ftype = $meta->type;
require_once("mysql_header.php");

// Enhanced Exception object
// no exceptions in php 4
//require_once("exception.php");



// Various utility functions
require_once("genlib.php");


// Returns field number given resource object and field name
if (! function_exists('mysql_field_no')) {
function mysql_field_no($result, $fieldname) {
	$res =  -1;
	
	for ($i = 0; $i < mysql_num_fields($result) ; $i++) {

		$finfo = mysql_fetch_field($result, $i);
		
		if (strToUpper($fieldname) == strToUpper($finfo->name)) {
			$res = $i;
			break;
		}
	}
	if ($res== -1) {
		die("function my_sql_field_no() - Error -  field $fieldname not in result set" . 
		    "<br>no of fields = $result->field_count arg name $fieldname");
	}
	
	return($res);
}
}

/*****************
 Changes dd/mm/yyyy to yyyy-mm-dd
*********************/
if (! function_exists('ddmmyyyyToyyyymmdd')) {
function ddmmyyyyToyyyymmdd($d) {
	return (substr($d,6,4) . '-' . substr($d,3,2) . '-' .substr($d,0,2));
}
}

/*****************
 Changes yyyy-mm-dd to dd/mm/yyyy
*********************/
if (! function_exists('yyyymmddToddmmyyyy')) {
function yyyymmddToddmmyyyy($d){
	return (substr($d,8,2) . '/' . substr($d,5,2) . '/' .substr($d,0,4));
}
}

?>