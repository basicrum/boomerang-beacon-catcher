<?php
$authConfigFileName = __DIR__ . "/../config/auth_config.php";

if (false === file_exists($authConfigFileName)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo "Your Basic Auth configuration file: <strong style='color: red'>app/config/auth_config.php</strong> is missing! You can create it from <strong style='color: green'>app/config/auth_config.php.template</strong>";
    die();
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