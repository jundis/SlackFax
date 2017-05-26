<?php

ini_set('display_errors', 1); //Display errors in case something occurs
header('Content-Type: application/json'); //Set the header to return JSON, required by Slack
require_once("slackfax-config.php");

if(empty($_REQUEST['token']) || ($_REQUEST['token'] != $slacktoken)) die("Slack token invalid."); //If Slack token is not correct, kill the connection. This allows only Slack to access the page for security purposes.
if(empty($_REQUEST['text'])) die("No text provided."); //If there is no text added, kill the connection.

$tonumber = preg_replace("/[^0-9]/", "", $_REQUEST["text"]);
$postfields = "To=%2B" . $countrycode . $tonumber . "&From=%2B" . $countrycode . $fromnumber . "&MediaUrl=" . $faxpdf;

$ch = curl_init(); //Initiate a curl session

//Create curl array to set the API url, headers, and necessary flags.
$curlOpts = array(
    CURLOPT_URL => "https://fax.twilio.com/v1/Faxes",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(),
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $postfields,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HEADER => 1,
    CURLOPT_USERPWD => $accountsid . ":" . $authtoken,
);
curl_setopt_array($ch, $curlOpts); //Set the curl array to $curlOpts

$answerTData = curl_exec($ch); //Set $answerTData to the curl response to the API.
$headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);  //Get the header length of the curl response
$curlBodyTData = substr($answerTData, $headerLen); //Remove header data from the curl string.

// If there was an error, show it
if (curl_error($ch)) {
    die(curl_error($ch));
}
curl_close($ch);

$jsonDecode = json_decode($curlBodyTData); //Decode the JSON returned by the Twilio API.

if(array_key_exists("status",$jsonDecode) && $jsonDecode->status=="queued")
{
    die("A fax has been queued for delivery to " . $jsonDecode->to);
}
else
{
    var_dump($jsonDecode);
}
