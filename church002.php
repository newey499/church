<?php
require_once('genlib.php');
/***********
 Creates link to original sized jpg from smaller version of image
********************************************************************/
function writeThumbTag($href, $src, $size)
{
	print("<td  class=\"thumbnail\">\n");
	print("<a href=\"jpgdisp.php?jpgurl=" . $href . "\" >\n");
	print("<img class=\"thumbnail\" src=\"" . $src . "\" \n");
	print("     alt=\"Click to view in new window\" \n");
	print("     title=\"Click to view in new window\" \n");
	print("	/>\n");
	print("<br /> \n");
	print($size);
	print("</a> \n");
	print("</td>\n");
}
?>

<br />

<h2>Image Gallery </h2>


<p>

Clicking on the thumbails below will display a larger version of the picture.
To download the full size picture right click on the larger image in the
new window and select the "Save Image As" option in the menu that pops up in most browsers.

</p>

<!--
DISABLE SLIDE SHOW
<div style="margin-left:1em; margin-top:1em;">

<div id="slideshow" style="padding-left:1em; padding-bottom:1em;">

    <div class="active">
        <img src="jpgs/churchFront.jpg" alt="Slideshow Image 4" />
    </div>

    <div>
        <img src="jpgs/church003.jpg" alt="Slideshow Image 4" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin001.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin002.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin003.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin004.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin005.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin006.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin007.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/churchwin/churchwin008.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem001.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem002.jpg" alt="Slideshow Image 2" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem003.jpg" alt="Slideshow Image 3" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem004.jpg" alt="Slideshow Image 1" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem005.jpg" alt="Slideshow Image 2" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem006.jpg" alt="Slideshow Image 3" />
    </div>

    <div>
        <img src="jpgs/slide/warmem/warmem007.jpg" alt="Slideshow Image 1" />
    </div>

</div>

</div>

-->


<p>
  <h2>External Views</h2>
</p>

<table class="thumbnail">

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/churchfrontwarmem.jpg", "jpgs/churchfrontwarmem.jpg", "24Kb"); ?>
		<?php writeThumbTag("jpgs/churchFront.jpg", "jpgs/churchFront.jpg", "5Kb"); ?>
		<?php writeThumbTag("jpgs/church003.jpg", "jpgs/church003.jpg", "5Kb"); ?>
		<?php // writeThumbTag("jpgs/church002.jpg", "jpgs/thumbs/church002.jpg", "19Kb"); ?>
		<?php // writeThumbTag("jpgs/church001.jpg", "jpgs/thumbs/church001.jpg", "5Kb");  ?>
	</tr>

</table>


<p>
  <h2>Stained Glass Windows</h2>
</p>

<table class="thumbnail">

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin001.jpg", "jpgs/slide//churchwin/churchwin001.jpg", "3.4Mb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin002.jpg", "jpgs/slide//churchwin/churchwin002.jpg", "3.1Mb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin003.jpg", "jpgs/slide//churchwin/churchwin003.jpg", "1.4Mb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin004.jpg", "jpgs/slide//churchwin/churchwin004.jpg", "800Kb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin005.jpg", "jpgs/slide//churchwin/churchwin005.jpg", "160Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin006.jpg", "jpgs/slide//churchwin/churchwin006.jpg", "259Kb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin007.jpg", "jpgs/slide//churchwin/churchwin007.jpg", "193Kb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin008.jpg", "jpgs/slide//churchwin/churchwin008.jpg", "1.29Mb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin009.jpg", "jpgs/slide//churchwin/churchwin009.jpg", "111Kb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin010.jpg", "jpgs/slide//churchwin/churchwin010.jpg", "108.Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin011.jpg", "jpgs/slide//churchwin/churchwin011.jpg", "116Kb"); ?>
		<?php writeThumbTag("jpgs/slide/churchwin/orig/churchwin012.jpg", "jpgs/slide//churchwin/churchwin012.jpg", "58.Kb"); ?>
	</tr>


</table>


<p>
  <h2>War Memorial</h2>
</p>

