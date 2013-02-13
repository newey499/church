
<?php
require_once "globals.php";
include_once "mysql.php";


mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');

/**********
 Prints contact details for a forthcoming event
****************/
function printContactInfo($contribemail,
													$contactname,
													$contactphone,
													$contactemail)
{
	$ct = "";

	if ( empty($contactname) && empty($contactphone) && empty($contactemail) )
	{
		return $ct;
	}

	$ct .= "<hr>\n";

	$ct .= "<table>\n";
	$ct .= "<tr>\n";

	$ct .= "<form action=\"index.php?displaypage=mailform.php\" method=\"post\">\n";

	$ct .= "<td>\n";
	$ct  .= "Contact Points\n";
	$ct .= "</td>\n";

	if (! empty($contactname))
	{
		$ct .= "<td>\n";
		$ct .= $contactname;
		$ct .= "</td>\n";
	}
	if (! empty($contactphone))
	{
		$ct .= "<td>\n";
		$ct .= "Phone: " . $contactphone;
		$ct .= "</td>\n";
	}
	if (! empty($contactemail))
	{
		//$ct .= "<form action=\"index.php?displaypage=mailform.php\" method=\"post\">\n";
		$ct .= "<td>\n";
		$ct .= "<INPUT type=\"hidden\" name=\"emailrecipient\"\n";
		$ct .= "value=\"" . $contactemail . "\">\n";
		$ct .= "&nbsp;&nbsp;";
		$ct .= "<INPUT type=\"submit\" value=\"Email\">\n";
		$ct .= "</td>\n";
		//$ct .= "</form>\n";
	}

	$ct .= "</form>\n";

	$ct .= "</tr>\n";
	$ct .= "</table>\n";
	//$ct .= "<br />\n";

	return $ct;
}





function displayForthcomingEvents()
{

$qry = "SELECT	id,
				orgid,
				DATE_FORMAT(eventdate,'%W %D %M %Y') as displayEventDate,
				DATE_FORMAT(eventdate,'%W %D %M %Y at %l:%i%p') as displayEventDateTime,
                DATE_FORMAT(eventdate,'%H:%i') as eventTime,
			 	eventname,
			 	eventdesc,
				contribemail,
				contactname,
				contactphone,
				contactemail,
                isvisible
        FROM forthcomingevents ";

if (! empty($_POST['forthcomingeventsearch']))
{
	$like = '%' . mysql_real_escape_string($_POST['forthcomingeventsearch']) . '%';
	$qry .= " WHERE eventname LIKE '" . $like . "'" .
					" OR eventdesc LIKE '" . $like . "'" .
					" OR contribemail LIKE '" . $like . "'" .
					" OR contactname LIKE '" . $like . "'" .
					" OR contactphone LIKE '" . $like . "'" .
					" OR contactemail LIKE '" . $like . "'" .
					" OR DATE_FORMAT(eventdate,'%H:%i') LIKE '" . $like . "'" .
					" OR DATE_FORMAT(eventdate,'%W %D %M %Y at %l:%i%p') LIKE '" . $like . "'" .
					" OR DATE_FORMAT(eventdate,'%W %D %M %Y') LIKE '" . $like . "'";

}

$qry .= " ORDER BY eventdate";


$result = mysql_query($qry) or die('Query failed: ' . mysql_error());

if (mysql_num_rows($result) == 0)
{
	// CDN 4/1/10 - Tweak message slightly to take account of empty $_POST['forthcomingeventsearch'] string
	if (! empty($_POST['forthcomingeventsearch']))
	{
		print("<h4>No Events found containing [" . $_POST['forthcomingeventsearch'] . "]</h4>\n");
	}
	else
	{
		print("<h4>No Forthcoming Events found.</h4>\n");
	}
}

/*******************************
 Printing results in HTML

08/12/08 CDN 	Now have a fixed header that stays in place as the page scrolls.
							Because of this the internal anchor is hidden by the header (top banner).
							To work around this the anchor for an article is written at the top of
							the previous article.
18/02/09 CDN  Fixed header no longer used but this code unchanged
*********************************/
$article = '';
$lastArticle = '';
$articleIdAnchor = '';
$lastArticleIdAnchor = '';

$firstArticle = 0;

$articleCount = 0;

while ($line = mysql_fetch_assoc($result))
{
	if ($line['isvisible'] != 'YES')
	{
		continue; // ignore items flagged as not visible
	}

	// Link for use by rss feed
	$articleIdAnchor = "<a NAME=articleid" . $line['id'] .
                     "></a>\n";

	// Article Information
	$articleCount++;
	$article = "";
	$article .=	"<div class=\"forthcomingEventTitle\" >\n";
	$article .= "<b>";
	$article .= stripslashes($line['eventname']);

	$article .= "<br />\n";
	if ($line['eventTime'] == FC_HIDE_TIME) // FC_HIDE_TIME (11:11) is a magic time that means do not display time
	{
		$article .=	stripslashes($line['displayEventDate']);
	}
	else
	{
		$article .=	stripslashes($line['displayEventDateTime']);
	}
	$article .= "</b>\n";
	$article .=	"</div>\n";   // end "<div class=\"forthcomingEventTitle\" >\n";

	$article .=	"<div class=\"forthcomingEventDescription\" >\n";
	$article .=	"<br />\n";
	$article .= stripslashes($line['eventdesc']);
	$article .= printContactInfo($line['contribemail'],
								 $line['contactname'],
								 $line['contactphone'],
								 $line['contactemail']);
	$article .=	"</div>\n";		// end "<div class=\"forthcomingEventDescription\" >\n";

	if ($firstArticle == 0)
	{	// If its the first article just print the Anchor
		$firstArticle = 1;					// reset the pass counter
		$lastArticle = $article;		// stash the current article in the previous article variable
		$lastArticleIdAnchor = $articleIdAnchor;
	}
	else
	{
		if ($firstArticle == 1)
		{
			$firstArticle = 2;						// reset the pass counter
			print($lastArticleIdAnchor);	// print the internal link for the previous article
		}
		print($articleIdAnchor);	// print the internal link for this article
		print($lastArticle);			// print the previous article
		$lastArticle = $article;	// stash the current article
		$lastArticleIdAnchor = $articleIdAnchor;	// stash the current article anchor
	}

}

// At the end of the loop there is still the final article to print
// Its internal link has already been printed out
print($lastArticle);
$articleCount++;


// Free resultset
mysql_free_result($result);


return "";

}


