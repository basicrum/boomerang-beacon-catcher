<?php
include __DIR__ . "/../../utility/cross_origin.php";
include __DIR__ . "/../../utility/storage.php";

$debugMode = isset($_GET['debug_mode']);

$storage = Catcher_Utility_Storage::getStorage($debugMode);

/**
 * We use this in order to return 200 to the user as soon as possible.
 *
 * So far we will use this solution instead of doing with webserver approach
 */
if (!$debugMode) {
    header('Content-Type: image/gif');
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:01 GMT');
    header("HTTP/1.0 204 No Content");
    fastcgi_finish_request();
}

// Depending on the size of beacon data Boomerang may send GET or POST
$beacon = !empty($_GET) ? $_GET : $_POST;

if (!empty($beacon)) {
    $beacon['user_agent'] = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $beacon['created_at'] = date("Y-m-d H:i:s");
    $beaconJson = json_encode($beacon);

    $storage->storeBeacon($beaconJson);
    return;
}

echo "<strong>No beacon baby!</strong>";