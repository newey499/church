<?php
/***********************************************

class.socialmedia.php

Helper class for buildform.php to provide checkboxes for social media
that define whether updates are to be sent to Social media sites.


Modification History
====================

Date				Programmer								Description
04/05/2012		CDN					Created
05/05/2012		CDN					Add helper class SocialMediaArgs for use by exec method

**************************************************/

require_once("genlib.php");
require_once('class.google.url.short.php');
require_once('class.twitter.php');
require_once('class.dlvr.it.php');

class SocialMedia
{
	const SEND_YES = "yes";
	const SEND_NO = "no";


	const CHKBOX_TWITTER = 'sendtotwitter';
	const CHKBOX_FACEBOOK = 'sendtofacebook';
	const CHKBOX_GOOGLEPLUS = 'sendtogoogleplus';

	public $oExecArgs = null;

	protected $sendToTwitter 	= false;
	protected $sendToFacebook 	= false;
	protected $sendToGooglePlus = false;
	protected $menuPrompt 		= "";
	protected $targetUrl 		= "";
	protected $shortUrl 		= "";

	function __construct()
	{
		// First do nothing
		$this->sendToTwitter    = false;
		$this->sendToFacebook   = false;
		$this->sendToGooglePlus = false;
		$this->menuPrompt 		= "";
		$this->targetUrl 		= "";
		$this->shortUrl 		= "";

		$oExecArgs = new SocialMediaArgs();

		// pseudo support for multiple constructors
        $argv = func_get_args();
        switch( func_num_args() )
        {
            case 0:
				// nothing to do
                break;

            case 3:
                self::__construct3( $argv[0], $argv[1], $argv[2] );
                break;

            default:
				die("Invalid args sent to constructor of SocialMedia class");
				break;
         }

	}

	// Used to set up Class with initial values
	protected function __construct3($sendToTwitter = false, $sendToFacebook = false, $sendToGooglePlus = false)
	{
		$this->sendToTwitter    = $sendToTwitter;
		$this->sendToFacebook   = $sendToFacebook;
		$this->sendToGooglePlus = $sendToGooglePlus;
	}

	function __destruct()
	{
		// does nothing
	}

	public function setTwitter($value)
	{
		$this->sendToTwitter = $value;
	}

	public function setFacebook($value)
	{
		$this->sendToFacebook = $value;
	}

	public function setGooglePlus($value)
	{
		$this->sendToGooglePlus = $value;
	}


	// Build HTML for checkboxes
	public function buildHTML()
	{
		$result = "";

		if ($this->sendToTwitter || $this->sendToFacebook || $this->sendToGooglePlus)
		{
			$result .= '<div style="margin-left:5em;" >';
		}

		if ($this->sendToTwitter)
		{
			$result .= "<b>Send to Twitter</b>";
			$result .= '<input type="checkbox" name="' . SocialMedia::CHKBOX_TWITTER .
					    '" value="' . SocialMedia::SEND_YES . '" >';
			$result .= "&nbsp;&nbsp;";
			//$result .= "<br />";
		}

		if ($this->sendToFacebook)
		{
			$result .= "<b>Send to Facebook</b>";
			$result .= '<input type="checkbox" name="' . SocialMedia::CHKBOX_FACEBOOK .
					   '" value="' . SocialMedia::SEND_YES . '" >';
			$result .= "&nbsp;&nbsp;";
			//$result .= "<br />";
		}

		if ($this->sendToGooglePlus)
		{
			$result .= "<b>Send to GooglePlus</b>";
			$result .= '<input type="checkbox" name="' . SocialMedia::CHKBOX_GOOGLEPLUS .
					   '" value="' . SocialMedia::SEND_YES . '" >';
			$result .= "&nbsp;&nbsp;";
			//$result .= "<br />";
		}

		if (! empty($result))
		{
			$result .= "<br />";
			$result .= "<br />";
		}

		if ($this->sendToTwitter || $this->sendToFacebook || $this->sendToGooglePlus)
		{
			$result .= "</div>";
		}


		return $result;
	}

