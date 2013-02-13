<?php

require_once "globals.php";
include_once "mysql.php";


/* Connect to a MySQL server */ 
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

?>


<br />

<h2>MP3 Sermons and Talks</h2>



<p>
To play the MP3's in your browser click on the link.
</p>

<p>
To download the MP3's right click on the link and use the 
"Save link as" option.
</p>


<!-- ========================================================= -->
<!-- Begin Display of MP3 Sermons/Talks                        -->
<!-- ========================================================= -->
<table class="sermonsAndTalksGroup">

<?php

function displayNew($daysOld)
{
	// debug echo $daysOld . '<br /> ';
	if ($daysOld <= 7)
	{
		echo '		<span class="new">New</span>';
	}

	return true;
}


$qry = "SELECT	id, 
								filename,
								dateperformed, 
								DATE_FORMAT(dateperformed, '%b %D %Y') AS datedisplay,
								DATEDIFF(CURRENT_DATE(), lastupdated) AS days_old,
								series, 
								biblebook, 
								bibleref,
								title, 
								preacher, 
								description,
								groupno,
								itemno
        FROM sermonstalks
        ORDER BY groupno, itemno DESC";  

if (! ($cursor = mysql_query($qry)) ) 
{
	die("<p>" . mysql_error() . "</p>\n");
}


$groupno = "";
while ($row = mysql_fetch_assoc($cursor)) 
{
	if (! ($groupno == $row['groupno']))
	{
		echo '<tr class="sermonsAndTalksGroup">';
		echo '	<th class="sermonsAndTalksGroup">';
		$groupno = $row['groupno'];
		echo "<h4>";
		echo $row['series'];
		echo "</h4>";
		echo '	</th>';
		echo '</tr>';
	}

	echo '<tr class="sermonsAndTalksGroup">';

	echo '	<td class="sermonsAndTalksGroup">';
	echo '		<b>';
	echo '		' . $row['bibleref'];
	echo '		</b>';
	echo '	</td>';

	echo '	<td class="sermonsAndTalksGroup">';
	echo '		<a href="mp3s/' . $row['filename'] . '">';
	echo '		' . $row['title'];
	echo '		</a>';
	displayNew($row['days_old']);
	echo '	</td>';

  echo '</tr>';


	echo '<tr class="sermonsAndTalksGroup">';

	echo '	<td class="sermonsAndTalksGroupEndItem">';
	echo '		' . $row['datedisplay'];
	echo '	</td>';

	echo '	<td class="sermonsAndTalksGroupEndItem">';
	echo '		' . $row['preacher'];
	echo '	</td>';

  echo '</tr>';


}

// Free the resources associated with the result set
// This is done automatically at the end of the script
mysql_free_result($cursor);


?>

</table>

<!-- ========================================================= -->
<!-- End Display of MP3 Sermons/Talks                          -->
<!-- ========================================================= -->
