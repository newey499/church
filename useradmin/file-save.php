<html>

<?php
	$pageTitle = "Save MP3 file";
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
  <!-- End CSS Includes -->

</head>

<body style="margin-top:20px; margin-left:20px;">

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">



<?php

require_once("../globals.php");
require_once("../mysql.php");
require_once("../genlib.php");
require_once("../rest-service/class.sermonstalks.php");

require_once('topbanner.php');

/* Connect to a MySQL server */
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


switch ($_POST['opcode'])
{
	// constant defined in ../globals.php
	case ST_UPLOAD_MP3_FILE :
		$save_dir = "../mp3s/"; // directory in which to store uploaded file
		// sql to update column in which the name of the uploaded file is stored
		$sqlUpdateQry = " UPDATE sermonstalks SET filename = " .
                  	"'" . cleanFileName($_FILES["file"]["name"]) . "' " .
									  " WHERE id = " . $_POST['id'];
		// Where to return to
		$returnTarget="bsermonstalks.php";
		$returnPrompt="Return to Browsing Sermons and Talks";
		// menu item to mark as new
		$touchItem = 'Sermons and Talks';
		break;


	default:
		die("<h4>opcode [" . $_POST['opcode'] . "] is unknown when saving an uploaded file</h4>");



}

// =============================================
// Example with file type and size restricted
// Nb. jpeg isneeded for Firefox and pjpeg needed for that typically aberrant piece of shit IE.
// =============================================
//if ((($_FILES["file"]["type"] == "image/gif")
//|| ($_FILES["file"]["type"] == "image/jpeg")
//|| ($_FILES["file"]["type"] == "image/pjpeg"))
//&& ($_FILES["file"]["size"] < 20000))
if (true)
{
	echo "<h4>Record id = [" . $_POST['id'] . "]</h4>";

  if ($_FILES["file"]["error"] > 0)
  {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
  }
  else
  {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

		$upload_filename = $_FILES["file"]["tmp_name"];
		$save_filename	 = $save_dir . cleanFileName($_FILES["file"]["name"]);

		echo "<p>";
		echo "Uploaded filename: [" . $upload_filename . "]<br />";
		echo "Save     filename: [" . $save_filename . "]<br />";
		echo "</p>";

    if (file_exists($save_filename))
    {
		echo "<p class='error' >";
	    echo "Error: Save filename [" . $save_filename . "] already exists. ";
		echo "</p>";
    }
    else
    {
		  if (! file_exists($upload_filename))
		  {
				echo "<p class='error'>";
				echo "Error: Upload filename [" . $upload_filename . "] does not exist. ";
				echo "</p>";
		  }
			else
			{
				$from_file = $upload_filename;
				$to_file = $save_filename;
				echo "<p>";
				echo "From [" . $from_file . "]<br />";
				echo "To [" . $to_file . "]<br />";
				echo "</p>";

				if (is_uploaded_file($from_file))
				{
					echo "<h4>[" . $from_file . "] is an HTTP uploaded file</h4>";
				}
				else
				{
					echo "<h4 class='error'>[" . $from_file . "] is NOT an HTTP uploaded file</h4>";
				}

		    if (! move_uploaded_file($from_file, $to_file) )
				{
					echo "<h4 class='error'>move_uploaded_file failed - returned false</h4>";
				}
				else
				{

					$result = mysql_query($sqlUpdateQry);
					if (!$result)
					{
						die('Invalid query: ' . mysql_error());
					}

					if (! empty($touchItem))
					{
						touchMenuItem($touchItem);
					}
					setLastupdatedDate( date('d/m/Y'), date('H:i'));


				}

				echo "<p>";
		    echo "Stored in: [" . $save_filename . "]";
				echo "</p>";
			}
    }

  }
}
else
{
	print("<p class='error'>");
	echo "Invalid file";
	print("</p>");
}

?>

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
