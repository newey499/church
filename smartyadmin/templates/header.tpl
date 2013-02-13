<html>
<head>

	<title>{$title|default:"no title"}</title>

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

		{if file_exists("templates/devsystemsemaphore.tpl") }
			{include file="devsystemsemaphore.tpl"}
		{/if}

	</span>		

</div>	

<!-- END TOP BANNER -->
<div id="maincontainer">


