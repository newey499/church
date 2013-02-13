<html>

<head>


</head>


<body>

<h4>
twitter.php
</h4>

<?php

require_once("class.twitter.php");

$tweet = "Test 003 chris-newey.crabdance.com";
$reply = "reply";

$oTwitter = new Twitter();

$reply = $oTwitter->sendTweet($tweet);

print_r($reply);


?>


</body>

</html>