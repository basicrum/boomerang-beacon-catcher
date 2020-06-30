<?php
class Catcher_Utility_Storage
{

    /**
     * Factory for a beacons storage engine
     *
     * @param bool $debug
     * @return Catcher_Utility_Storage_Interface
     */
    static function getStorage(bool $debug = false)
    {
        $storageConfigFileName = __DIR__ . "/../config/storage.php";

        if (false === file_exists($storageConfigFileName)) {
            require_once __DIR__ . '/storage/file.php';
            return new Catcher_Utility_Storage_File($debug);
        }

        $storageConfig = include $storageConfigFileName;

        if ('file' == $storageConfig['engine']) {
            require_once __DIR__ . '/storage/file.php';
            return new Catcher_Utility_Storage_File($debug);
        }

        require_once __DIR__ . '/storage/file.php';
        return new Catcher_Utility_Storage_File($debug);
    }

}