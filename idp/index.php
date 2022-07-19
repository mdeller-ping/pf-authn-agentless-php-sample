<?php

// PSEUDOCODE
// 
// PingFederate POSTs user to this code, including: REF, resumePath
//
// If REF of resumePath are missing, redirect user to PingFederate to begin flow
//
// TODO: Optional API query to PingFederate to find out what PingFederate already knows
//
// TODO: Authenicate the User
//
// REST API POST to PingFederate to "Dropoff" the authenticated user
//
// PingFederate responds with new REF
//
// Redirect User to PingFederate + resumePath + REF

$debug = false;

if ($debug) echo "<p>debug enabled</p>\n";

// variable definitions

$pingFederateHost = 'auth.example.com:9031'; // hostname:engine_port
$pingFederateUser = 'DefinedInAgentlessAdapter'; // PingFederate agentless adapter username
$pingFederatePass = 'DefinedInAgentlessAdapter'; // PingFederate agentless adapter password
$pingFederateInst = 'AgentlessAdapterId'; // PingFederate agentless adapter instance id

// get stuff from form post

$referenceId = $_POST['REF'];
$resumePath = $_POST['resumePath'];

// optional debug

if ($debug) echo "<p>$referenceId</p>\n";
if ($debug) echo "<p>$resumePath</p>\n";

// did user show up without REF and resumePath?

if (! $referenceId || ! $resumePath) {

    if ($debug) echo "<p>referenceId or resumePath required</p>\n";

    if ($debug) exit();

    // redirect the user to PingFederate to start login flow

    // for example https://auth.example.com:9031/idp/startSSO....
    // or https://auth.example.com:9031/as/authorization.oauth2?client_id=....

    header ('Location: https://www.example.com');

    exit();

}

// TODO: Optional API query to PingFederate to find out what PingFederate already knows

// TODO: Authenicate the User

// Get ready to drop off user information to PingFederate

$subject = "user.0";

// backchannel call to tell pingfederate who the authenticated user is

$dropoffUrl = "https://" . $pingFederateHost . "/ext/ref/dropoff";

if ($debug) echo "<p>dropoffUrl: $dropoffUrl</p>\n";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://' . $pingFederateHost . '/ext/ref/dropoff',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  // CURLOPT_SSL_VERIFYHOST => false,
  // CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "subject": "' . $subject . '"
}',
  CURLOPT_HTTPHEADER => array(
    'ping-instanceId: ' . $pingFederateInst,
    'ping-uname: ' . $pingFederateUser,
    'ping-pwd: ' . $pingFederatePass,
    'Content-Type: application/json'
  ),
));

$dropoffResponse = curl_exec($curl);

$dropoffResponseData = json_decode($dropoffResponse, true);

// parse out the new REF ID

$referenceId = $dropoffResponseData['REF'];

// redirect the user to resumeUrl 

$resumeUrl = "https://" . $pingFederateHost . $resumePath . "?REF=" . $referenceId;

if ($debug) echo "<p>subject: $subject</p>\n";
if ($debug) echo "<p>resumeUrl: $resumeUrl</p>\n";
if ($debug) echo "<p>referenceId: $referenceId</p>\n";
if ($debug) echo "<p>dropoffResponse: $dropoffResponse</p>\n";

if ($debug) exit();

header ("Location: " . $resumeUrl);

exit();

?>
