<?php
/***************************************************

	class.dlvr.it.php

Holds API key for dlvr.it account

Date		Programmer			Decsription
13/05/2012	CDN				Created

****************************************************/

require_once('dlvr.it-config.php');


class DeliverIt
{
	const FORMAT 		= "json";

	const API_KEY 		= DLVR_IT_API_KEY;
	const ACCOUNT_NAME 	= DLVR_IT_ACCOUNT_NAME;

	const FACEBOOK_ROUTE = DLVR_IT_FACEBOOK_ROUTE;

	function __construct()
	{
		// does nothing
	}

	function __destruct()
	{
		// does nothing
	}


	public function callCurl($url, $data) {

		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return result as string
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);  // Suppress returned headers - just get the message
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);
		$result = json_decode($result,true);

		curl_close($ch);

		// Return the result
		return $result;
	}


	public function listRoutes()
	{
		$url =  'https://api.dlvr.it/1/routes.' .  DeliverIt::FORMAT;
		$data = json_encode(array("key"=> DeliverIt::API_KEY));
		$data = array("key"=> DeliverIt::API_KEY);
		$result = $this->callCurl($url, $data);
		return $result;
	}

	public function getRouteIdByName($name)
	{
		$result = $this->listRoutes();
		foreach ($result['routes'] as $route)
		{
			if (strtoupper($name) == strtoupper($route['name']))
			{
				//print("Found [" . $route['name'] . "] id [" . $route['id'] . "]<br />");
				return $route['id'];
			}
		}

		return "";

	}


	public function listAccounts()
	{
		$url =  'https://api.dlvr.it/1/accounts.' .  DeliverIt::FORMAT;
		$data = json_encode(array("key"=> DeliverIt::API_KEY));
		$data = array("key"=> DeliverIt::API_KEY);
		$result = $this->callCurl($url, $data);
		return $result;
	}

	public function getAccountIdByName($accountName)
	{
		$result = $this->listAccounts();
		foreach ($result['accounts'] as $account)
		{
			if (strtoupper($accountName) == strtoupper($account['name']))
			{
				//print("Found [" . $account['name'] . "] id [" . $account['id'] . "]<br />");
				return $account['id'];
			}
		}

		return "";
	}

	public function postToRoute($routeName, $msg)
	{
		$routeId = $this->getRouteIdByName($routeName);
		$url =  'https://api.dlvr.it/1/postToRoute.' .  DeliverIt::FORMAT;
		$data = array("key" => DeliverIt::API_KEY,
					  "id"  => $routeId,
					  "msg" => $msg);
		$result = $this->callCurl($url, $data);
		/**************
		print("<h4>");
		print("postToRoute<br />");
		print_r($data);
		print("<br />");
		print_r($result);
		print("</h4>");
		**********************/
	}

	public function postToAccount($msg)
	{
		$accountId = $this->getAccountIdByName(DeliverIt::ACCOUNT_NAME);
		$url =  'https://api.dlvr.it/1/postToAccount.' .  DeliverIt::FORMAT;
		$data = array("key" => DeliverIt::API_KEY,
					  "id"  => $accountId,
					  "msg" => $msg);
		$result = $this->callCurl($url, $data);
		/*******************
		print("<h4>");
		print("postToAccount<br />");
		print_r($data);
		print("<br />");
		print_r($result);
		print("</h4>");
		**************************/
	}


}

?>