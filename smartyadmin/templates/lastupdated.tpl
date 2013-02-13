{include file="header.tpl" title="Last Updated"}

<br>

<p>
	<h2>Website Last updated</h2>
</p>

<form name="setLastUpdated" action="lastupdated.php" method="post">

		{* start and end year can be relative to current year *}
		{html_select_date prefix='luDate' 
											time=$luDate 
											start_year='-1' 
											end_year='+1' 
											display_days=true 
											field_order="DMY" }


		{html_select_time use_24_hours=true
											display_seconds=false
											time=$luTime }

  <br />
  <br />

  <input type="submit" value="Update"> 

	&nbsp;


</form>

	<form name="touchLastUpdated" action="lastupdated.php" method="post">

			<input type=hidden name="touchLastUpdated" value="touchLastUpdated">
	    <input type="submit" value="Set Last Updated to now"> 

	</form>



<p> 
Return to <a href="index.php">Admin Menu</a> 
</p>


<br />


{include file="footer.tpl"}