?>
<!-- ============================================================= -->


<br />
<h2>Whats On</h2>

<h4>
Diary of Forthcoming Events
</h4>

<p>

<b>
For further information contact the Church Secretary
<br />
Gloria Burrows
<br />
01384 - 894948
<br />

or use our RSS Feed.
&nbsp;&nbsp;

<img	src="jpgs/feed-icon-14x14.png"
      alt="RSS Feed of Christ Church Forthcoming Events"
  		onclick="location.href='http://www.christchurchlye.org.uk/rss.xml'"
			onmouseover="this.style.cursor='pointer'"
			title="RSS Feed of Christ Church Forthcoming Events"
			width="14" height="14" />


</b>

<p>
		<form action="index.php?displaypage=mailform.php" method="post">

		<input type="hidden" name="emailrecipient"
		value="gloria.burrows@christchurchlye.org.uk">
		<input type="submit" value="Email">

		</form>

</p>

<p>

<table style="margin-left:0;">

<tr>

<td>

<form name="showall"
<?php
  print('action="http://' . WEBSITE_DOMAIN . '/index.php?displaypage=dispforthevent.php"');
?>
  method="post">

	<input type="hidden" name="forthcomingeventsearch"
	value="">
	<input type="submit" value="Show All Events">
</form>

</td>

<td>

<form name="input"
<?php
  print('action="http://' . WEBSITE_DOMAIN . '/index.php?displaypage=dispforthevent.php"');
?>
  method="post">

	<input type="submit" value="Search Events">

	&nbsp;

	<input type="text"
       name="forthcomingeventsearch"
			 value="<?php print( (isset($_POST['forthcomingeventsearch']) ? $_POST['forthcomingeventsearch'] : "") ); ?>"
       width="60"
       maxlength="60">

</form>

</td>


<td>

<a href="index.php?displaypage=internal.html&rowid=18">
Weekly Services
</a>

</td>



</tr>

</table>

</p>


<br />


<?php
	displayForthcomingEvents();
?>

<br />

<p>
	<?php
	/************************
	this is html but use php to comment it out
	You may also like to look at the
	<a href="http://christchurchlye.org.uk/index.php?displaypage=internal.html&rowid=73">
	Church Newsletters
	</a>
	.  <!-- Note full stop outside link -->
	(pdf format)

	***********************************/
	?>

	<br />
  <b>Forthcoming events for the Worcester Diocese</b> may be found in the
  Diocesan

  <a href="http://www.cofe-worcester.org.uk/AA/154">
  newsletter.
  </a>

  <br />
  The newsletter is stored on the Worcester Diocesan website in PDF format.
  You may need to download a copy of the

  <a href="http://www.adobe.com/downloads/">
  Adobe Reader
  </a>


  software or another pdf reader such as Foxit for
	<a href="http://foxitsoftware.com/Secure_PDF_Reader/">
  Windows
	</a>
	or
	<a href="https://www.foxitsoftware.com/pdf/desklinux/">
  Linux
	</a>

  all of which are free.

</p>





