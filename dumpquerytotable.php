<?php
/*****************************************************************
	Dump an SQL query into an HTML Table headed by column names

	Notes
	=====

28/08/09		CDN			TODO Replace/Provide public variables with get/set accessor methods


	History
	=======

28/12/04		CDN			Created
27/02/05  	CDN			Add addLinkColumn method
28/08/09		CDN			Rewrite as a PHP 5 Class
16/09/09		CDN     Fix Bug where Next Page option was being offered when
                    no more data existed - only happened when number of rows returned
                    by query was an eaxct multiple of rows per page.
*************************************************************************/
require_once("mysql.php");


class dumpQryToTable
{

	/*****************
		Public Vars
	************************/
	public $insertTarget = null;			// The page to be called when the user clicks on the Add link
																		// which is only displayed
																		// if this var contains something. Eg. editRecOnThisPage.php
	public $editTarget = null;				// The page to be called when the user clicks on the edit
																		// link which is only displayed
																		// if this var contains something. Eg. editRecOnThisPage.php
	public $deleteTarget = null;			// The page to be called when the user clicks on the edit link
																		//  which is only displayed
																		// if this var contains something. Eg. editRecOnThisPage.php
	public $borderSize = 1;						// Deprecated - value ignored - now expected to be set via CSS
	public $displayTitles = True;			// Display Table Header or not
	public $tableClass = null;				// CSS Class to be applied to the table
	public $thClass = null;						// CSS Class to be applied to the table header
	public $tdClass = null;						// CSS Class to be applied to table data rows
	public $tableId = null;						// CSS Id to be applied to the table
	public $numberOfRows = 40;				// Number of table rows to display
	public $showTopLinks = True;      // Display links at top of table
	public $showBottomLinks = True;   // Display links at bottom of table
	public $displayQueryString = False;	// Display query string or not

	/*****************
		Internal Vars
	************************/
	protected $columnTitles = array();	// An array containing titles to be used for the table
																			// header. If not present then
                               				// the table column names are used.
	protected $dbHandle;							// Database Handle returned from  a mysqlj_connect call
	protected $resultSet;             // SQL Result set returned from MySQL
	protected $query;									// SQL Query string
	protected $primaryKeyFields;			// name of each column in primary key
	protected $cursorpos = 0;         // Current row number in result set - not primary key value
	protected $links;									// Associative array key => value where key is url to jump and
																		// value is prompt for link
	protected $colLinks = array();		// Associative array key => value where key is url to jump and
																		// value is prompt for link
	protected $noOfRows = null;       // Number of rows returned by executing SQL in $query

	// Constructor
	function __construct($dbHandle,$queryArg, $tableRows = 40) {


		if (isset($dbHandle))
		{
			$this->dbHandle = $dbHandle;
		}
		else
		{
			die("No database handle passed to constructor of dumpQryToTable Class");
		}

		if (isset($queryArg))
		{
			$this->query = $queryArg;
		}
		else
		{
			die("No SQL Query string passed to constructor of dumpQryToTable Class");
		}

		// Set Number of rows returned by query
		$this->noOfRows = $this->getNoOfRows();


		// Number of rows to display on each page
		if (isset($tableRows))
		{
			$this->numberOfRows = $tableRows;
		}

	}

	function __destruct()
	{
		//print("<h4>__destruct() called</h4>\n");
	}

	/**************************
	 Public Methods
	 **************************/

	// Add a column name and title
	public function addColumn($colName, $colTitle)
	{
		$this->columnTitles[$colName] = $colTitle;
		return $this;
	}

	// Add a link
	public function addLink($url,$prompt) {
		$this->links[$url] = $prompt;
		return $this;
	}

	// Add a column link
	public function addColumnLink($colTitle, $url, $linkText) {
		$this->colLinks[] = array('coltitle' 	=> $colTitle,
															'url'				=> $url,
															'linktext'	=> $linkText);
		return $this;
	}


	public function addPrimaryKeyColumn($colName) {
		$this->primaryKeyFields[$colName] = '';
		return $this;
	}

