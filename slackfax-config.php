<?php

//Twilio Configuration
$accountsid = "SID here"; //Find this on https://www.twilio.com/user/account/
$authtoken = "Token here"; //Find this on https://www.twilio.com/user/account/

//Slack Configuration
$slacktoken = "Slack Token Here"; //Set your token for the fax slash command
$webhookurl = "https://hooks.slack.com/services/tokens"; //Change this to the URL retrieved from incoming webhook setup for Slack.

//Set authentication for twilio
$twilauth = "Authentication: Basic " . base64_encode($accountsid . ":" . $authtoken);

