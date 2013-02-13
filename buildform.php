<?php
/*********************************************

Modification History
====================

Date				Programmer								Description
16/01/05		CDN 					Detect if column type is enumerated. If so get enumerated values and
													populate a combo box instead of using a text box
14/03/05    CDN						Use stripshlashes() on text and text area values
08/09/2011	CDN           Add ability to change default row and column settings for text area
08/09/2011	CDN           Add ability to change row and column settings for text area for a specific field
08/09/2011	CDN						Add Helper Class to store textarea row and column settings specific to a column
14/04/2012	CDN						Add ability to make an input field readonly
04/05/2012  CDN			Add check boxes for social media via class.socialmedia.php helper class
15/06/2012	CDN			readonly fields now marked as readonly and disabled to show input field greyed out
**********************************************************/



require_once("mysqlj.php");
require_once("mysql.php");
require_once("genlib.php");
require_once("class.socialmedia.php");


// Build an HTML form
class buildForm {

	public $oSocialMedia = null;

	var $formname = 'frm_Default';	// Name of form
	var $dbHandle;							// Handle to database connection
	var $opcode;								// Operation - Insert, Update
	var $fields = array();							 	// Associative array where key = "fieldName" =>
															// value = "Prompt for associated field"
	var $defaults; 							// Associative array where key = "fieldName" =>
															// value = "Default value for fieldname in new record"
	var $messages;						 	// Associative array where key = "fieldName" =>  // value = "Message for associated field"
	var $errorMessages;				 	// Associative array where key = "fieldName" =>  // value = "Message for associated field"
	var $links;									// Associative array key => value where key is url to jump and
															// value is prompt for link
	var $passwdFields;					// Array containing the names of fields that are to be treated
															// as password inputs - text masked with '*'
	var $query;									// SQL Query string

	var $pkey;                  // Primary Key Associative array where key = "fieldName" =>  value = "Prompt for associated field"
	var $submitTarget;					// Name of page to which form is to be submitted
	var $borderSize = 1;				// table border size
	var $displayQuery = False;	// Display query on page
	var $result;                // mysql query result set object

	private $columnTextArea = array();	// contains array of textarea column row settings for a specific column name
	// CDN 22/01/2009 Change Defaults
	//var $textarearows = 10;			// default number of rows for textarea
	//var $textareacols = 50;			// default number of columns for textarea
	var $textarearows = 20;				// default number of rows for textarea
	var $textareacols = 80;				// default number of columns for textarea
	var $maxtextcols  = 50;     	// max length of text box in chars - used if length of field exceeds this figure otherwise
	                              // the length of the field is used

	private $readonlyCols = array();	// Array of readonly column names
	private $hiddenCols = array();		// Array of hidden column names

	private $useSocialNetworking = false;	// Flag to decide if social networking prompts are to be displayed



	var $formClass;							// CSS Class for form
	var $tableClass;							// CSS Class for table
	var $promptClass;						// CSS Class for prompt column
	var $dataClass;							// CSS Class for data column

	var $refreshFromPost = False;  // Overwrite table values with $_POST values

	// constructor
	function buildForm ($opcode,$qry,$pkey,$flds = array() ) {

		if (isset($opcode)) {
			$this->opcode = strToUpper($opcode);
		}
		else {
			die("No opcode passed to constructor");
		}
		if (isset($qry)) {
			$this->query = $qry;
		}
		else {
			die("No SQL Query string passed to constructor");
		}
		if (isset($flds)) {
			$this->fields = $flds;
		}
		else {
			//die("No Fields array passed to constructor");
		}
		if (isset($pkey)) {
			$this->pkey = $pkey;
		}
		else {
			die("No Primary Key array passed to constructor");
		}

		$this->oSocialMedia = new SocialMedia();
		$this->oSocialMedia->setTwitter(true);
		$this->oSocialMedia->setFacebook(true);
		$this->oSocialMedia->setGooglePlus(true);

	}


	// Flag a field as a password field
	function flagPasswordField($fieldName) {
		$this->passwdFields[] = $fieldName;
	}


	// Add a link
	function addLink($url,$prompt) {
		$this->links[$url] = $prompt;
		return $this;
	}

