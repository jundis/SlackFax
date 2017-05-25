<?php

ini_set('display_errors', 1); //Display errors in case something occurs
header('Content-Type: application/json'); //Set the header to return JSON, required by Slack
require_once("slackfax-config.php");

/**
 * @param $url
 * @param $header
 * @param $postfieldspre
 * @return mixed
 */
function cURLPost($url, $header, $request, $postfieldspre)
{
    global $debugmode; //Require global variable $debugmode from config.php
    $ch = curl_init(); //Initiate a curl session

    $postfields = json_encode($postfieldspre); //Format the array as JSON

    //Same as previous curl array but includes required information for PATCH commands.
    $curlOpts = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => $request,
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts);

    $answerTCmd = curl_exec($ch);
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $curlBodyTCmd = substr($answerTCmd, $headerLen);

    if($debugmode)
    {
        var_dump($answerTCmd);
    }

    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);
    if($curlBodyTCmd == "ok") //Slack catch
    {
        return null;
    }

}

//Timeout Fix Block
if($timeoutfix == true)
{
    ob_end_clean();
    header("Connection: close");
    ob_start();
    echo ('{"response_type": "in_channel"}');
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();
    session_write_close();
    if($sendtimeoutwait==true) {
        cURLPost($_REQUEST["response_url"], array("Content-Type: application/json"), "POST", array("parse" => "full", "response_type" => "ephemeral", "text" => "Please wait..."));
    }
}
//End timeout fix block