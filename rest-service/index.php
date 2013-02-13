<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>REST Service</title>

<link rel="stylesheet" type="text/css" href="rest.css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>



<br />

<h2>rest.christchurchlye.org.uk</h2>

<h2>index.php</h2>

<h2>Creation Date: 2011-03-11</h2>

<?php
require_once("class.rest-service.php");
require_once("../genlib.php");
require_once("../dbconnectparms.php");
require_once("../globals.php");
require_once("../mysql.php");

/* Connect to a MySQL server */
$connection = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

$oRest = new RestService();

print("<h2>");

print("<p> Version : " . RestService::REST_VERSION_NUMBER . "</p>");
print("<p>Last updated: " . getLastUpdatedDate() . "</p>");

print("</h2>");

?>

<h2>A RESTful Service for Church Events.</h2>

<p>
This REST service is not intended for use in a web browser. It provides a mechanism
for interrogating the backend website database in order to provide <b>XML</b> and <b>JSON</b> formatted
information for use by client applications.
</p>

<p>
This release provides enquiry access to Church events. Further
development will provide the ability to insert and update events.
</p>


<h2>Useful Links</h2>

<p>

  <a href="https://docs.google.com/viewer?url=http%3A%2F%2Fhome.ccil.org%2F~cowan%2Frestws.pdf">
  RESTful Web Services
  </a>
  <br />
  <a href="http://gdp.globus.org/gt4-tutorial/multiplehtml/ch01s02.html">
  A short introduction to Web Services
  </a>
  <br />


</p>


<h2>Service Specification</h2>
<p>
  <!-- <a href="REST-documentation.pdf">REST-documentation.pdf</a> -->
	<a href="restdocs.php">documentation</a>
</p>


<p>
Return to

<a href="http://christchurchlye.org.uk">Main Website</a>

</p>

</body>
</html>