	function addField($colName,$colTitle,$default=NULL,$message=NULL) {
		$this->fields[$colName] = $colTitle;
		if (isset($default)) {
			$this->defaults[$colName] = $default;
		}
		if (isset($message)) {
			$this->messages[$colName] = $message;
		}
	}


	function addHiddenField($colName,$colTitle,$default=NULL,$message=NULL) {

		$this->addField($colName,$colTitle,$default,$message);
		$this->setIsHidden($colName, true);

	}


	function addReadOnlyField($colName,$colTitle,$default=NULL,$message=NULL) {

		$this->addField($colName,$colTitle,$default,$message);
		$this->setReadOnly($colName, true);

	}


	function addMessageCol($colName, $message) {
		$this->messages = $this->messages + array($colName => $message);
	}

	function addErrorMessageCol($colName, $message) {
		$this->errorMessages[$colName] = "<SPAN id=\"error\">$message</SPAN>";
	}

	function delErrorMessageCol($colName) {
		unset($this->errorMessages[$colName]);
	}

	// pseudonym for addMessageCol
	function setValidationMessage($col,$mesg) {
		$this->addMessageCol($col, $mesg);
	}

	function exec() {


		if ($this->displayQuery) {
			print("<h4>$this->query</h4>\n");
		}

		if (empty($this->query)) {
			die("No Query String to work on");
		}

		// Get a database connection if we haven't been given a handle to one
		if (!isset($this->dbHandle)) {
			$this->dbHandle = mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD)
			   or die('Could not connect: ' . mysql_error());

			mysql_select_db(MYSQL_DATABASE)  or die('Could not select database');
		}

		$row = mysql_query($this->query);
		if (!$row) {
			die("Query failed: [$this->query] " . mysql_error());
		}

		$cols = mysql_fetch_assoc($row);

		/***********
			Update the contents of the column values in memory with the
			matching values in $_POST array elements of the same name
		****************************/
		if ($this->refreshFromPost) {
			foreach ($cols as $key => $value) {
				if (isset($_POST[$key])) {
					$cols[$key] = $_POST[$key];
				}
			}
		}


		// other links
		if (isset($this->links)) {
			echo "<p>\n";
			foreach ($this->links as $key => $value) {
				echo "<a href=\"$key\">$value</a>\n";
			}
			echo "</p>\n";
		}




		// Start form definition - include CSS class if present
		// CDN 13/4/12 = Provide Enclosure type if file upload has been requested
		if (! empty($this->formClass)) {
			echo "<form name=\"" . $this->formname . "\" class=\"" . $this->formClass . "\" method=\"post\" " .
					 " action=\"$this->submitTarget\" >\n";
		}
		else {
			echo "<form name=\"" . $this->formname . "\" method=\"post\" " .
					 " action=\"$this->submitTarget\" >\n";

		}

		// Indicate Add/Amend/Delete operation
		echo "<input type=\"hidden\" name=\"opcode\" value=\"$this->opcode\">\n";


		// Set up hidden fields for all primary key fields
		if (isset($this->pkey)) {
			foreach($this->pkey as $key => $value) {
				$fldNo = mysql_field_no($row,$key);
				$meta = mysql_fetch_field($row,$fldNo);
				print("<input type=\"hidden\" name=\"" . $key . "\" value=\"" . stripslashes($cols[$key]) . "\">\n");
			}
		}


		/*************
		Place whether social networking is being used in POST array as hidden field
		******************************************************************************/
		print('<input type="hidden" name="issocialnetworking" ' .
			  'value="' . ($this->isSocialNetworking() ? "YES" : "NO") . '" \>');
		if ($this->isSocialNetworking())
		{
			// Build social media html but only if we are told to use social networking
			print($this->oSocialMedia->buildHTML());
		}

		if (isset($this->tableClass)) {
			print("<table class=" . $this->tableClass . " border=\"$this->borderSize>\"\n");
		}
		else {
			print("<table border=\"$this->borderSize>\"\n");
		}



