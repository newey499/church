<?php
/***************
class.library.php

**************************/

class Library 
{
	public function getUserDetails($user_id) 
	{
    $details = array(
        "user_id" => $user_id,
        "name" => 'Joe Bloggs',
        "email" => 'joe@example.com');
    return $details;
	}
}

?>
