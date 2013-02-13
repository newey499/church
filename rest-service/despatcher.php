<?php

include_once('xdebug-setup.php');


require_once 'class.rest.exception.php';
require_once('class.rest-service.php');
require_once('class.events.php');


$request = new RestService();
$events  = new Events($request);


//$request->displayArgs();

//var_dump($request);


$request->writeXmlHeader();

 try
 {
	 if (! isset($request->pathElements[0]))
	 {
		 $events->throwError("Invalid URI - Enquiry type not specified", 100);
	 }


		switch ($request->pathElements[0])
		{
			case "EVENTS":

				if (! isset($request->pathElements[1]))
				{
					$events->throwError("Invalid URI - Event type not specified", 200);
				}

				switch ($request->pathElements[1])
				{
					case "ONEOFF":
						$events->handleOneOff();
						break;

					case "REGULAR":
						$events->handleRegular();
						break;

					case "ALL":
						$events->handleAll();
						break;

					case "SERMONSANDTALKS":	
						$events->handleSermonsAndTalks();
						break;

					default:
						$errMsg = "unsupported POST/EVENTS service requested - operation " .
											" URL [" .$request->url . "]";
						$events->throwError($errMsg, 999);
						break;
				}
				break;



		}


  $request->writeXmlFooter();
 }
 catch (RestException $e)
 {
		$request->writeElementStart("error");
		$request->writeElement("code", $e->restCode);
		$request->writeElement("message", $e->restMessage);
		$request->writeElementEnd("error");
		$request->writeXmlFooter();
 }

$request->printOutputText();



// ======================================================================


// ======================================================================
?>
