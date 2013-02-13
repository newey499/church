<?php
/***************************************************************

Chris Newey

12/01/2005

class mysqlj - enhancements to mysqlj

Constants taken from C source code headers 
for MySQL 4.17


Modification History
====================

Date			Programmer							Description
12/01/05	CDN						Created
25/02/05 CDN						Written for PHP 5  - make compatable with 4.3.3
****************************************************************/
class mysqlj {

// MySQL Error Codes
var $MYSQL_DUPLICATE_KEY					= 1062;
	
	
	
var $MYSQL_NOT_NULL_FLAG 				=		  1;		// Field can't be NULL 
var $MYSQL_PRI_KEY_FLAG 					= 	  2;		// Field is part of a primary key 
var $MYSQL_UNIQUE_KEY_FLAG 			=  	  4;		// Field is part of a unique key 
var $MYSQL_MULTIPLE_KEY_FLAG 		=  	  8;		// Field is part of a key 
var $MYSQL_BLOB_FLAG 						=	 	 16;		// Field is a blob 
var $MYSQL_UNSIGNED_FLAG 				=  	 32;		// Field is unsigned 
var $MYSQL_ZEROFILL_FLAG 				=  	 64;		// Field is zerofill 
var $MYSQL_BINARY_FLAG 					= 	128;		// Field is binary   

// The following are only sent to new clients 
var $MYSQL_ENUM_FLAG						 	=    256;		// field is an enum 
var $MYSQL_AUTO_INCREMENT_FLAG 	=    512;		// field is a autoincrement field 
var $MYSQL_TIMESTAMP_FLAG 				=   1024;		// Field is a timestamp 
var $MYSQL_SET_FLAG 							=   2048;		// field is a set 
var $MYSQL_NUM_FLAG 							=  32768;		// Field is num (for clients) 
var $MYSQL_PART_KEY_FLAG 				=  16384;		// Intern; Part of some key 
var $MYSQL_GROUP_FLAG 						=	 32768;		// Intern: Group field 
var $MYSQL_UNIQUE_FLAG 					=	 65536;		// Intern: Used by sql_yacc 
var $MYSQL_BINCMP_FLAG 					=	131072;		// Intern: Used by sql_yacc 

var $MYSQL_TYPE_DECIMAL 					=  		0;
var $MYSQL_TYPE_TINY 						=			1;		// type of integer	
var $MYSQL_TYPE_SHORT 						=			2;		// type of integer	
var $MYSQL_TYPE_LONG 						=			3;		// type of integer	
var $MYSQL_TYPE_FLOAT 						=			4;		// type of real	
var $MYSQL_TYPE_DOUBLE 					=			5;		// type of real	
var $MYSQL_TYPE_NULL 						=			6;
var $MYSQL_TYPE_TIMESTAMP 				= 		7;
var $MYSQL_TYPE_LONGLONG 				= 		8;
var $MYSQL_TYPE_INT24 						= 		9;
var $MYSQL_TYPE_DATE 						= 	 10;
var $MYSQL_TYPE_TIME 						= 	 11;
var $MYSQL_TYPE_DATETIME 				= 	 12;
var $MYSQL_TYPE_YEAR 						= 	 13;
var $MYSQL_TYPE_NEWDATE 					= 	 14;
var $MYSQL_TYPE_ENUM 						= 	247;
var $MYSQL_TYPE_SET							= 	248;
var $MYSQL_TYPE_TINY_BLOB 				= 	249;
var $MYSQL_TYPE_MEDIUM_BLOB 			= 	250;
var $MYSQL_TYPE_LONG_BLOB 				= 	251;
var $MYSQL_TYPE_BLOB 						= 	252;
var $MYSQL_TYPE_VAR_STRING 			= 	253;			// VARCHAR
var $MYSQL_TYPE_STRING 					= 	254;
var $MYSQL_TYPE_GEOMETRY 				= 	255;

function isAutoIncrement($result, $fieldName) {
	$fldNo = $this->field_no($result, $fieldName);
	$meta = $result->fetch_field_direct($fldNo);	
	return ($meta->flags & $MYSQL_AUTO_INCREMENT_FLAG);
}

function isNotNull($result, $fieldName) {
	$fldNo = $this->field_no($result, $fieldName);
	$meta = $result->fetch_field_direct($fldNo);	
	return ($meta->flags & $MYSQL_NOT_NULL_FLAG);
}

function isPrimaryKey($result, $fieldName) {
	$fldNo = $this->field_no($result, $fieldName);
	$meta = $result->fetch_field_direct($fldNo);	
	return ($meta->flags & $MYSQL_PRI_KEY_FLAG);
}

function isEnumerated($result, $fieldName) {
	$fldNo = $this->field_no($result, $fieldName);
	$meta = $result->fetch_field_direct($fldNo);	
	return ($meta->flags & $MYSQL_ENUM_FLAG);
}



// Returns a column number given a result set and the colum name
function field_no($result, $fieldName) {

	for ($i = 0; $i < $result->field_count ; $i++) {
		$result->field_seek($i);
		$finfo = $result->fetch_field();
		if (strToUpper($fieldName) == strToUpper($finfo->name)) 
			return($i);
	}
	die("Error -  field $fieldName not in result set");
}


// Note $default is passed by reference
function getEnumValues($table,$column, &$default) 
{
	
	$retval = NULL;
	
	global $dbHandle;

	/********
	$dbHandle = new mysqlj(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE)
		   or die("Could not connect to server: MYSQL_SERVER : Database MYSQL_DATABASE " . mysqlj_error($this->dbHandle));
  **************/
	$queryString = "SHOW COLUMNS FROM `" . $table . "` LIKE '" . $column . "'";
	$query=mysql_query($queryString);
	
	if(mysql_num_rows($query) > 0)
	{
		$row=mysql_fetch_assoc($query);
		$retval=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row['Type']));
		$default = $row['Default'];	// Default is passed by reference offset 4 of the row array is the default value
												        // of the enumerated field
	}	
	
	mysql_freeresult($query);

	
	return $retval;
		
}





} // End class definition mysqlj

?>
