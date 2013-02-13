
<div class="chromestyle" id="chromemenu">
<ul>

<?php

/********
 Write the drop down menu headers
******************/

$qry = "select itemgroup, prompt, target FROM menutitles WHERE isvisible = 'YES' ORDER BY itemgroup";

// Perform Query
$result = mysql_query($qry);

if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $qry;
    die($message);
}

while ($row = mysql_fetch_assoc($result)) 
{
	if ( ! empty($row['target']) )
	{
		// Assume that if something is in <target> then its a URL
		print("<li>\n");
		print("<a href=\"" . $row['target'] . "\">" . $row['prompt'] . "</a>\n");
		print("</li>\n");

	}
	else
	{
		// nothing in <target> so assume its the heading for a drop down menu
		print("<li>\n");
		print("<a href=\"#\" rel=\"" . "dropmenu" . $row['itemgroup'] . "\">" . $row['prompt'] . "</a>\n");
		print("</li>\n");
	}

}

?>

</ul>
</div>


<?php

/********
 Write the drop down menu items
******************/

// Move back to start of query
mysql_data_seek($result, 0);

while ($row = mysql_fetch_assoc($result)) 
{
	if ( empty($row['target']) )
	{
		// nothing in <target> so assume its the heading for a drop down menu
		writeDropDownMenuItems($row['itemgroup'], "dropmenu" . $row['itemgroup']);
	}

}



mysql_free_result($result);

?>


<?php

function writeDropDownMenuItems($menuGroup, $menuName)
{
	print("<div id=\"" . $menuName . "\" class=\"dropmenudiv\">\n");

	buildDropDownLinksFromTable($menuGroup, $menuName);

	print("</div>\n");

}

?>


<?php

/*******************
	function to build menu links for drop down menu items
**************************************************/
function buildDropDownLinksFromTable($menuGroup, $menuName) 
{
	$qry =  'SELECT * FROM menuitems ' . 
					'WHERE itemgroup = ' . $menuGroup . ' AND isvisible = "YES" '.
					'  AND (itemtype = "MENU_ITEM" OR itemtype = "MENU_ITEM_DROP") ' .
					'ORDER BY menucol, itemgroup, itemorder';

	$res = mysql_query($qry);

	while ($row = mysql_fetch_assoc($res)) 
	{
		
		// If we ain't got a prompt refuse to play
		if (trim($row['prompt']) == '') 
		{		
			die("Invalid Menu record configuration (Empty Prompt) menus.id=" . $row['id']);			
		}
	
		// if a link to a target file is given then that takes priority
		if (trim($row['target']) <> '') 
		{
			// build link based upon assumption that external links will commence with 
			// "http:"
			if (strToUpper(substr($row['target'],0,5)) == 'HTTP:') 
			{
				buildDropDownMenuItem($row['target'],
        				              stripslashes($row['prompt']),
        				              $row['lastupdate']);					
			}
			else 
			{
				buildDropDownMenuItem('index.php?displaypage=' .
        				              $row['target'],stripslashes($row['prompt']),
        				              $row['lastupdate']);	
			}
		}
		else 
		{
			// no link to file - see if there's any text in the blob (text) field
			if (trim($row['content']) <> '') 
			{
				$args = http_build_query(array('displaypage' => INTERNAL_CONTENT_FLAG,
  																		 'id' => $row['id']	
																			)
																);
				buildDropDownMenuItem("index.php?$args",
        				              stripslashes($row['prompt']),
        				              $row['lastupdate']);						
			}
			else 
			{
				die("Invalid Menu record configuration menus.id=" . $row['id']);
			}
		}
	}
			
	mysql_free_result($res);
	
}



// Creates a link to $target with prompt = $prompt
function buildDropDownMenuItem($target,$prompt,$lastupdate = "") 
{

print("<a href=\"" . $target . "\">" . $prompt);

/********
Display an asterisk before any menu item that has been
updated in the last 7 days
*************/
// 7 days; 24 hours; 60 mins; 60secs
// RECENT_PAGE_UPDATE_DAYS is defined in globals.php
$weekSeconds = (RECENT_PAGE_UPDATE_DAYS * 24 * 60 * 60); 
if ( strtotime('now') < (strtotime($lastupdate) + $weekSeconds) )
{
	recentUpdateFlag();
}

print("</a>");


}

?>



<script type="text/javascript">

cssdropdown.startchrome("chromemenu")

</script>
