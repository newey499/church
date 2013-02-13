<?php

/******************************
	Start a session. This must be the very first thing done on the page.
**********************************/
require_once("../session.php");
$oSession = new session();
require('smartysetup.php');

$smarty = new Smarty();


// display it
$smarty->display('admin.tpl');


?> 
