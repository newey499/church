<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>testpostevent.php</title>

<link rel="stylesheet" type="text/css" href="rest.css" />

</head>
<body>

<p>
<b>testpostevent.php</b>
</p>

<?php

?>

<p>
Test harness for creating a forthcoming event
</p>

<p>
Submit form to "http://<?php print($_SERVER['SERVER_NAME']); ?>/events/oneoff/add"
</p>


<!-- <form name="forthcoming" action="despatcher.php" method="post"> -->
<form name="forthcoming" 
			action="http://<?php print($_SERVER['SERVER_NAME']); ?>/events/oneoff/add"
			method="post">


	<table>

	<tr>

	<th>
	Field Name
	</th>

	<th>
	Field Value
	</th>

	</tr>

	<tr>
	<td>
  id
	</td>
	<td>
	<input type="text" name='id' readonly		value ="99"  />
	</td>
	<td>
	<p>
  IGNORED and NOT USED integer (primary key) the id is the events primary key. The database
	allocates this when creating a new record. This POST request creates a new record.
	</p>
	<p>
	If the new record is created successfully then the id allocated to the new record
	is returned in an XML document.
	</p>
	</td>
	</tr>

	<tr>
	<td>
  eventdate
	</td>
	<td>
	<input type="text" name='eventdate'			value ="11/12/2011"						/>
	</td>
	<td>
  required: string date formatted as DD/MM/YYYY
	</td>
	</tr>

	<tr>
	<td>
	eventtime
	</td>
	<td>
	<input type="text" name='eventtime'			value ="12:12"								/>
	</td>
	<td>
  required: string time formatted as HH:MM
	</td>
	</tr>

	<tr>
	<td>
	eventname
	</td>
	<td>
	<input type="text" name='eventname'			value ="eventName"						/>
	</td>
	<td>
  required: string
	</td>
	</tr>

	<tr>
	<td>
	eventdesc
	</td>
	<td>
	<input type="text" name='eventdesc'			value ="eventDesc"						/>
	</td>
	<td>
  required: string
	</td>
	</tr>

	<tr>
	<td>
	orgid
	</td>
	<td>
	<input type="text" name='orgid' readonly	value ="orgId"								/>
	</td>
	<td>
  IGNORED and NOT USED int organisation id key
	</td>
	</tr>

	<tr>
	<td>
	contribemail
	</td>
	<td>
	<input type="text" name='contribemail'		value ="contribEmail"					/>
	</td>
	<td>
  required: string valid email address
	</td>
	</tr>

	<tr>
	<td>
	contactname
	</td>
	<td>
	<input type="text" name='contactname'		value ="contactName"					/>
	</td>
	<td>
  required: string
	</td>
	</tr>

	<tr>
	<td>
	contactphone
	</td>
	<td>
	<input type="text" name='contactphone'		value ="contactPhone"					/>
	</td>
	<td>
  required: string valid telephone number
	</td>
	</tr>

	<tr>
	<td>
	contactemail
	</td>
	<td>
	<input type="text" name='contactemail'		value ="contactEmail"					/>
	</td>
	<td>
  required: string valid email address
	</td>
	</tr>

	<tr>
	<td>
	isvisible
	</td>
	<td>
	<input type="text" name='isvisible'			value ="YES"						/>
	</td>
	<td>
  required: string valid values "YES" "NO"
	</td>
	</tr>

	<tr>
	<td>
	linkurl
	</td>
	<td>
	<input type="text" name='linkurl'				value ="http://google.co.uk"  />
	</td>
	<td>
  required: string URL related to event
	</td>
	</tr>

	</table>
<input type="submit" value="Submit" />
</form>


<p>
Example of XML Returned when an unknown operation is requested
</p>

<div class="codeblock">
<pre>
<code>
&lt;churchrestservice&gt;
	&lt;restversion&gt;
		0.1
	&lt;/restversion&gt;
	&lt;error&gt;
		&lt;code&gt;
			787
		&lt;/code&gt;
		&lt;message&gt;
			Error message of some description
		&lt;/message&gt;
	&lt;/error&gt;
&lt;/churchrestservice&gt;
</code>
</pre>
</div>

<p>
</p>

</body>
</html>