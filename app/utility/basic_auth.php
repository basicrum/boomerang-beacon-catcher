<?php
$enabled = getenv('BASIC_AUTH_ENABLED', true);

if ('yes' !== $enabled) {
    return;
}

$confUsername = getenv('BASIC_AUTH_USERNAME', true);
$confPassword = getenv('BASIC_AUTH_PASSWORD', true);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = ($user == $confUsername) && ($pass == $confPassword);

if (!$validated) {
    header('WWW-Authenticate: Basic realm="Boomerang Beacon Catcher"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Not authorized");
}