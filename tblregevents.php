<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 02/12/2004 -->
<head>
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="newey499@hotmail.com">
	<meta name="generator" content="AceHTML 5 Freeware">
	<link rel="stylesheet" type="text/css" href="/css/server.css" />
	<link rel="stylesheet" type="text/css" href="css/site.css" />		
</head>
<body>
<!-- ------------------ HEADER --------------------------------------- -->	
<H3>Bookshop Catalogue Demo - vhosts/books</H3>
<hr />
<!-- ---------------- END HEADER ------------------------------------- -->
<?php
require_once "globals.php";
require_once "mysql.php";
require_once "dumpquerytotable.php";
?>
<p>
<H4>Demomain</H4>
</P>

<?php

$dbHandle = mysqli_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
	   or die("Could not connect to server: MYSQL_SERVER : " . mysqli_error());
mysqli_select_db($dbHandle,MYSQL_DATABASE) or
	 die("Could not select database: " . MYSQL_DATABASE . " : " . msql_error());	 

$qry = "SELECT id,title,concat(au_fname,' ',au_lname)as Author,datepublished,saleprice,notes 
        FROM webbooks AS w, authors AS a 
				WHERE w.au_id = a.au_id";

$obj = new dumpQryToTable($dbHandle,$qry);

//$obj->columnTitles = array("Title","Author","Published","Price","Notes");
$obj->borderSize = 1;
$obj->editTarget = "editdeletebooks.php";
$obj->deleteTarget = "updatewebbooks.php";

$obj->addPrimaryKeyColumn('id');

$obj->addColumn("title","Title");
$obj->addColumn("Author","Author");
$obj->addColumn("datepublished","Published");
$obj->addColumn("saleprice","Price");

//$obj->tableClass = "error";
//$obj->thClass = "error";
//$obj->tdClass = "error";



$obj->exec();



$args = http_build_query(array('opcode' => INSERT_REC));

echo "<p>";
echo "<a href=\"editdeletebooks.php?$args\" title=\"add new book\">Add</a> a new book to the database";
echo "</p>";

?>

<!-- ------------------ FOOTER --------------------------------------- -->
<HR>
<div class=rightalign>   &copy; Chris Newey 2004: </div>
</body>
</html>
<!-- ---------------- END FOOTER ------------------------------------- -->