<table class="thumbnail">

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem001.jpg", "jpgs/slide/warmem/warmem001.jpg", "2.2Mb"); ?>
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem002.jpg", "jpgs/slide/warmem/warmem002.jpg", "2.6Mb"); ?>
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem003.jpg", "jpgs/slide/warmem/warmem003.jpg", "4.3Mb"); ?>
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem004.jpg", "jpgs/slide/warmem/warmem004.jpg", "4.4Mb"); ?>
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem005.jpg", "jpgs/slide/warmem/warmem005.jpg", "4.3Mb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem006.jpg", "jpgs/slide/warmem/warmem006.jpg", "4.7Mb"); ?>
		<?php writeThumbTag("jpgs/slide/warmem/orig/warmem007.jpg", "jpgs/slide/warmem/warmem007.jpg", "5Mb"); ?>
		<?php writeThumbTag("jpgs/afghanistan-soldiers-poem.jpg", "jpgs/afghanistan-soldiers-poem.jpg", "120Kb"); ?>
	</tr>

</table>


<p>
<A NAME="church_bells"> </A>
  <h2>Church Bells</h2>
</p>


<p>
<A NAME="church_bells_cleaned_up"> </A>
  <h4>Church Bells - Cleaned up 28/3/2012</h4>
</p>

<table class="thumbnail">

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells-clean-2012-03-28-001.jpg", "jpgs/bells/bells-clean-2012-03-28-001.jpg", "436Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells-clean-2012-03-28-002.jpg", "jpgs/bells/bells-clean-2012-03-28-002.jpg", "440Kb"); ?>
	</tr>

</table>

<br />

<p>
<A NAME="church_bells"> </A>
  <h4>Church Bells - Removal operation for restoration</h4>
</p>

<table class="thumbnail">

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells001.jpg", "jpgs/bells/bells001.jpg", "205Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells002.jpg", "jpgs/bells/bells002.jpg", "131Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells003.jpg", "jpgs/bells/bells003.jpg", "138Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells004.jpg", "jpgs/bells/bells004.jpg", "196Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells005.jpg", "jpgs/bells/bells005.jpg", "110Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells006.jpg", "jpgs/bells/bells006.jpg", "225Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells007.jpg", "jpgs/bells/bells007.jpg", "670Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells008.jpg", "jpgs/bells/bells008.jpg", "735Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells009.jpg", "jpgs/bells/bells009.jpg", "615Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells010.jpg", "jpgs/bells/bells010.jpg", "400Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells011.jpg", "jpgs/bells/bells011.jpg", "550Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells012.jpg", "jpgs/bells/bells012.jpg", "430Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells013.jpg", "jpgs/bells/bells013.jpg", "525Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells014.jpg", "jpgs/bells/bells014.jpg", "635Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells015.jpg", "jpgs/bells/bells015.jpg", "800Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells016.jpg", "jpgs/bells/bells016.jpg", "650Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells017.jpg", "jpgs/bells/bells017.jpg", "715Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells018.jpg", "jpgs/bells/bells018.jpg", "645Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells019.jpg", "jpgs/bells/bells019.jpg", "380Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells020.jpg", "jpgs/bells/bells020.jpg", "590Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells021.jpg", "jpgs/bells/bells021.jpg", "670Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells022.jpg", "jpgs/bells/bells022.jpg", "705Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells023.jpg", "jpgs/bells/bells023.jpg", "700Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells024.jpg", "jpgs/bells/bells024.jpg", "405Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells025.jpg", "jpgs/bells/bells025.jpg", "390Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells026.jpg", "jpgs/bells/bells026.jpg", "600Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells027.jpg", "jpgs/bells/bells027.jpg", "605Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells028.jpg", "jpgs/bells/bells028.jpg", "600Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells029.jpg", "jpgs/bells/bells029.jpg", "540Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells030.jpg", "jpgs/bells/bells030.jpg", "545Kb"); ?>
	</tr>

	<tr class="thumbnail">
		<?php writeThumbTag("jpgs/bells/orig/bells031.jpg", "jpgs/bells/bells031.jpg", "505Kb"); ?>
		<?php writeThumbTag("jpgs/bells/orig/bells032.jpg", "jpgs/bells/bells032.jpg", "250Kb"); ?>
	</tr>


</table>


<br />


