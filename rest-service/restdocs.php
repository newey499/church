<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>REST Service</title>

<link rel="stylesheet" type="text/css" href="rest.css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

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

?>

<br />

<h2>rest.christchurchlye.org.uk</h2>


<h2>restdocs.php</h2>

<h2>Creation Date: 2011-03-11</h2>

<h2>Last updated:

<?php
	print(getLastupdatedDate());
?>



<?php
require_once("class.rest-service.php");
$oRest = new RestService();

print("<p> Version : " . RestService::REST_VERSION_NUMBER . "</p>");
?>

</h2>

<h2>A RESTful Service for Church Events.</h2>

<p>
This REST service is not intended for use in a web browser. It provides a mechanism
for interrogating the backend website database in order to provide <b>XML</b> formatted
information for use by client applications.
</p>

<p>
The response may be requested in <b>JSON</b> format by appending "/json" to any of the URI's
specified below.
</p>

<p>
<b>XML</b> and <b>JSON</b> formatted responses contain the same information in the same structure.
The exception being that when the response contains multiple events the <b>XML</b> response contains
repeated elements, whilst the <b>JSON</b> reply wraps multiple events as an array.
</p>

<p>
One off events are automatically deleted from the database once they have taken place so attempting to
examine historical One off events will never return any items.
</p>

<p>
Rather than document the responses here just cut and paste the URI's below into a browser.
Obviously, don't use the hard coded dates in the URI's. Instead start by using the current year and month.
</p>

<br />

<p>
<h2>Change Log</h2>
</p>

<p>

	<table>

		<!-- ======================================================== -->
		<tr>

			<th>
			Version
			</th>

			<th>
			Date
			</th>

			<th>
			Programmer
			</th>

			<th>
			Description
			</th>

		</tr>

		<!-- ======================================================== -->

		<tr>

			<td>
			0.2
			</td>

			<td>
			2011/06/03
			</td>

			<td>
			CDN
			</td>

			<td>
			Initial Release
			</td>

		</tr>

		<!-- ======================================================== -->


		<tr>

			<td>
			0.3
			</td>

			<td>
			2012/03/21
			</td>

			<td>
			CDN
			</td>

			<td>
			Added Support for MP3 Sermons and Talks information
			</td>

		</tr>

		<!-- ======================================================== -->



		<tr>

			<td>
			0.4
			</td>

			<td>
			2012/04/19
			</td>

			<td>
			CDN
			</td>

			<td>
			Added ability to download all events (Forthcoming and Regular) in date/time order (Most recent first)
			</td>

		</tr>

		<!-- ======================================================== -->


	</table>

</p>

<br />

<p>
<h2>Available URI's</h2>
</p>

<p>
	<table>

		<tr>

			<th>
			MP3 Sermons and Talks
			</th>

			<th>
			Information and URI of MP3's of Church Sermons/Talks. Returned in date order - most recent first
			</th>

		</tr>

		<tr>
			<th>URI</th>
			<th>Response Description</th>
		</tr>

		<tr>
			<td>
				http://rest.christchurchlye.org.uk/events/sermonsandtalks
			</td>
			<td>
				All MP3 Files and associated information held on database
			</td>
		</tr>

	</table>
</p>


<p>
<table>

	<tr>
		<th>
			One off events
		</th>
		<th>
			Events that occur once at some time in the future. One off events are automatically
			deleted by a cron job once they have taken place. Returned in date order - First occuring event first.
		</th>
	</tr>

	<tr>
		<th>URI</th>
		<th>Response Description</th>
	</tr>


	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff
		</td>
		<td>
			All One off events held on database
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/237
		</td>
		<td>
			One off event whose primary key is 237
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/id/237
		</td>
		<td>
			One off event whose primary key is 237. A synonym URI for the above
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/date
		</td>
		<td>
			Error - Year and Month not provided
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/date/2011
		</td>
		<td>
			Error - Month not provided
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/date/2011/12
		</td>
		<td>
			One off events scheduled for December 2011
		</td>
	</tr>
	<tr>
		<td>
			http://rest.christchurchlye.org.uk/events/oneoff/date/2011/12/11
		</td>
		<td>
			One off events scheduled for the 11th of December 2011
		</td>
	</tr>

	</table>

