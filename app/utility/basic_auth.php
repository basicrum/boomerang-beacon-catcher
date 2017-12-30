<?php
$authConfigFileName = __DIR__ . "/../config/auth_config.php";

// We want to leave the security hole open in order to have an easy start.
if (false === file_exists($authConfigFileName)) {
    return;
}


$credentialsConfig = include $authConfigFileName;



if (true !== $credentialsConfig['enabled']) {
    return;
}

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = ($user == $credentialsConfig['username']) && ($pass == $credentialsConfig['password']);

if (!$validated) {
    header('WWW-Authenticate: Basic realm="Boomerang Beacon Catcher"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Not authorized");
}