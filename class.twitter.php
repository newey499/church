<?php
/***************************************

class.twitter.php

Encapsulates twitter actions

Date		Programmer				Description
01/05/2012		CDN			Created

****************************************/

require_once 'Services/Twitter.php';
require_once 'HTTP/OAuth/Consumer.php';
require_once("twitter-config.php");

class Twitter
{

   function __construct() {
       // does nothing
   }

   function __destruct() {
       // does nothing
   }

	public function sendTweet($tweetMsg) {

		$reply = "reply";

		try {
			$twitter = new Services_Twitter();
			$oauth   = new HTTP_OAuth_Consumer( CONSUMER_KEY,
												CONSUMER_SECRET,
												ACCESS_TOKEN,
												ACCESS_TOKEN_SECRET);
			$twitter->setOAuth($oauth);

			//print('<p>$twitter->statuses->update($tweetMsg) (not called)</p>');
			$reply = $twitter->statuses->update($tweetMsg);

		} catch (Services_Twitter_Exception $e) {
			$reply = $e->getMessage();
		}

		return $reply;

	}



}	// End class Twitter
























?>