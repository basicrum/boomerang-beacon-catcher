<?php
include __DIR__ . "/../../utility/cross_origin.php";
include __DIR__ . "/../../utility/storage.php";

$storage = Catcher_Utility_Storage::getStorage();

/**
 * We use this in order to return 200 to the user as soon as possible.
 *
 * So far we will use this solution instead of doing with webserver approach
 */
fastcgi_finish_request();

// Depending on the size of beacon data Boomerang may send GET or POST
$beacon = !empty($_GET) ? $_GET : $_POST;

if (!empty($beacon)) {
    $beacon['user_agent'] = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $beaconJson = json_encode($beacon);

    $storage->storeBeacon($beaconJson);
    return;
}

echo "<strong>No beacon baby!</strong>";