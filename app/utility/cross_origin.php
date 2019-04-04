<?php
// Hacking quickly to handle Cross Origin Requests
// Better if we implemente this in NIGIX level
$origin = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
$originHeader = 'Access-Control-Allow-Origin: ' . $origin;

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header($originHeader);
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With, Keep-Alive, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    header('Access-Control-Max-Age: 86400');

    exit;
}

header($originHeader);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Keep-Alive, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');