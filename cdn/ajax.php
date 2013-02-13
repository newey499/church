<html>
<head>

<script type="text/javascript">
function loadXMLDoc(url)
{
if (window.XMLHttpRequest)
{ 
	// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
}
else
{ 
	// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  
// anonymous function used to handle asynchronous request  
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {document.getElementById('test').innerHTML="<h2>Asynchronous " + xmlhttp.responseText + "</h2>"}
}  

xmlhttp.open("GET",url,true);  // true = asynchronous  
//xmlhttp.open("GET",url,false);  // false = synchronous (blocks until request completes)
xmlhttp.send(null);
//document.getElementById('test').innerHTML="<h2>Synchronous " + xmlhttp.responseText + "</h2>";  // synchronous
}
</script>
</head>

<body>

<div id="test">
<h2>Click to let AJAX change this text</h2>
</div>
<button type="button" onclick="loadXMLDoc('text1.txt')">Click Me - text1.txt</button>
 
<button type="button" onclick="loadXMLDoc('text2.txt')">Click Me - text2.txt</button>

<p>
The readyState property
<br />
<br />
The readyState property holds the status of the server's response.
<br />
<br />
Possible values for the readyState property:
<br />
State	Description
<br />
0	The request is not initialized
<br />
1	The request has been set up
<br />
2	The request has been sent
<br />
3	The request is in process
<br />
4	The request is complete
</p>

</body>
</html>



