<?php
/*****************************************************************
 Dump an SQL query into an HTML Table headed by column names

 Notes
 =====
 
28/12/04	CDN			Created
27/02/05  CDN			Add addLinkColumn method
14/03/05  CDN     Use stripshlashes on text and textarea columns
*************************************************************************/
require_once("mysql.php");


class dumpQryToTable {

	var $columnTitles = array();			// An array containing titles to be used for the table header. If not present then
	                                 			// the table column names are used.	
	var $dbHandle;										// Database Handle returned from  a mysqlj_connect call 
	var $query;												// SQL Query string
	var $primaryKeyFields;						// name of each column in primary key
	var $cursorpos = 0;               // Current row number in result set - not primary key value
	var $links;												// Associative array key => value where key is url to jump and 
																		// value is prompt for link	 			
	var $colLinks = array();					// Associative array key => value where key is url to jump and 																		// value is prompt for link	 			
	var $displayQueryString = False;		// Display query string or not
	var $insertTarget;									// The page to be called when the user clicks on the Add link which is only displayed
																				// if this var contains something. Eg. editRecOnThisPage.php			
	var $editTarget;										// The page to be called when the user clicks on the edit link which is only displayed
																				// if this var contains something. Eg. editRecOnThisPage.php
	var $deleteTarget;									// The page to be called when the user clicks on the edit link which is only displayed
																				// if this var contains something. Eg. editRecOnThisPage.php																		
																			
	var $displayTitles = True;					// Display Table Header or not
	var $tableClass;										// CSS Class to be applied to the table
	var $thClass;											// CSS Class to be applied to the table header
	var $tdClass;											// CSS Class to be applied to table data rows
	var $tableId;											// CSS Id to be applied to the table
	var $numberOfRows = 10;						// Number of table rows to display
	
	// Constructor
	function dumpqrytotable($dbHandle,$queryArg, $tableRows = 10) {


		if (isset($dbHandle)) {
			$this->dbHandle = $dbHandle;
		}
		else {
			die("No database handle passed to constructor");
		}

		if (isset($queryArg)) {
			$this->query = $queryArg;
		}
		else {
			die("No SQL Query string passed to constructor");
		}
		if (isset($tableRows)) {
			$this->numberOfRows = $tableRows;
		}

	}

	// Display navigation links
	function showLinks($result) {

		$alinks;
		

		echo "<p>\n";

	 	// If we aren't on the first row then provide a link to go backwards up the recordset	    if($this->cursorpos > 0) {
     	global $PHP_SELF;
     	$previous = True;
     	$args = http_build_query(array('opcode' => PAGE_UP,
                                    'cursorpos' => $this->cursorpos - $this->numberOfRows   ));
     	$alinks[] = "&nbsp;<a href=\"$PHP_SELF?$args\">Previous</a>&nbsp;\n";     	

    }
    else {
	   	// print spaces to keep links from moving around on page 
	   	//echo str_repeat("&nbsp;", strlen("Previous"));

    }


