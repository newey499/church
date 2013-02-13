<?php
ini_set('display_errors', 'On');
require_once('class.rest-service.php');

$oRequest = new RestService();

/*******************
$oRequest->outputHTML("<h2>");
$oRequest->outputHTML("rest.christchurchlye.org.uk");
$oRequest->outputHTML("</h2>");

$oRequest->outputHTML("<h2>");
$oRequest->outputHTML("diagnostic.php");
$oRequest->outputHTML("</h2>");


$oRequest->outputHTML("<h2>");
$oRequest->outputHTML("A RESTful Service for Church Events.");
$oRequest->outputHTML("</h2>");


$oRequest->outputHTML("<p>");
$oRequest->outputHTML('<a href="/index.php" >Specification</a>');
$oRequest->outputHTML("</p>");

$oRequest->outputHTML("<p>");
*********************/

//(Under Development)
//$oRequest->displayArgs();
if ($oRequest->jsonOutput)
{
	var_dump($oRequest);
	print(json_encode($oRequest));
}
else
{
	var_dump($oRequest);
	$oRequest->outputHTML("</p>");
}

?>







