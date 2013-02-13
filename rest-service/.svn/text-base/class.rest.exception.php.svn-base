<?php
/**
 * Define a custom exception class for the rest service
 */
class RestException extends Exception
{
		public $restMessage = "";
		public $restCode = 0;

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // make sure everything is assigned properly
      parent::__construct($message, $code, $previous);

			$this->restCode = $code;
			$this->restMessage = $message;
    }

    // custom string representation of object
    public function __toString()
    {
      return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function errorMsg()
    {
	    return "[{$this->restCode}] {$this->restMessage}\n";
		}
}

?>