    // link to Add a new record    if (isset($this->insertTarget)) {
    	$add = True;
			$args = http_build_query(array('opcode' => INSERT_REC));
			$alinks[] = "&nbsp;<a href=\"$this->insertTarget?$args\" title=\"add\">Add</a>\n";	
     	//echo "&middot";
		}

		// other links 
		if (isset($this->links)) {
			foreach ($this->links as $key => $value) { 
				$link = True;
				$alinks[] = "&nbsp;<a href=\"$key?\">$value</a>\n";			
	     	//echo "<b>&middot</b>";				
			}
		}

    // If we aren't on the last row then provide a link to go forwards down the recordset	    if(mysql_num_rows($result) >= $this->numberOfRows) {
    	global $PHP_SELF;
    	$next = True;
    	$args = http_build_query(array('opcode' => PAGE_DOWN,
                                   	 'cursorpos' => $this->cursorpos + $this->numberOfRows   ));    
    	$alinks[] = "&nbsp;<a href=\"$PHP_SELF?$args\">Next</a>&nbsp;\n";     	
    }
    else {
	   	// print spaces to keep links from moving around on page 	    
	   	//echo str_repeat("&nbsp;", strlen("Next"));	   	
    }	


		for ($i = 0; $i < count($alinks); $i++) {
			echo $alinks[$i];
			if ($i < (count($alinks) - 1)) {
	    	echo "&middot";
	    }
     }
	
		echo "</p>\n";
	}


	// Add a column name and title 	
	function addColumn($colName, $colTitle) {
		$this->columnTitles[$colName] = $colTitle;
		return $this;		
	}
	
	// Add a link
	function addLink($url,$prompt) {
		$this->links[$url] = $prompt;
		return $this;
	}

	// Add a column link	function addColumnLink($colTitle, $url, $linkText) {
		$this->colLinks[] = array('coltitle' 	=> $colTitle,
															'url'				=> $url,
															'linktext'	=> $linkText);
		return $this;
	}
	
	
	function addPrimaryKeyColumn($colName) {
		$this->primaryKeyFields[$colName] = '';		
		return $this;
	}

	function exec() {
	
		if (!isset($this->query)) {
			die("No Query String to work on");
		}

		if (isset($_GET['cursorpos'])) {
			$this->cursorpos = (int) $_GET['cursorpos'];
		}		
		
		
		$temp = $this->query . " LIMIT $this->cursorpos, $this->numberOfRows";
		$result = mysql_query($temp,$this->dbHandle) or die("Query failed: [$temp] " . mysql_error());

		// Printing results in HTML
		echo "<p>";
		// Display the query string if told to		
		if ($this->displayQueryString) {
			print("<p><h4>$this->query</h4></p>\n");
		}

		// Set the table class
		print("<table  ");
		
		if (isset($this->tableClass)) {
			print("class=\"" . $this->tableClass . "\"");
		}
		if (isset($this->tableId)) {	
			print("id=\"" . $this->tableId . "\"");
		}
		print(" >\n");

		
		// Write table headers if told to do so
		if ($this->displayTitles) {
		
			// If an array of column titles has been passed then use these
			// otherwise use the field names as column headings
			if (!empty($this->columnTitles)) {
				foreach ($this->columnTitles as $key => $value) {
				  echo	"\t<th " . 
				  			(isset($this->thClass) ? " class=\"$this->thClass\" " : "") . 
							 	" >$value</th>\n";				
				}
			}
			else {
				/* set up the table headers */
				foreach ($this->columnTitles as $key => $value) {				
						echo "\t<th  " . 
								 (isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
								 ">$value</th>\n";					
				}
			}
			
			// Add any column links
			foreach($this->colLinks as $col) {
				echo "\t<th  " . 
							(isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
							">" . $col['coltitle'] . "</th>\n";							
			}
			
			
			// Add an edit link if told to do so			
			if (isset($this->editTarget)) {
		  	echo "\t\t<th " . 
		  			 (isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
		  			 ">Edit</th>\n";			
			}		
			// Add a delete link if told to do so			
			if (isset($this->deleteTarget)) {
		  	echo "\t\t<th " . 
		  			 (isset($this->thClass) ? " class=\"$this->thClass\" " : "") . 
		  			 ">Delete</th>\n";			
			}					
			
			print("\n\n<br />\n\n");
		}



		// Get row data
		while ($row = mysql_fetch_assoc($result)) {
			echo "\t<tr>\n";	
			// one column for each column name in $this->columnTitles
			foreach ($this->columnTitles as $key => $value) {
				echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") .
						 ">" . stripslashes($row[$key]) . "</td>\n";					
			}
			
			// Stash the primary key value for use when building the edit and delete links
			foreach  ($this->primaryKeyFields as $key => $value) {
				$this->primaryKeyFields[$key] = $row[$key];
			}


			// Add any column links
			foreach($this->colLinks as $col) {
				$args = http_build_query($this->primaryKeyFields);
	  		echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") .
	  				 "><a href=\"" . $col['url'] . "?$args\" title=\"" . $col['url'] . $i .
	  				 "\">" . $col['linktext'] . "</a></td>\n";							
			}

		
			// Add an edit link for the row if told to do so
			if (isset($this->editTarget)) {
				$args = http_build_query(array('opcode' => UPDATE_REC) + $this->primaryKeyFields);
	  		echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") . "><a href=\"$this->editTarget?$args\" title=\"Edit$i\">Edit</a></td>\n";						
  		}

			// Add a Delete link for the row if told to do so
			if (isset($this->deleteTarget)) {			
				$args = http_build_query(array('opcode' => DELETE_REC) + $this->primaryKeyFields);
	  		echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") . "><a href=\"$this->deleteTarget?$args\" title=\"Delete$i\">Delete</a></td>\n";						
  		}
		
			echo "\t</tr>\n";	
		}

		// Adjust the cursor position 
   	if (!isset($_GET['cursorpos']) || (int)$_GET['cursorpos'] < 0) {
     	$this->cursorpos = 0; 
   	}
   	else {
      $this->cursorpos = (int)$_GET['cursorpos'];
   	}

		echo "</table>\n";	

		// display navigation links
		$this->showLinks($result);

		
		// Free resultset
		mysql_free_result($result);

		return $this;
	}	
}


?>
