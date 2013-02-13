<?php

session_start();
//require_once('/www/Smarty/libs/Smarty.class.php');
//require_once('/www/SmartyValidate/libs/SmartyValidate.class.php');

require('smartysetup.php');


$smarty =& new Smarty;

// load config variables and assign them
$smarty->config_load('global.cnf');

print("config [" . $smarty->get_config_vars('ROW_ADD') . "]<br />");


if(empty($_POST)) 
{
	SmartyValidate::connect($smarty, true);
	SmartyValidate::register_validator('fname','FullName','notEmpty');

	SmartyValidate::register_validator('fdate','fdate','isDate',false,false,'makeDate');
	SmartyValidate::register_validator('fdatevalid','fdate','isDate');

	$smarty->display('validate.tpl');

}
else 
{    
	SmartyValidate::connect($smarty);

	$_POST['fdate'] = makeMySqlDate($_POST['fdateYear'], $_POST['fdateMonth'], $_POST['fdateDay']);

	// validate after a POST
	if(SmartyValidate::is_valid($_POST)) 
	{
		 // no errors, done with SmartyValidate
		 SmartyValidate::disconnect();
		 $smarty->display('success.tpl');
	}
	else 
	{
		// error, redraw the form
		$smarty->assign($_POST);
		$smarty->display('validate.tpl');
	}
}


?>


<?php
// this assumes your form elements are named
// startDate_Day, startDate_Month, startDate_Year

//$startDate = makeTimeStamp($fdateYear, $fdateMonth, $fdateDay);

function makeTimeStamp($year='', $month='', $day='')
{
   if(empty($year)) {
       $year = strftime('%Y');
   }
   if(empty($month)) {
       $month = strftime('%m');
   }
   if(empty($day)) {
       $day = strftime('%d');
   }

   return mktime(0, 0, 0, $month, $day, $year);
}

function makeMySqlDate($year='', $month='', $day='')
{
	return $year . '-' . $month . '-' . $day;
}


?>
