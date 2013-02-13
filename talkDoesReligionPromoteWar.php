<?php
	/******************************
		Talk to MySQL
	**********************************/
	require_once("mysql.php");


	/******************************
		Start a session
	**********************************/
	require_once("session.php");
	$oSession = new session();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 14/01/2005 -->
<head>
<title></title>
<?php
	require_once("nocache.php");	// Stop - hopefully - this page being cached
?>

<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="newey499@hotmail.com">
<meta name="generator" content="AceHTML 5 Freeware">

  <!-- CSS Includes -->
  <link rel="stylesheet" type="text/css" href="css/layout.css" >
  <link rel="stylesheet" type="text/css" href="css/church.css" >
  <link rel="stylesheet" type="text/css" href="css/slideshow.css" >
  <link rel="stylesheet" type="text/css" href="css/tooltip.css" >
  <link rel="stylesheet" type="text/css" media="print" href="css/print.css" >
  <link rel="stylesheet" type="text/css" href="css/cssdropdownmenu.css" />
  <!-- End CSS Includes -->

</head>
<body>

<?php
	require_once("mysql.php");
	require_once("globals.php");
	require_once("genlib.php");
?>

<!-- ========================================================================== -->
<!--                            Start of CSS Layout                             -->
<!-- ========================================================================== -->
<div id="maincontainer">

<!-- ========================================================================== -->
<!--                             Start Top Section                              -->
<!-- ========================================================================== -->
<div id="topsection">
<div class="innertube">

<!-- Top Banner for site -->
<?php
	include_once("topbanner.php");
?>

</div>  <!-- End <div class="innertube"> -->
</div>  <!-- End <div id="topsection"> -->

<!-- This extra pointless div is needed to get IE out of the crap -->
<!-- Without it the top banner is not displayed                   -->
<!--[if IE ]>
	<div>
	</div>
<![endif]-->


<!-- ========================================================================== -->
<!--                               End Top Section                              -->
<!-- ========================================================================== -->



<!-- ========================================================================== -->
<!--                              Start Text Section                            -->
<!-- ========================================================================== -->
<div style="margin-left:2em; margin-right:2em;">

<p>
<?php
	backToSermonsTalksPage();
?>
</p>





<h4>
But aren't most wars caused by religion?
</h4>


<p>
A common objection to the Christian faith that is sometimes used by people is that religion is a cause of war and violence. It is true that sometimes religion has been a direct motivation for wars. The crusades are a well known example. It has also played an indirect role in promoting conflict e.g  the English Civil War. People have done terrible things, such as the Inquisition in the name of God. However there are many wars that have not had a direct religious cause.
</p>

<p>
This is a very complex and difficult question to answer because to give anything approaching a fair answer we would have to included potentally all religions (including those which don't claim to be religions) and all wars though all history.  Not an easy task! Discussion about this issue can be fruitless if people are unwilling to admit that they might be making some sweeping generalisations or that their knowledge of the subject is actually quite limited.
</p>

<p>
When asked a question Jesus often replied by asking a question back to the questioner. A good question to ask here would be, which religion or religions are have caused wars? As a Christian I can only try to give an answer for my own faith.   Everyone is religious in the sense that they have beliefs and principles that they live by. Has the questioner ever honestly looked at the impact of their own belief system on others? Have they ever asked if lack of a clear belief has contributed to conflict? Does the absence of a strong sense of truth or right and wrong make people indifferent to evil?
</p>

<p>
The fact that people have used Christainity to justify war does not mean that the belief in itself is wrong. There is strength in being honest. People have used Christainity to justify many things including slavery, the oppression of women, apartheid and the earth being at the centre of the solar system. This however the misuse of it by some misguided  people should not make us give it up. The answer to misuse is not disuse but right use.
</p>

<p>
Christianity may have been used to promote war but does atheism do any better? The Russian Writer Dostoyevsky  is reported as having said "If God does not exist, everything is permitted." The Communist regimes of China and USSR murdered about 100 million people in the pursuit of their aims. The radical Maoist, Pol Pot in Cambodia, killed 2 million people (a quarter of the population) in just 4 years. In comparsion the Spanish inquistion killed about 10,000 people over a period of 350 years. That horrible fact and others needs to be set alongside the far greater good that the church has done through the ages. However any faith, be it conventionally religious or a non-religious faith such as communism needs to be careful of what it boasts about. All our hands are to some extent stained with blood, though athesitic religions, which don't believe in a revealed truth, lack a deep rooted moral code that would restrain evil men. Does this make them prone to greater excesses?
</p>

<p>
A sober look at the history of religion and war perhaps leads us to conclude that the biblical view of man's fallen nature is accurate. Paul writes in Romans "For all have sinned and fall short of the glory of God". God has revealed himself to us in the person of Jesus but sometimes we lock him away in a prison of religion. Our sinful nature can distort anything. But with Jesus there is hope. Religion may fail but Jesus never fails. After all other aspects of this question have    been looked at Jesus is the best place to end.
</p>

<p>
The Apostle Peter wrote to the Christians living in the pagan culture of modern day Turkey with this advice "Always be prepared to give an answer to everybody who asks you to give  the reason for the hope that you have" 1 Peter 3:15. To reach unsaved and confused people today we need to do the same.
</p>





<p>
<?php
	backToSermonsTalksPage();
?>
</p>


<!-- ========================================================================== -->
<!--                              End Text Section                              -->
<!-- ========================================================================== -->
</div>


<!-- ========================================================================== -->
<!--                          Start Footer Content Section                      -->
<!-- ========================================================================== -->
<?php
	include('footer.php');
?>
<!-- ========================================================================== -->
<!--                            End Footer Content Section                      -->
<!-- ========================================================================== -->


<!-- ========================================================================== -->
<!--                            End of CSS Layout                               -->
<!-- ========================================================================== -->
</div>  <!-- END <div id="maincontainer"> -->


</body>
</html>
