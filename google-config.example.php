<?php
// google-config.example.php (Copy this to google-config.php and add your real credentials)
require_once 'vendor/autoload.php';

$clientID = 'YOUR_GOOGLE_CLIENT_ID_HERE';
$clientSecret = 'YOUR_GOOGLE_CLIENT_SECRET_HERE';
$redirectUri = 'http://localhost/Attendance/';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>