	// Get the version of the menu prompt from the database so we get the correct upper and lower case
	// Build a URL to point to the website page
	// Get a shortened version of the URL using the Google service
	public function setMenuPrompt($menuPrompt)
	{
		if (! empty($menuPrompt))
		{
			$qrySel = " SELECT * FROM menus " .
				" WHERE UCASE(prompt) = UCASE('" . $menuPrompt . "') " .
				" AND  isvisible = 'YES'" ;

			$row = array();
			$this->menuPrompt 	= "";
			$this->targetUrl 	= "";
			$this->shortUrl		= "";

			if ($cursor = mysql_query($qrySel) )
			{
				if ($row = mysql_fetch_array($cursor))
				{
					$this->menuPrompt = $row['prompt'];
					$menuPrompt 	  = $row['prompt'];
					$this->targetUrl = "http://" . WEBSITE_DOMAIN . "/" . buildTargetUrl($row);
					$oShortUrl = new GoogleUrlShort();
					$this->shortUrl = $oShortUrl->shortenUrl($this->targetUrl);
				}
				mysql_free_result($cursor);
			}

		}

		return $row;
	}

	public function getMenuPrompt()
	{
		return $this->menuPrompt;
	}

	public function getTargetUrl()
	{
		return $this->targetUrl;
	}

	public function getShortUrl()
	{
		return $this->shortUrl;
	}

	public function exec($msg)
	{
		$saveSendToFacebook = $this->sendToFacebook;

		//print("<h2>class.socialmedia.php exec method</h2>");

		if ($this->sendToTwitter)
		{
			// =====================================================================
			// If we are sending to Twitter then disable any send to Facebook
			// because the website http://dlvr.it is going to forward the Tweet to
			// Facebook. If the Facebook send is not disabled the facebook account
			// will end up with a duplicate post - one from here and one from
			// http://dlvr.it
			// =====================================================================
			$this->sendToFacebook = false;

			$sent = "";
			$oTwitter = new Twitter();

			if (empty($this->menuPrompt))
			{
				$this->targetUrl = "http://" . WEBSITE_DOMAIN;
				$this->oShortUrl = new GoogleUrlShort();
				$this->shortUrl = $oShortUrl->shortenUrl($targetUrl);
			}
			$sent = ( isset($msg)? $msg . " " : "" );

			if (empty($sent))
			{
				print("<h4>Not sent to Twitter or anywhere else Tweet does not contain any text</h4>");
			}
			else
			{
				$oTwitter->sendTweet($sent);

				print("<br />message text [" . $sent . "]<br />");
				print("<h4>Sent to Twitter which will then automatically forward to Facebook</h4>");
			}


		}
		else
		{
			print("<h4>NOT Sent to Twitter</h4>");
			//print_r($this);
		}


		if ($this->sendToFacebook)
		{
			$oDelvr = new DeliverIt();
			$oDelvr->postToRoute(DeliverIt::DLVR_IT_FACEBOOK_ROUTE, $msg);
			print("<h4>Sent to Facebook via http://dlvr.it</h4>");
		}
		else
		{
			if ($this->sendToTwitter)
			{
				print("<h4>Not sent to Facebook via  http://dlvr.it. But the message will " .
					  "be forwarded to Facebook from Twitter.</h4>");
			}
		}

		// restore the saved state of the facebook send flag so that the
		// calling code doesn't know it may have been temporarily disabled.
		// See the notes above.
		$this->sendToFacebook = $saveSendToFacebook;


		if ($this->sendToGooglePlus)
		{
			print("<h4>Sent to Google Plus  - Not supported yet - NO Google+ write API exists</h4>");
		}
		else
		{
			print("<h4>NOT Sent to Google Plus</h4>");
		}

	}

} // end class Social Media


// in c++ this would be a friend class
class SocialMediaArgs
{

	function __construct()
	{
		// does nothing
	}


	function __destruct()
	{
		// does nothing
	}

	public function getShortUrl($longUrl)
	{
		$oShortenUrl = new GoogleUrlShort();

		return $oShortenUrl->shortenUrl($longUrl);
	}

} //end class SocialMediaArgs


?>