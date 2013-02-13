<?php

require_once("../globals.php");
require_once("../mysql.php");
require_once('../genlib.php');


switch ($_GET['opcode'])
{

	// Set these for each new type of file upload (constant in ../globals.php)
	case ST_UPLOAD_MP3_FILE:
		$pageTitle = "Upload MP3 file to webserver";
		$returnTarget = "bsermonstalks.php";
		$returnPrompt = "Return to Browsing Sermons and Talks";

		break;


	default:
		die("<h4>opcode [" . $_GET['opcode'] . "] is unknown when uploading a file</h4>");



}

/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

?>

<html>

<?php
	print("<title>$pageTitle</title>\n");

	include_once("../nocache.php");						// Stop clients from cacheing - hopefully
?>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Chris Newey">
	<meta name="generator" content="AceHTML 5 Freeware">

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <link rel="stylesheet" type="text/css" href="../css/cssdropdownmenu.css" />
  <!-- End CSS Includes -->


</head>

<body style="margin-top:20px; margin-left:20px;">

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">


<?php

require_once('topbanner.php');

print('<br />');

print('<h4>' . $pageTitle . '</h4>');
print("<br />");
print("<br />");

//echo "id = [" . $_GET['id'] . "]";

?>
</h4>

<h4 style="color:red; font-weight:bold;">
Uploading the MP3 may take tome time. Do NOT leave this page until the MP3 upload is complete.
</h4>



<p>
<form action="file-save.php" method="post"
			enctype="multipart/form-data">

	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
	<input type="hidden" name="opcode" value="<?php echo $_GET['opcode']; ?>">

	<label for="file">Filename:</label>
	<input type="file" name="file" id="file" />
	<br />
	<br />
	<input type="submit" name="submit" value="Start upload of Selected MP3 File" />

</form>
</p>

<p>
<?php
	print('<a href="' . $returnTarget . '">' . $returnPrompt . '</a>');
?>
</p>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>
