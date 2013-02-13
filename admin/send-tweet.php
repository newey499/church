<html>
<?php
/*************************************

send-tweet.php

Does what it says

Date		Programmer		Description
01/05/2012		CDN			Created

****************************************/
?>

<head>
	<title></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="Chris Newey">
	<meta name="generator" content="AceHTML 5 Freeware">

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="../css/layout.css" >
  <link rel="stylesheet" type="text/css" href="../css/church.css" >
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="../css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="../css/print.css" >
  <link rel="stylesheet" type="text/css" href="../css/cssdropdownmenu.css" />
  <!-- End CSS Includes -->

</head>

<body>


<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">



<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>


<br>
<h2>Send Tweet</h2>


<?php

require_once("../class.twitter.php");


if (isset($_POST['tweet-text']))
{
	$reply = "reply";

	$oTwitter = new Twitter();

	print("<h4>Send a Tweet</h4>");



	print("<p>");
	if (empty($_POST['tweet-text']))
	{
		print("Cannot send an empty Tweet");
	}
	else
	{
		$reply = $oTwitter->sendTweet($_POST['tweet-text']);
		print("<p>");
		print($_POST['tweet-text']);
		print("</p>");
		print("<p>");
		//print_r($reply);
		print("</p>");
	}
	print("</p>");

}
else
{

?>

	<p>
	Note: sending a Tweet will copy the Tweet to Facebook.
	</p>

	<p>

	<form name="input" action="send-tweet.php" method="post" >

	Text to Tweet (Max 140 characters):
	<br />
	<br />
	<input type="text" name="tweet-text" size="120" maxlength="140" value="" />
	<br />
	<br />
	<input type="submit" value="Submit" />

	</form>

	</p>

<?php
}
?>


<p>
<a href="index.php" title="admin">Admin</a>
&nbsp;
&nbsp;
<a href="../index.php" title="admin">Home</a>
</p>


</div> <!-- End div  id="maincontainer"> -->
<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->


</body>
</html>
