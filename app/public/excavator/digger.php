<?php
/**
 * Interesting way to measure plain VS gzipped:
 *  - plain:   curl -i -H "Accept-Encoding: nothing" http://beacon-catcher.loc/excavator/digger.php | wc -c
 *  - gzipped: curl -i -H "Accept-Encoding: gzip, deflate" http://beacon-catcher.loc/excavator/digger.php | wc -c
 *
 * This also might be helpful when we decompress the response:
 *  https://unix.stackexchange.com/questions/290546/how-to-decompress-gzipped-http-response
 *
 *  command: sed -e '1,/^[[:space:]]*$/d' resp | gzip -d > resp.decompressed
 *
 *
 * Other hints. May be we want to delete the records from the DB after they are transferred to another location.
 *
 * We may use:
 *  - "SELECT MAX(`id`) FROM `beacons`";
 *  - "SELECT MIN(`id`) FROM `beacons`";
 *
 * Then we can be sure that:
 *  1. Fetch only the IDs in MIN and MAX range
 *  2. We can later delete the IDs in MIN and MAX range
 */
ini_set('memory_limit', -1);

include __DIR__ . "/../../utility/basic_auth.php";

$dbConfigFileName = __DIR__ . "/../../config/db_config.php";

if (false === file_exists($dbConfigFileName)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo "Your <strong style='color: red'>app/config/db_config.php</strong> is missing! You can create it from <strong style='color: green'>app/config/db_config.php.template</strong>";
    return;
}

$dbConfig = include $dbConfigFileName;

$mysqli = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);

$query = "SELECT * FROM `beacons` limit";

$res = $mysqli->query($query);
$beacons = $res->fetch_all(MYSQLI_ASSOC);

if(!empty($beacons)) {
    $maxId = end($beacons)['id'];
    $minId = reset($beacons)['id'];

    header('Content-Type: application/json');
    echo json_encode($beacons);

    $deleteQuery = "DELETE FROM `beacons` WHERE `id` >= $minId AND `id` <= $maxId";

    $res = $mysqli->query($deleteQuery);
}