<?php

/******************************************

Defines various constants

*******************************************/
define("FORM_INSERT",			1);
define("FORM_EDIT",				2);
define("FORM_DELETE",			3);
define("FORM_DISPLAY",		4);
define("FORM_VALIDATE",		5);


require_once("dbconnectparms.php");

define("PATH_TO_PHPMAILER", "/usr/local/apache2/htdocs/phpmailer");
set_include_path(get_include_path() . 
                 PATH_SEPARATOR . PATH_TO_PHPMAILER);


define("LOGIN_MEMBER",								1);							// Login an existing member
define("LOGIN_CREATE",								2);							// Create a new member
define("LOGIN_VALIDATE",							3);							// Validate a Login attempt
define("LOGOUT_MEMBER",								4);							// Logout a member who is already logged in
define("LOGIN_MEMBER_EDIT",				    5);							// Edit a logged in members details
define("LOGIN_MEMBER_UPDATE",					6);							// Update a logged in members details
define("LOGIN_ACCEPT_REGISTRATION", 	7);							// Email Argument when confirming user registration
define("LOGIN_DECLINE_REGISTRATION", 	8);							// Email Argument when declining user registration

define("NEWSLETTER_SUBSCRIBE",				1);
define("NEWSLETTER_UNSUBSCRIBE",			2);
define("NEWSLETTER_DOWNLOAD",				  3);
define("NEWSLETTER_VIEW",						  4);
define("NEWSLETTER_BROWSE",						5);
define("NEWSLETTER_PUBLISH",					"P");

define("INTERNAL_CONTENT_FLAG", 'internal.html');		// used to indicate content comes from Menus table

define("MYSQL_DUPLICATE_KEY",				1062);


define("RECENT_PAGE_UPDATE_DAYS", 7);  // Number of days for which a page is considered
                                       // to be recently updated

// Opcodes for marking menu items as recently updated or not recently updated
define("MENU_MARK_NEW", "mmn");
define("MENU_MARK_NOT_NEW", "mmnn");

// Opcode for changing the status of a forthcoming event to visible and
// sending a confirmatory email
define("FC_EVENT_MARK_VISIBLE", "fcemv");


// "Magic" time used by Forthcoming Events to
// suppress display of time
define("FC_HIDE_TIME", "11:11");



?>
