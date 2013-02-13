<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<!-- saved from url=(0030)http://www.glish.com/css/7.asp -->
<HTML>
<HEAD>
	<TITLE>The website of Christ Church, Lye, West Midlands UK</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=windows-1252">
	<link rel="stylesheet" type="text/css" href="site.css" />		
	<link rel="stylesheet" type="text/css" href="all.css" />		
	<link rel="stylesheet" type="text/css" href="3cols.css" />		
	<META content="MSHTML 6.00.2800.1479" name=GENERATOR>
<?php

/******
This form calls itself with an opcode and sometimes a row primary key.
These constants reflect the various opcodes that decide what processing the 
form carries out.
******************/
define("REG_EVENTS",    1);		// Display Regular events in central DIV from database
define("FORTH_EVENTS",  2);		// Display Forthcoming events in central DIV from database
define("BLOG_ENTRIES",  3);		// Display Blog Entries

require_once('upcomingevents.php');	
require_once('blog.php');	

// Default to this option when loading this page without an opcode
if (!isset($_GET['opcode'])) {
	$_GET['opcode'] = FORTH_EVENTS;	
}


?>	
</HEAD>


<BODY>


<!-- START BANNER DIV -->
<DIV id=banner>

<span style="float:left;margin-top:2px; margin-left:5px;">
<img src="cross002.jpg" border="0" width="20px" height="30px" alt=""> 
</span>

<span style="margin-top:0px; margin-left:10px; font-size:30px; font-weight:bold;">
Christ Church
</span>
High Street, Lye, Stourbridge, West Midlands, UK
</DIV>
<!-- END BANNER DIV -->



<!-- START LEFT COLUMN DIV -->
<DIV id=leftcontent>

<?php
include("mainmenu.php");
?>

</DIV>
<!-- END LEFT COLUMN DIV -->



<!-- START CENTER COLUMN DIV -->
<DIV id=centercontent>


<?php

if (isset($_GET['opcode'])) {

	switch ($_GET['opcode']) {
	case REG_EVENTS:
		print "<H1>Regular Events</H1>\n";
    regular_events();
    break;
	case FORTH_EVENTS:
		print "<H1>Forthcoming Events</H1>\n";
    forthcoming_events();
    break;
  case BLOG_ENTRIES:
		print "<H1>Church Blog</H1>\n";  
    blog_display();
    break;		
	default:
    echo "i is not equal to 0, 1 or 2";
	}
}
?>



</DIV>
<!-- END CENTER COLUMN DIV -->


<!-- START RIGHT COLUMN DIV -->
<DIV id=rightcontent>
<?php
	$args = http_build_query(array('opcode' => REG_EVENTS));
	echo "<br>\n";
 	echo "<p>\n<a href=\"try3cols.php?$args\">Regular Events</a></p>\n";		
	
	$args = http_build_query(array('opcode' => FORTH_EVENTS));
 	echo "<p><a href=\"try3cols.php?$args\">Forthcoming Events</a></p>\n";			
	
	$args = http_build_query(array('opcode' => BLOG_ENTRIES));
 	echo "<p><a href=\"try3cols.php?$args\">Christ's Church Blog</a></p>\n";	
 	
 			
	echo "<br>\n";	
?>


</DIV>
<!-- END RIGHT COLUMN DIV -->


<!-- END OF DIVS - ENTIRE WIDTH OF BROWSER AVAILABLE -->
<BR style="CLEAR: both">


</BODY>

</HTML>