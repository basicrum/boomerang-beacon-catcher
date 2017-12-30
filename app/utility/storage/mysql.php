<?php
require_once __DIR__ . '/interface.php';

class Catcher_Utility_Storage_Mysql
    implements Catcher_Utility_Storage_Interface
{

    /** @var \mysqli */
    private $_mysqli;

    public function __construct()
    {
        $dbConfigFileName = __DIR__ . "/../../config/db_config.php";

        if (false === file_exists($dbConfigFileName)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "Your <strong style='color: red'>app/config/db_config.php</strong> is missing! You can create it from <strong style='color: green'>app/config/db_config.php.template</strong>";
            exit;
        }

        $dbConfig = include $dbConfigFileName;

        $this->_mysqli =
            mysqli_connect(
                $dbConfig['host'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                $dbConfig['port']
            );
    }

    /**
     * @param string $beacon
     */
    public function storeBeacon($beacon)
    {
        $beaconJson = $this->_mysqli->escape_string(json_encode($beacon));

        $query = "INSERT INTO `beacons` (`beacon_data`) VALUES ('$beaconJson')";

        $this->_mysqli->query($query);
    }

    /**
     * @return array
     */
    public function fetchBeacons()
    {
        $query = "SELECT * FROM `beacons`";

        $res = $this->_mysqli->query($query);
        $beacons = $res->fetch_all(MYSQLI_ASSOC);

        if(!empty($beacons)) {
            $this->deleteBeacons($beacons);
        }

        return $beacons;
    }

    /**
     * @param array $beacons
     */
    private function deleteBeacons(array $beacons)
    {
        $maxId = end($beacons)['id'];
        $minId = reset($beacons)['id'];

        $deleteQuery = "DELETE FROM `beacons` WHERE `id` >= $minId AND `id` <= $maxId";

        $this->_mysqli->query($deleteQuery);
    }

}