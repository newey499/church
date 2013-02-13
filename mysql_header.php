<?php
/**********************************

mysql_header.php

Chris Newey	08/12/04

Constants taken from C source code headers 
for MySQL 4.17

08/12/04	CDN		Added prefix of "MYSQL_" to some
					constants for consistency and to avoid
					(hopefully) name clashes
***************************************************/


define("MYSQL_NOT_NULL_FLAG",		  1);		/* Field can't be NULL */
define("MYSQL_PRI_KEY_FLAG",		  2);		/* Field is part of a primary key */
define("MYSQL_UNIQUE_KEY_FLAG", 	  4);		/* Field is part of a unique key */
define("MYSQL_MULTIPLE_KEY_FLAG", 	  8);		/* Field is part of a key */
define("MYSQL_BLOB_FLAG",		 	 16);		/* Field is a blob */
define("MYSQL_UNSIGNED_FLAG",	 	 32);		/* Field is unsigned */
define("MYSQL_ZEROFILL_FLAG",	  	 64);		/* Field is zerofill */
define("MYSQL_BINARY_FLAG",			128);		/* Field is binary   */

/* The following are only sent to new clients */
define("MYSQL_ENUM_FLAG",			    256);		/* field is an enum */
define("MYSQL_AUTO_INCREMENT_FLAG", 	512);		/* field is a autoincrement field */
define("MYSQL_TIMESTAMP_FLAG",		   1024);		/* Field is a timestamp */
define("MYSQL_SET_FLAG",			   2048);		/* field is a set */
define("MYSQL_NUM_FLAG",			  32768);		/* Field is num (for clients) */
define("MYSQL_PART_KEY_FLAG",		  16384);		/* Intern; Part of some key */
define("MYSQL_GROUP_FLAG",			  32768);		/* Intern: Group field */
define("MYSQL_UNIQUE_FLAG",			  65536);		/* Intern: Used by sql_yacc */
define("MYSQL_BINCMP_FLAG",			 131072);		/* Intern: Used by sql_yacc */

define("MYSQL_TYPE_DECIMAL", 		0);
define("MYSQL_TYPE_TINY",			1);						// type of integer	
define("MYSQL_TYPE_SHORT",			2);					// type of integer	
define("MYSQL_TYPE_LONG",			3);						// type of integer	
define("MYSQL_TYPE_FLOAT", 			4);					// type of real	
define("MYSQL_TYPE_DOUBLE",			5);					// type of real	
define("MYSQL_TYPE_NULL", 			5);
define("MYSQL_TYPE_TIMESTAMP",		7);
define("MYSQL_TYPE_LONGLONG",		8);
define("MYSQL_TYPE_INT24",			9);
define("MYSQL_TYPE_DATE",			10);
define("MYSQL_TYPE_TIME",			11);
define("MYSQL_TYPE_DATETIME",		12);
define("MYSQL_TYPE_YEAR",			13);
define("MYSQL_TYPE_NEWDATE",		14);
define("MYSQL_TYPE_ENUM",			247);
define("MYSQL_TYPE_SET",			248);
define("MYSQL_TYPE_TINY_BLOB",		249);
define("MYSQL_TYPE_MEDIUM_BLOB",	250);
define("MYSQL_TYPE_LONG_BLOB",		251);
define("MYSQL_TYPE_BLOB",			252);
define("MYSQL_TYPE_VAR_STRING",		253);			// VARCHAR
define("MYSQL_TYPE_STRING",			254);
define("MYSQL_TYPE_GEOMETRY",		255);
			
			
/***********************************************

For information

#define IS_PRI_KEY(n)	((n) & PRI_KEY_FLAG)
#define IS_NOT_NULL(n)	((n) & NOT_NULL_FLAG)
#define IS_BLOB(n)	((n) & BLOB_FLAG)
#define IS_NUM(t)	((t) <= FIELD_TYPE_INT24 || (t) == FIELD_TYPE_YEAR)
#define IS_NUM_FIELD(f)	 ((f)->flags & NUM_FLAG)
#define INTERNAL_NUM_FIELD(f) (((f)->type <= FIELD_TYPE_INT24 && ((f)->type != FIELD_TYPE_TIMESTAMP || (f)->length == 14 || (f)->length == 8)) || (f)->type == FIELD_TYPE_YEAR)


typedef struct st_mysql_field {
  char *name;                 // Name of column 
  char *org_name;             // Original column name, if an alias 
  char *table;                // Table of column if column was a field 
  char *org_table;            // Org table name, if table was an alias 
  char *db;                   // Database for table 
  char *catalog;	      	  // Catalog for table 
  char *def;                  // Default value (set by mysql_list_fields) 
  unsigned long length;       // Width of column (create length)
  unsigned long max_length;   // Max width for selected set 
  unsigned int name_length;
  unsigned int org_name_length;
  unsigned int table_length;
  unsigned int org_table_length;
  unsigned int db_length;
  unsigned int catalog_length;
  unsigned int def_length;
  unsigned int flags;         // Div flags 
  unsigned int decimals;      // Number of decimals in field 
  unsigned int charsetnr;     // Character set 
  enum enum_field_types type; // Type of field. See mysql_com.h for types 
} MYSQL_FIELD;			
			
****************************************************/			
			
?>