		// Set up input fields for all visible columns
		foreach ($this->fields as $key => $prompt) {

			$fldNo = mysql_field_no($row,$key);
			$meta = mysql_fetch_field($row,$fldNo);

			$default = $meta->def;
			$ftype = $meta->type;
			$fsize = $meta->max_length;

			$finput = '';

			if ($this->getIsHidden($key))
			{

				$hiddenHtml = $this->getIsHiddenHtml($key);
				if ($this->opcode == INSERT_REC)
				{
					$finput = '<input type="' .  $hiddenHtml . '" ' .
								" name=\"$key\" " .
								" value=\"" . $this->getDefaultValue($key,"") . "\">";
				}
				else
				{
					$finput = '<input type="' .  $hiddenHtml . '" ' .
								" name=\"$key\" " .
								"value=\"" . stripslashes($cols[$key]) . "\">";
				}
				print($finput);
			}
			else
			{
				switch ($ftype) {

					case 'year':
					//case MYSQL_TYPE_YEAR:
						if ($this->opcode == INSERT_REC) {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"$fsize\" " .
									" value=\"" . $this->getDefaultValue($key,"YYYY") . "\">";
						}
						else {
							$finput = "<input type=\"text\" " .
												" name=\"$key\" " .
												" size=\"$fsize\" " .
												" maxlength=\"$fsize\" " .
												" value=\"" . stripslashes($cols[$key]) . "\">";
						}
					break;

					case 'string':

						// Handle enumerated
						$flagArray = explode(' ', mysql_field_flags($row, $fldNo));
						if ( ! (array_search('enum', $flagArray) === false))
						{
							$oMySqlj = new mysqlj();               // Object with utility MySql objects
							// If we have been given a specific default value then use that
							// otherwise use the default held on the database
							// Note $default passed by reference to enable default value to be made available
							// So this line gets the enum entries and the default on the database
							$enumValues = $oMySqlj->getEnumValues($meta->table,$key,$default);

							$finput = "<select name=\"" . $key . "\">\n";
							foreach($enumValues as $enumkey => $enumvalue)
							{
								$finput = $finput . "<option value=\"" . $enumvalue . "\"";

								if ($this->opcode == INSERT_REC)
								{
									// Use the default value
									if ($enumvalue == $default)
									{
										$finput = $finput . " selected ";
									}
								}
								else
								{
									// Use the current value
									if ($enumvalue == $cols[$key])
									{
										$finput = $finput . " selected ";
									}
								}

								$finput = $finput . ">" . $enumvalue . "</option>\n";

								}
								$finput = $finput . "</select>\n";

							}
						else
						{
							// Handle String
							$type = $this->isPasswordField($key)? 'password' : 'text';
							$fsize = 50;
							$fMaxSize = 150;	// CDN 29/6/7 Really should get the size of each field dynamically
																//            from the data dictionary
							if ($this->opcode == INSERT_REC)
							{
								$finput = '<input type="' . $type .
											'" ' .
											" name=\"$key\" " .
											$this->getReadOnlyHtml($key) .
											" size=\"$fsize\" " .
											" maxlength=\"$fMaxSize\" " .
											" value=\"" . $this->getDefaultValue($key,'') . "\">";
							}
							else
							{

								$finput = '<input type="' .
										  $type .
											'" ' .
											" name=\"$key\" " .
											$this->getReadOnlyHtml($key) .
											$this->getIsHiddenHtml($key) .
											" size=\"$fsize\" " .
											" maxlength=\"$fMaxSize\" " .
											"value=\"" . stripslashes($cols[$key]) . "\">";
							}
						}
					break;

					case 'int':
					//case MYSQL_TYPE_TINY:				// type of integer
					//case MYSQL_TYPE_SHORT:			// type of integer
					//case MYSQL_TYPE_LONG:       // type of integer
					//case MYSQL_TYPE_LONGLONG:   // type of integer
					//case MYSQL_TYPE_INT24:      // type of integer
						$fsize = 10;
						if ($this->opcode == INSERT_REC) {
							$finput = " <input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"$fsize\" " .
									" value=\"" . $this->getDefaultValue($key,'0') . "\">";
						}
						else {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"$fsize\" " .
									" value=\"{$cols[$key]}\">";
						}
					break;

					case 'float':
					//case MYSQL_TYPE_FLOAT:			// type of floating point
					//case MYSQL_TYPE_DOUBLE:			// type of floating point
					//case MYSQL_TYPE_DECIMAL:    // Number defined as <number of digits,number of decimal places
						$fsize = 12;
						if ($this->opcode == INSERT_REC) {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"$fsize\" " .
									" value=\"" . $this->getDefaultValue($key,'0.0') . "\">";
						}
						else {
							$finput = "<input type=\"text\" name=\"$key\" size=\"$fsize\" maxlength=\"$fsize\" value=\"{$cols[$key]}\">";
						}
					break;

					case 'blob':
					//case MYSQL_TYPE_TINY_BLOB:
					//case MYSQL_TYPE_MEDIUM_BLOB:
					//case MYSQL_TYPE_LONG_BLOB:
					//case MYSQL_TYPE_BLOB:
					//case MYSQL_TYPE_TEXT:

						// CDN 08/11/2011 allow text area to be set for individual column
						// $finput = "<textarea rows =\"$this->textarearows\" cols=\"$this->textareacols\" name=\"$key\">";
						$finput = "<textarea " . $this->getTextAreaSetting($key) . " name=\"$key\">";

						if ($this->opcode == INSERT_REC) {
							$finput = $finput . $this->getDefaultValue($key,'');
						}
						else {
							$finput = $finput . stripslashes($cols[$key]);
						}
						$finput = $finput . "</textarea>\n";
					break;

					case 'date':
						// MYSQL_TYPE_DATE:
						if ($this->opcode == INSERT_REC) {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"10\" " .
									" placeholder = \"DD/MM/YYYY\" " .
									"  >";
						}
						else {
							$finput = "<input type=\"text\" name=\"$key\" size=\"$fsize\" maxlength=\"10\" value=\"" . yyyymmddToddmmyyyy($cols[$key]) . "\">";
						}
					break;

					case 'time':
						//MYSQL_TYPE_TIME:
						if ($this->opcode == INSERT_REC) {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"5\" " .
									" placeholder=\"HH:MM\" >";
						}
						else {
							$finput = "<input type=\"text\" name=\"$key\" size=\"$fsize\" maxlength=\"5\" value=\"" .
						substr($cols[$key],0,5) . "\">";
						}
					break;

					case 'datetime':
					case 'timestamp':
					//case MYSQL_TYPE_DATETIME:
						if ($this->opcode == INSERT_REC) {
							$finput = "<input type=\"text\" " .
									" name=\"$key\" " .
									" size=\"$fsize\" " .
									" maxlength=\"16\" " .
									" placeholder=\"DD/MM/YYYY HH:MM\" >";
						}
						else {
							$finput = "<input type=\"text\" name=\"$key\" size=\"$fsize\" maxlength=\"16\" value=\"" . $cols[$key] . "\">";
						}
					break;

					default:
						die("buildForm(): Error do not know how to deal with field type [$ftype] field name  = [$key]");
				}
			}
			$fldNo = mysql_field_no($row,$key);
			/****
			if its a hidden field its already been put in the form as a hidden field
			*******************/
			if (! $this->getIsHidden($key))
			{
				echo "\t<tr>\n";
				echo "\t\t<td>$prompt</td>\n";
				echo "\t\t<td>$finput</td>\n";
				echo "\t\t<td>" .
					(isset($this->messages[$key]) ? $this->messages[$key] : '') . ' ' .
					(isset($this->errorMessages[$key]) ? $this->errorMessages[$key] : '') .
					"</td>\n";

				echo "\t</tr>\n";
			}


		}	// END Field processing loop

