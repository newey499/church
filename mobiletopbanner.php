<!-- START TOP BANNER -->

	<a href="http://www.cofe.anglican.org/">
	<img src="jpgs/cofe_logo030.gif" 
	     onmouseover="this.style.cursor='pointer'" 
   		 alt="Link to Church of England Website" 
			 title="Link to Church of England Website" 
	/> 
	</a>
	
	<br />

	<span class="christchurchtitle">
		Christ Church 
	</span>

	<br />

	<span class="christchurchaddress">
		High Street, Lye,
		<br />
		Stourbridge, West Mids, UK.
		<br />
		DY9 8LF
	</span>

	<br />
	
	<!-- <a href="mobile.php">Mobile Site</a>	-->

	<span class="developmentsystem">
		<?php
			if ( file_exists('devsystemsemaphore.php') )
			{
				include_once('devsystemsemaphore.php');
			}
		?>
	</span>		

<!-- END TOP BANNER -->
