<?php

//Twilio Configuration
$accountsid = "SID Here"; //Find this on https://www.twilio.com/user/account/
$authtoken = "Token Here"; //Find this on https://www.twilio.com/user/account/
$faxpdf = "https://domain.tld/testfax.pdf"; //Link to your test fax that Twilio will send
$fromnumber = "6514888888"; //Phone number formatted with no country code and no dashes or parenthesis
$countrycode = "1"; //Set to your phones country code without the plus sign

//Slack Configuration
$slacktoken = "Slack Token Here"; //Set your token for the fax slash command
$webhookurl = "https://hooks.slack.com/services/tokens"; //Change this to the URL retrieved from incoming webhook setup for Slack.

//Set authentication for twilio
$twilauth = "Authentication: Basic " . base64_encode($accountsid . ":" . $authtoken);

