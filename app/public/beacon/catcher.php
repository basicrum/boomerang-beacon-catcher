<?php
include __DIR__ . "/../../utility/cross_origin.php";

$dbConfigFileName = __DIR__ . "/../../config/db_config.php";

if (false === file_exists($dbConfigFileName)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo "Your <strong style='color: red'>app/config/db_config.php</strong> is missing! You can create it from <strong style='color: green'>app/config/db_config.php.template</strong>";
    return;
}

// Depending on the size of beacon data Boomerang may send GET or POST
$beacon = !empty($_GET) ? $_GET : $_POST;


if (!empty($beacon)) {
    $dbConfig = include $dbConfigFileName;

    $mysqli = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);

    $beaconJson = $mysqli->escape_string(json_encode($beacon));

    $query = "INSERT INTO `beacons` (`beacon_data`) VALUES ('$beaconJson')";

    $mysqli->query($query);

    echo $mysqli->error;
    return;
}

echo "<strong>No beacon baby!</strong>";