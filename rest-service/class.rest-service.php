<?php
/*****************
class.rest-service.php

Date 				Programmer		Description
10/03/2012	CDN					Map "&pound;" and "£" to UTF code U+00A3
21/03/2012  CDN         Up version number to 0.3 to reflect added support for sermons and talks
************************/

require_once('class.rest.exception.php');

class RestService
{
  const REST_VERSION_NUMBER = "0.4";


  public $url;
  public $urlParts;
  public $urlSplit;
	public $path;
  public $pathElements;
	public $method;
	public $getArgs;
	public $postArgs;
	public $globalGetArgs;
	public $globalPostArgs;
	public $putArgs;
	public $deleteArgs;
  public $serverName;

	public $jsonOutputFlag;
	public $jsonOutputString;
	public $xmlOutputString;
	public $phpDataArray;

	function __construct()
	{
		$this->url          =	strtoupper('http://'.
													 $_SERVER['SERVER_NAME'] .
													 $_SERVER['REQUEST_URI']);

		// Check to see if JSON output has been requested - if not send XML
		if ( strpos(strtoupper($_SERVER['REQUEST_URI']),'JSON') === false)
		{
			$this->jsonOutput = false;
		}
		else
		{
			$this->jsonOutput = true;
		}

		// var to contain xml output
		$this->xmlOutputString = "";
		// var to contain JSON output
		$this->xmlOutputString = "";
		// PHP array to contain output
		$this->phpDataArray = array();


		// When debugging using despatcher.php the file name despatcher.php appears in
		// the URI - so it has to be removed.
		// In normal operation Apache is set up to redirect events/all/2011/3/10 to despatcher.php
		// at the Apache redirect level. despatcher.php is NEVER called directly in normal operation.
		// Also when debugging is in operation with xdebug search for &XDEBUG_SESSION_START and delete
		// the "&XDEBUG_SESSION_START" and anything else that follows it
		$this->url = str_replace('DESPATCHER.PHP?', '', $this->url);
		$pos = $pos = strpos($this->url, '&XDEBUG_SESSION_START');
		// Note our use of ===.  Simply == would not work as expected
		if (! ($pos === false))
		{
			$this->url = substr($this->url, 0, $pos);
		}
		$this->urlParts     = parse_url($this->url);
		$this->path					= $this->urlParts['path'];
    //$this->pathElements = split('/', substr($this->urlParts['path'], 1));
    $this->pathElements = split('/', substr($this->path, 1));
		$this->method       = strtoupper($_SERVER['REQUEST_METHOD']);
		parse_str(file_get_contents('php://input'), $this->getArgs);
		$this->globalGetArgs      = $_GET;
		parse_str(file_get_contents('php://input'), $this->postArgs);
		$this->globalPostArgs     = $_POST;


		// PHP Puts Arguments for PUT in the $_GET variable
		if ($this->method == "PUT")
		{
			$this->putArgs = $this->getArgs;
			// Row Id specified as part of the URI takes priority
			if (isset($this->pathElements[3]))
			{
				$this->putArgs['id'] = $this->pathElements[3];
			}
		}
		//parse_str(file_get_contents('php://input'), $this->putArgs);
		//$this->putArgs      = $this->putArgs;

		// DELETE operations expect the record id as the last part of the URI
		// Eg . http://rest-church/events/oneoff/delete/400
		if ($this->method == "DELETE")
		{
			if (isset($this->pathElements[3]))
			{
				$this->deleteArgs['id'] = $this->pathElements[3];
			}
			else
			{
				$this->deleteArgs['id'] = "";
			}
		}



    $this->serverName   = strtoupper($_SERVER['SERVER_NAME']);
	}

	function __destruct()
	{

	}

  public function displayArgs($printHTML = false)
  {

		var_dump($request);

  }


	public function outputHTML($tag, $console = "\n", $printHTML = true)
	{
		if ($printHTML)
		{
			//print($tag);
			$this->xmlOutputString .= $tag;
		}
		else
		{
			print($tag . $console);
		}

	}

