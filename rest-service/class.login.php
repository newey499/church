<?php

require_once("../globals.php");
require_once("../genlib.php");
require_once("../mysql.php");


/**
 * Description of login
 *
 * Used to authorize REST operations that modify the database
 *
 * @author cdn
 */
class Login
{
	function __construct()
	{
		$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
		   or die('Could not connect: ' . mysql_error());

		mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');
	}

	function __destruct() 
	{

	}
	 
	public function isValidUser($userId, $passwd)
	{
		$result = false; // assume the worst

		$rset = mysql_query( 
						sprintf("SELECT id, restenabled, password, MD5(password) AS md5password FROM members WHERE nickname = '%s'",
						  			 mysql_real_escape_string($userId)));

		/* fetch values */
		if ($rset)
		{
			$row = mysql_fetch_assoc($rset);
			if ($row)
			{
				if ($row['password'] == $passwd) // check password sent as plain text
				//if ($row['md5password'] == $passwd) // check password sent as MD5 hash
				{
					$result = true;
				}
			}

			mysql_free_result($rset);
		}
		
		return $result;
	}

	public function isRestEnabled($userId, $passwd)
	{
		$result = false; // assume the worst

		$rset = mysql_query(
						sprintf("SELECT id, restenabled, password, MD5(password) AS md5password FROM members WHERE nickname = '%s'",
						  			 mysql_real_escape_string($userId)));

		/* fetch values */
		if ($rset)
		{
			$row = mysql_fetch_assoc($rset);
			if ($row)
			{
				if ($row['password'] == $passwd) // check password sent as plain text
				//if ($row['md5password'] == $passwd) // check password sent as MD5 hash
				{
					if ($row['restenabled'] == "YES")
					{
						$result = true;
					}
				}
			}

			mysql_free_result($rset);
		}

		return $result;
	}

}
?>