	public function exec()
	{

		if (!isset($this->query)) {
			die("No Query String to work on");
		}

		if (isset($_GET['cursorpos'])) {
			$this->cursorpos = (int) $_GET['cursorpos'];
		}


		$temp = $this->query . " LIMIT $this->cursorpos, $this->numberOfRows";
		$this->resultSet = mysql_query($temp,$this->dbHandle) or die("Query failed: [$temp] " . mysql_error());

		// Printing results in HTML
		echo "<p>";


		// Display the query string if told to
		if ($this->displayQueryString)
		{
			print("<p><h4>$this->query</h4></p>\n");
			print("<p><h4>number of rows [$this->noOfRows]</h4></p>\n");
		}

		// display navigation links
		if ($this->showTopLinks)
		{
			$this->showLinks($this->resultSet);
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
		$this->writeTableHeaders();

		// Write a page of data
		$this->writePageData();

		echo "</table>\n";

		echo "</p>\n";

		// display navigation links
		if ($this->showBottomLinks)
		{
			$this->showLinks($this->resultSet);
		}


		// Free resultset
		mysql_free_result($this->resultSet);

		return $this;
	}

	/************************
	 Internal Methods
	 ************************/

	private function writeTableHeaders()
	{
		// Write table headers if told to do so
		if ($this->displayTitles)
		{

			// If an array of column titles has been passed then use these
			// otherwise use the field names as column headings
			if (!empty($this->columnTitles))
			{
				foreach ($this->columnTitles as $key => $value)
				{
				  echo	"\t<th " .
				  			(isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
							 	" >$value</th>\n";
				}
			}
			else
			{
				/* set up the table headers */
				foreach ($this->columnTitles as $key => $value)
				{
						echo "\t<th  " .
								 (isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
								 ">$value</th>\n";
				}
			}

			// Add any column links
			foreach($this->colLinks as $col)
			{
				echo "\t<th  " .
							(isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
							">" . $col['coltitle'] . "</th>\n";
			}

			$this->writeEditLinkHeader();
			$this->writeDeleteLinkHeader();
		}
	}

	// Add an edit link if told to do so
	private function writeEditLinkHeader()
	{
		if (isset($this->editTarget))
		{
			echo "\t\t<th " .
						(isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
						">Edit</th>\n";

		}
	}

	// Add a delete link Header if told to do so
	private function writeDeleteLinkHeader()
	{
		if (isset($this->deleteTarget))
		{
			echo "\t\t<th " .
						(isset($this->thClass) ? " class=\"$this->thClass\" " : "") .
						">Delete</th>\n";
		}
	}

	// Add an edit link if told to do so
	private function writeEditLink()
	{
		$i = 1;
		if (isset($this->editTarget))
		{
			// Add an edit link for the row if told to do so
			if (isset($this->editTarget))
			{
				$args = http_build_query(array('opcode' => UPDATE_REC) + $this->primaryKeyFields +
				array('cursorpos' => $this->cursorpos) );
	  		echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") .
	  		"><a href=\"$this->editTarget?$args\" title=\"Edit$i\">Edit</a></td>\n";
  		}

		}
	}

	// Add a delete link if told to do so
	private function writeDeleteLink()
	{
			$i = 1;
			// Add a Delete link for the row if told to do so
			if (isset($this->deleteTarget))
			{
				$args = http_build_query(array('opcode' => DELETE_REC) +
																	$this->primaryKeyFields  +
																	array('cursorpos' => $this->cursorpos) );
	  		echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") .
	  				 "><a href=\"$this->deleteTarget?$args\" title=\"Delete$i\">Delete</a></td>\n";
  		}
	}


	// Display navigation links
	private function showLinks() {

		$tmpPos;
		$alinks;

		echo "<br />";
		echo "<p>\n";

    // link to add a New record
    if (isset($this->insertTarget))
    {
    	$add = True;
			$args = http_build_query(array('opcode' => INSERT_REC));
			$alinks[] = "&nbsp;<a href=\"$this->insertTarget?$args\" title=\"new\">New</a>\n";
		}


	 	// If we aren't on the first row then provide a link to go backwards up the recordset
		if($this->cursorpos > 0)
		{
		 $tmpPos = $this->cursorpos - $this->numberOfRows;
		 if ($tmpPos < 0)
		 {
		 	$tmpPos = 0;
		 }

     	global $PHP_SELF;
			$previous = True;
     	$args = http_build_query(array('opcode' => PAGE_UP,
                                     'cursorpos' => $tmpPos
																		));
     	$alinks[] = "&nbsp;<a href=\"$PHP_SELF?$args\">Previous</a>&nbsp;\n";

		}


		// If more than one page
		if ($this->noOfRows > $this->numberOfRows)
		{
			// links for each page
			for ($i = 0; $i < $this->noOfRows; $i += $this->numberOfRows)
			{
				global $PHP_SELF;
				$next = True;
				$args = http_build_query(array('opcode' => PAGE_DOWN,
																			'cursorpos' => $i ));
				$alinks[] = "&nbsp;<a href=\"$PHP_SELF?$args\">[ " . $i . " ]</a>&nbsp;\n";
			}
    }


		// link for next page
    // if ($this->noOfRows > ($_GET['cursorpos'] + mysql_num_rows($this->resultSet)) )
    if ($this->noOfRows > ($this->cursorpos + mysql_num_rows($this->resultSet)) )
    {
    	global $PHP_SELF;
    	$next = True;
    	$args = http_build_query(array('opcode' => PAGE_DOWN,
                                   	 'cursorpos' => $this->cursorpos + $this->numberOfRows   ));
    	$alinks[] = "&nbsp;<a href=\"$PHP_SELF?$args\">Next</a>&nbsp;\n";
    }

		// other links
		if (isset($this->links))
		{
			foreach ($this->links as $key => $value) {
				$link = True;
				$alinks[] = "&nbsp;<a href=\"$key?\">$value</a>\n";
			}
		}


		// Write out assembled links
		for ($i = 0; $i < count($alinks); $i++) {
			echo $alinks[$i];
			if ($i < (count($alinks) - 1))
			{
	    	echo "&middot;";
	    }
     }

		echo "</p>\n";
		echo "<br />";
	}


	// Write page of row data
	private function writePageData()
	{

		while ($row = mysql_fetch_assoc($this->resultSet))
		{
			// Write individual row of data
			echo "\t<tr>\n";

			// Adjust the cursor position
	   	if (!isset($_GET['cursorpos']) || (int)$_GET['cursorpos'] < 0)
			{
	     	$this->cursorpos = 0;
	   	}
	   	else
			{
	      $this->cursorpos = (int)$_GET['cursorpos'];
	   	}

			// one column for each column name in $this->columnTitles
			foreach ($this->columnTitles as $key => $value) {
				echo "\t\t<td " . (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") . ">" .
             stripslashes($row[$key]) . "</td>\n";
			}
			// Stash the primary key value for use when building the edit and delete links
			foreach  ($this->primaryKeyFields as $key => $value) {
				$this->primaryKeyFields[$key] = $row[$key];
			}


			// Add any column links
			$i = 1;
			foreach($this->colLinks as $col) {
				$args = http_build_query($this->primaryKeyFields + array('cursorpos' => $this->cursorpos) );

				$tmpStr = "\t\t<td " .
				          (isset($this->tdClass) ? " class=\"$this->tdClass\" ": "") .
	  				      ">" .
									"<a href=\"" . $col['url'] . "&" . $args . "\"" .
									   "title=\"" . $col['url'] . $i++ . "\">" .
										 $col['linktext'] .
									"</a>" .
									"</td>\n";

				echo $tmpStr;
			}


			$this->writeEditLink();
			$this->writeDeleteLink();

			echo "\t</tr>\n";
		}
	}

	/******************************
	TODO: Trying to avoid having to brute force select to get the number of rows.
				The object is destroyed at the end of the script, but stashing the row
				count in the SESSION doesn't work 'cos row insertions/deletions won't
				be recognised neither will the fact that a particular browse object is
				REALLY finished with.
				Need a # key to identify unique instance of browse object. Still doesn't
				solve insertions/deletions not being recognised
	**************************************/
	private function getNoOfRows()
	{
		//print("<h4> getNoOfRows() Entered</h4>\n");
		//if (is_Null($this->noOfRows))
		//if (! isset($_SESSION['dumpquerytotable.noOfRows']) )
		// TODO: Brute force the row count to be refreshed every time - YUK!!!!!
		if (TRUE)
		{
			$this->resultSet = mysql_query($this->query,
			                               $this->dbHandle)
			                   or die("Query failed: [$temp] " . mysql_error());
			$this->noOfRows = mysql_num_rows($this->resultSet);
			//$_SESSION['dumpquerytotable.noOfRows'] = $this->noOfRows;
			//print("<h4> getNoOfRows() SELECT executed</h4>\n");
		}
		else
		{
			// $this->noOfRows = $_SESSION['dumpquerytotable.noOfRows'];
			die("dumpquerytotable.php line 454 - This is broken and should never execute");
		}

		return $this->noOfRows;
	}

}


?>
