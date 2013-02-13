<?php

require_once 'epd_cookie.class.php';
$epd = new epd_cookie();

$epd->check_epd_cookie();

if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $referer = $_POST['referer'];
    $approve = $_POST['approve'];

    if ($approve == 'yes')
      {
        $epd->set_epd_cookie($referer);
        exit;
      }
    else if ($approve == 'no')
      {
        $epd->master_redirect();
        exit;
      }
  }
?>
<!DOCTYPE html>
<html>

<head>

<title>Cookie Consent</title>

  <link rel="stylesheet" type="text/css" href="css/layout.css" >
  <link rel="stylesheet" type="text/css" href="css/church.css" >
	<link rel="stylesheet" href="css/epd_cookie_class.css" type="text/css" media="screen">
</head>

<body>

<p id="topbanner" >

Christ Church

<span style="font-size:0.75em;">
High Street, Lye, Stourbridge, West Mids, UK. DY9 8LF
</span>


</p>


  <div class="container">
    <div class="c">
      <div class="inner">
        <h3>Cookies</h3>
        <br />
        <p>This site uses cookies for session management.</p>
        <br />
        <p>Under the European Privacy and Electronic Communications directive, we are required by law to obtain "explicit consent" from you.</p>
        <br />
        <p>Do you give us permission to set and retrieve cookies, please note if you say no you will be redirected away from this site as this site CANNOT function without cookies.</p>
        <br />
        <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
          <fieldset>
            <input type="hidden" name="referer" value="<?=$referer?>">
            <select name="approve">
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
            <input type="submit" value="Submit Yes/No answer to Cookie use" ><br>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
