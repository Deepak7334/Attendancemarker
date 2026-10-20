<?php
// google-login.php
require_once 'google-config.php';

// Redirect user to Google's OAuth Server
header('Location: ' . $client->createAuthUrl());
exit;
?>