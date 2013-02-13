<?php

require_once("../globals.php");
require_once("../mysql.php");
require_once("../rest-service/class.sermonstalks.php");

/* Connect to a MySQL server */ 
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');


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
		$save_dir = "../mp3s/"; // "../mp3s/"
		$save_filename	 = $save_dir . $_FILES["file"]["name"];

		echo "<p>";
		echo "Uploaded filename: [" . $upload_filename . "]<br />";
		echo "Save     filename: [" . $save_filename . "]<br />";
		echo "</p>";

    if (file_exists($save_filename))
    {
			echo "<p>";
	    echo "Error: Save filename [" . $save_filename . "] already exists. ";
			echo "</p>";
    }
    else
    {
		  if (! file_exists($upload_filename))
		  {
				echo "<p>";
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
					echo "<h4>[" . $from_file . "] is NOT an HTTP uploaded file</h4>";
				}

		    if (! move_uploaded_file($from_file, $to_file) )
				{
					echo "<h4>move_uploaded_file failed - returned false</h4>";
				}
				else
				{
					$qry = " UPDATE sermonstalks SET filename = " . 
                  	"'" . $_FILES["file"]["name"] . "' " . 
								 " WHERE id = " . $_POST['id'];

					$result = mysql_query($qry);					
					if (!$result) 
					{
						die('Invalid query: ' . mysql_error());
					}		

					touchMenuItem('Sermons and Talks');
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
  echo "Invalid file";
}

?> 

<p>

<a href="bsermonstalks.php">Return to Browsing Sermons and Talks</a>

</p>