		print("</table>\n");

		print("<br />\n");

		// update, insert, delete and reset buttons as required
		switch ($this->opcode)
		{
			case UPDATE_REC:
				echo "<BUTTON TYPE=submit NAME=update VALUE=$this->opcode>Update</BUTTON>\n";
				echo "&nbsp;\n";
				break;

			case INSERT_REC:
				echo "<BUTTON TYPE=submit NAME=insert VALUE=$this->opcode>Insert</BUTTON>\n";
				echo "&nbsp;\n";
				break;

			case DELETE_REC:
				echo "<BUTTON TYPE=submit NAME=delete VALUE=$this->opcode>Delete</BUTTON>\n";
				echo "&nbsp;\n";
				break;

			default:
				echo "<BUTTON TYPE=submit NAME=submit VALUE=$this->opcode>Submit</BUTTON>\n";
				echo "&nbsp;\n";
				break;
		}
		echo "<BUTTON TYPE=reset NAME=reset VALUE=$this->opcode>Reset</BUTTON>\n";


		// other links
		if (isset($this->links)) {
			foreach ($this->links as $key => $value) {
				echo "&nbsp;<a href=\"$key\">$value</a>\n";
			}
		}


		print("</form>\n");

		// Free resultset - Note don't free the database handle
		mysql_free_result($row);

	}

	function getDefaultValue($colName,$stdDefaultValue) {

		if (is_array($this->defaults)) {
			if (array_key_exists( $colName, $this->defaults)) {
				$retval = $this->defaults[$colName];
			}
			else {
				$retval = $stdDefaultValue;
			}
		}
		else {
			$retval = $stdDefaultValue;
		}
		return $retval;
	}


	function isPasswordField($colName) {

		if (isset($this->passwdFields)) {
			foreach($this->passwdFields as $key => $value) {
				if (strToUpper($colName) == strToUpper($value)) {
					return True;
				}
			}
		}
		return False;
	}


	private function getTextAreaSetting($colName) {

		$result = ' rows ="' . $this->textarearows . '" cols="' . $this->textareacols . '" ';


		foreach ($this->columnTextArea as $oTxtArea)
		{
			if ($oTxtArea->isColSet($colName))
			{
				$result = $oTxtArea->getHTML($colName);
				return $result;
			}
		}

		return $result;
	}


	public function setTextAreaSize($colName, $rows, $cols) {

		$oTxtArea = new TextAreaSetting($colName, $rows, $cols);
		$key = spl_object_hash($oTxtArea);

		$this->columnTextArea[] = $oTxtArea;

	}


	public function setReadOnly($colName, $readOnly=false)
	{
		$this->readonlyCols[$colName] = $readOnly;
	}

	public function getReadOnly($colName)
	{
		$result = false;
		if (isset($this->readonlyCols[$colName]))
		{
			$result = $this->readonlyCols[$colName];
		}
		return $result;
	}

	protected function getReadOnlyHtml($colName)
	{
		$result = "";
		if (isset($this->readonlyCols[$colName]))
		{
			if ($this->readonlyCols[$colName])
			{
				$result = " readonly disabled ";
			}
		}
		return $result;
	}


	public function setIsHidden($colName, $isHidden=false)
	{
		$this->hiddenCols[$colName] = $isHidden;
	}

	public function getIsHidden($colName)
	{
		$result = false;
		if (isset($this->hiddenCols[$colName]))
		{
			$result = $this->hiddenCols[$colName];
		}
		//print("<h4> getIsHidden($colName) [$result] </h4>");
		return $result;
	}

	protected function getIsHiddenHtml($colName)
	{
		$result = "";
		if (isset($this->hiddenCols[$colName]))
		{
			if ($this->hiddenCols[$colName])
			{
				$result = "hidden";
			}
		}
		//print("<h4> getIsHiddenHtml($colName) [$result] </h4>");
		return $result;
	}

	public function setSocialNetworking($value)
	{
		$this->useSocialNetworking = $value;
	}

	public function getSocialNetworking()
	{
		return $this->useSocialNetworking;
	}

	public function isSocialNetworking()
	{
		return $this->useSocialNetworking;
	}


}	// End Class Buildform
// **********************************************

/******************
Date				Programmer							Description
08/08/2011	CDN 				Helper Class to store textarea row and column settings specific to a column
*****************************/
class TextAreaSetting
{
	private $colName;
	private $textAreaRows = 10;			// default number of rows for textarea
	private $textAreaCols = 50;			// default number of columns for textarea

  function __construct($colName, $row, $col)
	{

		$this->colName = $colName;
		$this->textAreaRows = $row;
		$this->textAreaCols = $col;
	}

	public function isColSet($colName)
	{

		$result = false;

		if (strcmp($colName, $this->colName) == 0)
		{
			$result = true;
		}

		return $result;
	}

	public function getHTML($colName)
	{

		$result = "";

		if ($this->isColSet($colName))
		{
			$result = ' rows ="' . $this->textAreaRows . '" cols="' . $this->textAreaCols . '" ';
		}

		return $result;
	}

}		// End Class
// **********************************************



?>
