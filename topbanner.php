<!-- START TOP BANNER -->
<div id="topsection" >
<div class="innertube">

	<!-- =========
	<a href="http://www.cofe.anglican.org/">
	<img src="jpgs/cofe_logo030.gif"
		onmouseover="this.style.cursor='pointer'"
		alt="Link to Church of England Website"
			title="Link to Church of England Website"
	/>
	</a>

	&nbsp;&nbsp;
	============================ -->


	<span class="christchurchtitle">
		Christ Church
	</span>

	<span class="christchurchaddress">
		High Street, Lye, Stourbridge, West Mids, UK. DY9 8LF
	</span>


	<span class="developmentsystem">
		<?php
			if ( file_exists('devsystemsemaphore.php') )
			{
				include_once('devsystemsemaphore.php');
			}
		?>
	</span>

	<?php
		require_once('hbarmenu.php');
	?>

</div>  <!-- End <div class="innertube"> -->
</div>  <!-- End <div id="topsection"> -->
<!-- END TOP BANNER -->
