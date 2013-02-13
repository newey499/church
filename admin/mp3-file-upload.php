<?php

require_once "../globals.php";
include_once "../mysql.php";


/* Connect to a MySQL server */ 
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

?>

<html>
<body>

<h4>
Upload MP3 file to webserver
<br />
<?php
echo "id = [" . $_GET['id'] . "]";

?>
</h4>

<p>
<form action="mp3-file-save.php" method="post"
			enctype="multipart/form-data">

	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

	<label for="file">Filename:</label>
	<input type="file" name="file" id="file" />
	<br />
	<input type="submit" name="submit" value="Submit" />

</form>
</p>

<p>
<a href="bsermonstalks.php">Return to Browsing Sermons and Talks</a>
</p>

</body>
</html> 
