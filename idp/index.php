<?php

// psuedocode
//
// ( unsolicited GET -> redirect to authorization request url ), OR
// ( GET with reference ID & resumePath )
//
// optional backchannel Query PF for attributes
// we authenticate user
// mandatory backchannel call to PF to dropoff attributes
// redirect the user to resumeUrl 

require("httpful.phar");

$debug = false;

if ($debug) echo "<p>debug enabled</p>\n";

// variable definitions

$pingFederateHost = 'localhost:9031'; // hostname:engine_port
$pingFederateUser = 'DropOffUser'; // PingFederate agentless adapter username
$pingFederatePass = '2FederateM0re'; // PingFederate agentless adapter password

// get stuff from form post

$referenceId = $_POST['REF'];
$resumePath = $_POST['resumePath'];

if ($debug) echo "<p>$referenceId</p>\n";
if ($debug) echo "<p>$resumePath</p>\n";

// unsolicted inbound GET
// get rid of people who show up without referenceId or resumePath
// in pingfederate, might want a query parameter selector or other branch
// mechanism to isolate the redirect below

if (! $referenceId || ! $resumePath) {

    $unsolicitedUrl = "https://" . $pingFederateHost . "/as/authorization.oauth2?client_id=Implicit&response_type=token&redirect_uri=https://decoder.pingidentity.cloud/implicit&dropoff=true";

    if ($debug) echo "<p>$unsolicitedUrl</p>\n";

    header ('Location: ' . $unsolicitedUrl);

    exit();

}

// optional backchannel query pingfederate.  not required - but might provide
// valuable context of user if there was a prior adapter in authentication
// policy

$pickupUrl = "https://" . $pingFederateHost . "/ext/ref/pickup?REF=" . $referenceId;

if ($debug) echo "<p>pickupUrl: $pickupUrl</p>\n";

$pickupResponse = \Httpful\Request::get($pickupUrl)
    ->authenticateWith($pingFederateUser, $pingFederatePass)
    ->expectsJson()
    ->send();

if ($debug) echo "<p>pickupReseponse: $pickupResponse</p>\n";

// perform user authentication

// TODO

$subject = "michael@example.com";

// backchannel call to tell pingfederate who the authenticated user is

$dropoffUrl = "https://" . $pingFederateHost . "/ext/ref/dropoff";

if ($debug) echo "<p>dropoffUrl: $dropoffUrl</p>\n";

$dropoffResponse = \Httpful\Request::post($dropoffUrl)
    ->authenticateWith($pingFederateUser, $pingFederatePass)
    ->sendsJson()
    ->body(['subject' => $subject])
    ->send();

$referenceId = "{$dropoffResponse->body->REF}";

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