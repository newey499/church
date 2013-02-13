<?php
/*******************************************
 session.php

 An object to control sessions.

 Note: when using sessions the session MUST repeat MUST
 be the instantiated before ANYTHING else is written to
 the browser client.

 Date				Programmer						Description
 15/01/05		CDN					Created
 24/02/05		CDN					retro fit back to PHP 4.3
 04/02/09   CDN         rewrite for PHP 5.2.6
*************************************************/
require_once("dbconnectparms.php");	// Database connection - user id, password etc.
require_once("mysql.php");

class session
{

	public $dbHandle	= NULL;

	function __construct()
  {
		/*********************
		CDN 18/2/10

		void session_set_cookie_params  ( int $lifetime  [, string $path  [, string $domain
		                                 [, bool $secure = false  [, bool $httponly = false  ]]]] )
		set httponly to TRUE then PHP will attempt to send the httponly  flag when setting the session cookie.

		Set cookie parameters defined in the php.ini file.
		The effect of this function only lasts for the duration of the script.
		Thus, you need to call session_set_cookie_params() for every request and before session_start() is called.

	  session.cookie_httponly  boolean
    Marks the cookie as accessible only through the HTTP protocol.
		This means that the cookie won't be accessible by scripting languages, such as JavaScript.
		This setting can effectively help to reduce identity theft through XSS attacks (although it is not supported by all browsers).
		******************************/
		$lifetime = 60 * 60 * 12;  // 12 hour lifetime
		session_set_cookie_params( $lifetime , NULL , NULL , NULL , true);
		@session_start();

		$this->dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

		$this->loadSystemVars();
	}

	function __destruct()
	{
		// mysql_close() isn't required here - connection gets closed when script exits
	}

	protected function loadSystemVars()
	{
		$rset = mysql_query("SELECT * FROM sysconf");

	  /* fetch columns and store values in session*/
		$data = mysql_fetch_assoc($rset);
		foreach($data as $key => $value)
		{
			$_SESSION[$key] = $value;
		}
		mysql_free_result($rset);

	}

	function is_registered($varName)
	{
		return session_is_registered($varName);
	}

	// deprecated - Access $_SESSION directly
	function set_var($key,$value)
	{
		$_SESSION[$key] = $value;
		return $value;
	}

	// deprecated - Access $_SESSION directly
	function get_var($key)
	{
		return $_SESSION[$key];
	}

	// deprecated - Access $_SESSION directly
	function unset_var($key)
	{
		unset($_SESSION[$key]);
		return NULL;
	}

	// deprecated - Access $_SESSION directly
	function getClear_var($key)
	{
		$tmp = $this->get_var($key);
		$this->unset_var($key);
		return $tmp;
	}

	function killSession()
	{
		session_destroy();
	}

}	// end of session class definition


?>