</p>
<br />

<table>

<tr>
	<th>
		Regular events
	</th>
	<th>
		Events that take place on a regular basis - daily, weekly, monthly or annually
	</th>
</tr>

<tr>
	<th>URI</th>
	<th>Response Description</th>
</tr>

<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular
	</td>
	<td>
		All Regular events held on database
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/1
	</td>
	<td>
		Regular event whose primary key is 1
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/id/1
	</td>
	<td>
		Regular event whose primary key is 1. A synonym URI for the above
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/date
	</td>
	<td>
		Error - Year and Month not provided
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/date/2011
	</td>
	<td>
		Error - Month not provided
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/date/2011/3
	</td>
	<td>
		All Regular events due to take place in March 2011 sorted in date time order
	</td>
</tr>
<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/regular/date/2011/3/22
	</td>
	<td>
		All Regular events due to take place on the 22nd of March 2011 sorted in date time order
	</td>
</tr>

</table>

<br />

<table>

<tr>
	<th>
		Regular and One off events combined
	</th>
	<th>
		Events are returned sorted in date time order.
	</th>
</tr>

<tr>
	<th>URI</th>
	<th>Response Description</th>
</tr>

<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/all
	</td>
	<td>
		Error - Minimum Year and Date must be specified
	</td>
</tr>


<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/all/2011
	</td>
	<td>
		Error - Minimum Year and Date must be specified
	</td>
</tr>

<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/all/2011/3
	</td>
	<td>
		All events for year 2011 and month March sorted in date time order
	</td>
</tr>

<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/all/2011/3/22
	</td>
	<td>
		All events for 22nd March 2011 sorted in date time order
	</td>
</tr>


<tr>
	<td>
		http://rest.christchurchlye.org.uk/events/all/days/7
	</td>
	<td>
		All events for 7 days starting from today. The number of days can be any positive integer.
	</td>
</tr>


</table>

<br />

<!------------------------------------------
		// all oneoff events
		http://rest.christchurchlye.org.uk/events/oneoff

		// id
		http://rest.christchurchlye.org.uk/events/oneoff/237
		// id
		http://rest.christchurchlye.org.uk/events/oneoff/id/237
		// date
		http://rest.christchurchlye.org.uk/events/oneoff/date
		http://rest.christchurchlye.org.uk/events/oneoff/date/2011
		http://rest.christchurchlye.org.uk/events/oneoff/date/2011/12
		http://rest.christchurchlye.org.uk/events/oneoff/date/2011/12/11

		// regular and oneoff events combined
		http://rest.christchurchlye.org.uk/events/all/2011/3/10
		http://rest.christchurchlye.org.uk/events/all/2011/3/22
		http://rest.christchurchlye.org.uk/events/all/2011/3
		http://rest.christchurchlye.org.uk/events/all/2011

		// regular events
		http://rest.christchurchlye.org.uk/events/regular
		http://rest.christchurchlye.org.uk/events/regular/1
		http://rest.christchurchlye.org.uk/events/regular/id/1
		http://rest.christchurchlye.org.uk/events/regular/date
		http://rest.christchurchlye.org.uk/events/regular/date/2011
		http://rest.christchurchlye.org.uk/events/regular/date/2011/3
		http://rest.christchurchlye.org.uk/events/regular/date/2011/3/22

----------------------------------------------------------------->


<p>

<a href="index.php">REST home page</a>

<br />

<?php
require_once("../dbconnectparms.php");
//print("<a href='http://" . MYSQL_SERVER . "'>Main Website</a>");
?>
<a href="http://christchurchlye.org.uk">Main Website</a>

</p>

</body>
</html>

