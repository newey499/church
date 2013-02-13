<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function curlSend($url, $fields)
{
  //open connection
  $ch = curl_init();

  //return the transfer as a string
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL,$url);

  $jsonStr = $this->encodeForPost($fields);

  curl_setopt($ch,CURLOPT_POST,count($jsonStr));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $jsonStr);

  //execute post
  $result = curl_exec($ch);

  //$decoded = json_decode($result);
  $decoded = $this->decodeFromPost($result);

  //close connection
  curl_close($ch);

  return $decoded;

}


?>
