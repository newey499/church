<?php
/****************************************************************************

 rssbld.php

 04/12/2008  CDN		Created
 21/08/2009  CDN   Switched to use smarty
******************************************************************************/
require_once("../buildnewrssfeed.php");
/******************************
	Start a session. This must be the very first thing done on the page.
**********************************/
require_once("../session.php");
$oSession = new session();

require_once('smartysetup.php');

$msg = "";

class rssBld
{
	function _construct()
	{
	}
	function _destruct()
	{
	}

	public function exec()
	{
		buildNewRssFeed($msg, FALSE);
	}


}

$obj = new rssBld();

$pageTitle = "RSS Feed Builder";

$smarty = new Smarty();
$smarty->assign('title', $pageTitle);
$smarty->assign('msg', $msg);
$smarty->assign_by_ref('obj', $obj);
$smarty->display('rssbld.tpl');

?>




