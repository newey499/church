<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 02/12/2004 -->
<head>
<?php
	$pageTitle = "Ad-hoc Test Page";
	print("<title>$pageTitle</title>\n");
?>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="newey499@hotmail.com">
	<meta name="generator" content="AceHTML 5 Freeware">
	<link rel="stylesheet" type="text/css" href="../css/site.css" />		
</head>
<body>
<!-- ------------------ HEADER --------------------------------------- -->	
<?php
print "<H3>$pageTitle</H3>\n";
?>
<hr />
<!-- ---------------- END HEADER ------------------------------------- -->


<?php
require_once("../mysql.php");

$sdate = "2005-01-02";
$udate = "2005-01-02";
	
$test 	= new mysqldate();


$tim = new mysqltime;
dbug($tim->checkTime("00:00"));
dbug($tim->checkTime("12:59"));
dbug($tim->checkTime("00:60"));
dbug($tim->checkTime("24:00"));

	
dbug("<P>SQL Date is " . ($test->storeDate(mysqldate::MYSQL_DATE,$sdate) ? "Valid" : "Invalid") . " $test->date</P>\n");
dbug("<P>UK Date is " . ($test->storeDate(mysqldate::UK_DATE,$udate) ? "Valid" : "Invalid") . " $test->date</P>\n");
	
	
?>

<!-- ------------------ FOOTER --------------------------------------- -->
<HR>
<div class=rightalign>    Chris Newey 2004: </div>
</body>
</html>
<!-- ---------------- END FOOTER ------------------------------------- -->



