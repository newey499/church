<html>
<head>

</head>

<body>

<h1>index.php</h1>

<p>

<select name="calmonth" id="calmonth"  onchange="changeCalendarMonth(document.getElementById('calmonth')); return false; "  >
<option value="month0"  >Mar 2010</option>
<option value="month1"  >Apr 2010</option>
<option value="month2"  >May 2010</option>
<option value="month3"  >Jun 2010</option>
<option value="month4"  >Jul 2010</option>

<option value="month5"  >Aug 2010</option>
</select>



</p>



<p>
<a href="ajax.php">ajax.php</a>
</p>




<?php


	//phpinfo();
	
	
	// Has to be run from test.christchurchlye.org.uk
	$target = "http://policeapi.rkh.co.uk/api/hello-world?key={d309380283ecefc0ecc63cb5793efa56}";
	
  // create curl resource
  $ch = curl_init();

  // set url
  curl_setopt($ch, CURLOPT_URL, $target);

  //return the transfer as a string
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  // $output contains the output string
  $output = curl_exec($ch);

	print("<h1>Curl Call to " . $target . "</h1>\n");

	print($output);
	
  // close curl resource to free up system resources
  curl_close($ch);   	
	

?>


</body>
</html>