	public function writeHeaders()
	{
		if ($this->jsonOutput)
		{
			// JSON content
			// application/json is correct but a lot of things screw up if this is specified
			header('Content-type: application/json');
			//header('Content-type: text/json');
			//header('Content-type: text/plain');
			// header('Content-type: text/html');
		}
		else
		{
			// XML content
			header("Content-type: text/xml");
		}
	}

   public function writeXmlHeader()
  {
		if (! $this->jsonOutput)
		{
			// XML content
			$this->outputHTML("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
		}

    $this->writeElementStart("churchrestservice");
    $this->writeElement("restversion", RestService::REST_VERSION_NUMBER);
  }

  public function writeXmlFooter()
  {
    $this->writeElementEnd("churchrestservice");
  }

  public function writeDateElement($elementName, $elementContent)
  {
    $this->writeElementStart($elementName);
    $this->writeElement('year', substr($elementContent,0,4));
    $this->writeElement('month', substr($elementContent,5,2));
    $this->writeElement('day', substr($elementContent,8,2));
    $this->writeElementEnd($elementName);
  }

  public function writeTimeElement($elementName, $elementContent)
  {
    $this->writeElementStart($elementName);
    $this->writeElement('hour', substr($elementContent,0,2));
    $this->writeElement('minute', substr($elementContent,3,2));
    $this->writeElementEnd($elementName);
  }


  public function writeElement($elementName, $elementContent)
  {
    $this->writeElementStart($elementName);
		$elementContent = $this->mapPoundToUtf($elementContent);
		$elementContent = strip_html_tags($elementContent);
		$elementContent = stripslashes($elementContent);
		$elementContent = trim($elementContent);
    $this->outputHTML($elementContent);
    $this->writeElementEnd($elementName);
  }

	// 10/03/2012	CDN	Map "&pound;" and "£" to UTF code U+00A3
	protected function mapPoundToUtf($elementContent)
	{
		//$poundAsUtf = "U+00A3";
		$poundAsUtf = "__POUND_SYMBOL__";
		$elementContentUpper = strToUpper($elementContent);

		$offset = strpos($elementContentUpper, "&POUND;");
		if (! $offset  === FALSE)
		{
			$elementContent = str_ireplace("&POUND;", $poundAsUtf, $elementContent);
		}

		$offset = strpos($elementContentUpper, "£");
		if (! $offset  === FALSE)
		{
			$elementContent = str_ireplace("£", $poundAsUtf, $elementContent);
		}

		return $elementContent;
	}

  public function writeElementStart($elementName)
  {
    $this->outputHTML("<" . $elementName . ">");
  }

  public function writeElementEnd($elementName)
  {
    $this->outputHTML("</" . $elementName . ">");
  }




	public function printOutputText()
	{

		// Document headers
		$this->writeHeaders();


		if ($this->jsonOutput)
		{
			libxml_use_internal_errors(true);
			$obj = simplexml_load_string($this->xmlOutputString);

			if (!$obj)
			{
		    echo "Failed loading XML\n";
				foreach(libxml_get_errors() as $error)
				{
	        echo "\t", $error->message;
			  }
			}

			//$obj = (array) $obj;
			$obj = simplexml2array($obj);

			//var_dump($obj);
			print(json_encode($obj));
		}
		else
		{
			print($this->xmlOutputString);
		}

	}

}


function simplexml2array($xml) {
   if (is_object($xml) && get_class($xml) == 'SimpleXMLElement')
	 {
       $attributes = $xml->attributes();
       foreach($attributes as $k=>$v)
			 {
           if ($v) $a[$k] = (string) $v;
       }
       $x = $xml;
       $xml = get_object_vars($xml);
   }
   if (is_array($xml))
	 {
       if (count($xml) == 0) return (string) $x; // for CDATA
       foreach($xml as $key=>$value)
			 {
           $r[$key] = simplexml2array($value);
       }
       if (isset($a)) $r['@'] = $a;    // Attributes
       return $r;
   }
   return (string) $xml;
}

?>
