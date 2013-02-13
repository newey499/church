<?php

require_once "globals.php";
include_once "mysql.php";


/* Connect to a MySQL server */ 
$dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
   or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

?>


<br />

<h2>Sermons and Talks</h2>


<p>
<b>
MP3 Talks and Sermons
</b>
</p>

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


<!-- =========================================== -->
<br />


<p>
  <b>
  Text Talks and Sermons
  </b>
</p>

<ul>

    <li>
      <p>
      Ezekiel
      </p>

      <ul>

        <li>
           <a href="talkezekiel001.php">A Birthday to Remember</a>
        </li>

        <li>
           <a href="talkezekiel002.php">
           Gods Judgment - A Message that Needs to be Heard</a>
        </li>

        <li>
           <a href="talkezekiel003.php">﻿Doom is no Joke</a>
        </li>

        <li>
           <a href="talkezekiel004.php">Taking God for Granted</a>
        </li>

      </ul>
    </li>


    <li>

     <p>
     Jesus
     </p>

     <ul>

      <li>
      <a href="talkJesusWhenDidHeLive.php">Jesus - When did he live</a>
      </li>

      <li> 
      <a href="talkavicar.php">
       What Would Jesus say to a Vicar</a>
      </li>

      <li> 
      <a href="talkstrugglingparent.php">
         What Would Jesus say to a Struggling Parent</a>
      </li>

      <li> 
      <a href="talkjesusJediKnight.php">
            What Would Jesus Say To A JEDI Knight</a>
      </li>


      <li> 
      <a href="talkwhatwouldjesussaytoaprostitute.php">
            What Would Jesus Say To A Prostitute</a>
      </li>

     </ul>

    </li>

    <li>

     <p>
     Romans
     </p>

     <ul>


      <li> 
      <a href="talkRomansLifeInTheSpirit.php">Life in the Spirit</a>
      </li>


      <li> 
      <a href="talkRomans16.6.php">Do We need the Gospel ?</a>
      </li>

      <li>
      <a href="talkRomans18-32.php">Things could not get worse</a>
      </li>

      <li>
      <a href="talkRomans3.php">Why We Are All In The Same Boat</a>
      </li>

      <li>
      <a href="talkRomans4.php">Justified By Faith</a>
      </li>

      <li>
      <a href="talkfreedforobedience.php">Freed for Obedience</a>
      </li>

     </ul>

    </li>



    <li>

      <p>
      Da Vinci Code
      </p>

      <ul>

       <li>
        <a href="talkDaVinci001.php">Invented in a Pub ?</a>
       </li>

       <li>
        <a href="talkDaVinci002.php">The Real Jesus</a>
       </li>

       <li>
        <a href="talkDaVinci003.php">The Shocking Truth about the Church</a>
       </li>

      </ul>
    </li>       


    <li>

      <p>
      Miscellaneous
      </p>

      <ul>

        <li> 
        <a href="talkdealingwithdepression.php">
              Dealing with Depression</a>
        </li>

        <li> 
        <a href="talkglobalWarming001.php">
                Should we be bothered about Global Warming  ?</a>
        </li>

        <li>
        <a href="talkisreligiondangerous.php">Is Religion Dangerous</a>
        </li>

        <li>
        <a href="talkPaulWhenDidHeLive.php">Paul - When did he live</a>
        </li>

        <li>
        <a href="talkDoesReligionPromoteWar.php">Does religion promote War</a>
        </li>

        <li>
        <a href="talkthefall.php">The Fall</a>
        </li>

      </ul>
    </li>

</ul>






