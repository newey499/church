<?php
/******************************

class.google.url.short.php

Uses the Google URL Shortener API to provide shortened URLS.
Useful for putting URLs in Tweets

Provided by Google
API Key : AIzaSyCEXwW9LnyXN0D2GJbRmsW-X7FbL8oTF3c


*********************************/

Class GoogleUrlShort
{
	private $googleUrl = "https://www.googleapis.com/urlshortener/v1/url";
	private $apiKey = "AIzaSyCEXwW9LnyXN0D2GJbRmsW-X7FbL8oTF3c";

	function __construct() {
		// do nothing
	}

	function __destruct() {
		// do nothing
	}

	public function shortenUrl($longUrl) {

		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return result as string
		curl_setopt($ch, CURLOPT_URL, $this->googleUrl . '?key=' . $this->apiKey);
		curl_setopt($ch, CURLOPT_HEADER, 0);  // Suppress returned headers - just get the message
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		$data = json_encode(array("longUrl"=>$longUrl));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);
		$result = json_decode($result,true);

		curl_close($ch);

		// Return the result
		return isset($result['id']) ? $result['id'] : false;
	}

	public function expandUrl($shortUrl) {

		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return result as string
		curl_setopt($ch, CURLOPT_URL,$this->googleUrl . '?shortUrl='.$shortUrl . '&key=' . $this->apiKey);
		curl_setopt($ch, CURLOPT_HEADER, 0); // Suppress returned headers - just get the message

		$result = curl_exec($ch);

		$result = json_decode($result,true);
		curl_close($ch);

		// Return the result
		return isset($result['longUrl']) ? $result['longUrl'] : false;
	}


}	// End Class GoogleUrlShort

?>