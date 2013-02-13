212
a:4:{s:8:"template";a:4:{s:12:"template.tpl";b:1;s:22:"devsystemsemaphore.tpl";b:1;s:10:"header.tpl";b:1;s:10:"footer.tpl";b:1;}s:9:"timestamp";i:1250626552;s:7:"expires";i:1250630152;s:13:"cache_serials";a:0:{}}<html>
<head>

	<title>template.tpl - Change This</title>

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />	

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" />	
  <link rel="stylesheet" type="text/css" href="css/church.css" />	
  <link rel="stylesheet" type="text/css" href="css/adminlayout.css" />	

</head>

<body>

<!-- START TOP BANNER -->

<div style="border-bottom: blue 2px solid; padding-bottom:2px; padding-left:1em;" >
	<a href="http://www.cofe.anglican.org/">
	<img src="../jpgs/cofe_logo030.gif" 
	     onmouseover="this.style.cursor='pointer'" 
   		 alt="Link to Church of England Website" 
			 title="Link to Church of England Website" 
	/> 
	</a>
	
	&nbsp;&nbsp;

	<span class="christchurchtitle">
		Christ Church
	</span>

	<span class="christchurchaddress">
		High Street, Lye, Stourbridge, West Mids, UK. DY9 8LF
	</span>

	<span class="developmentsystem">

		&nbsp;
&nbsp;
&nbsp;
&nbsp;
[Test System]
	</span>		

</div>	

<!-- END TOP BANNER -->
<div id="maincontainer">



<br>
<h2>template.tpl</h2>

<p>

To use the template - 

</p>

<p>
From docroot of website
<br />
<br />
cp template.php newfile.php
<br />
cp templates/template.tpl templates/newfile.tpl
</p>


<p>
Change the line at the top of newfile.tpl to give the title of the new page
</p>

<p>

<div class="code" >
<tt>

{include file="header.tpl" title="template.tpl - Change This Page Title"}

</tt>
</div>

</p>




<p>
Change the line at the bottom of newfile.php to point to the new template file Eg. newfile.tpl
</p>

<p>

<div class="code" >
<tt>
// display the associated template file
<br />
// Change this to the name of the new Template File Eg. newfile.tpl
$smarty->display('template.tpl'); 
</tt>
</div>

</p>

<br />


</div> <!-- End <div id="maincontainer"> -->

<!-- ========================================================================== -->
<!--                          Start Footer Content Section                      --> 
<!-- ========================================================================== -->
<div id="footer" class="footer">
<div class="innertube">

Copyright &copy; 2007 - 2009 Christ Church Parish Council
<br />

</div> <!-- END <div class="innertube"> -->
</div> <!-- END <div id="footer"> -->
<!-- ========================================================================== -->
<!--                            End Footer Content Section                      --> 
<!-- ========================================================================== -->

</body>
</html>

