<?php
class Catcher_Utility_Storage
{

    /**
     * @return Catcher_Utility_Storage_Interface
     */
    static function getStorage()
    {
        $storageConfigFileName = __DIR__ . "/../config/storage.php";

        if (false === file_exists($storageConfigFileName)) {
            require_once __DIR__ . '/storage/file.php';
            return new Catcher_Utility_Storage_File();
        }

        $storageConfig = include $storageConfigFileName;

        if ('file' == $storageConfig['engine']) {
            require_once __DIR__ . '/storage/file.php';
            return new Catcher_Utility_Storage_File();
        }


        if ('mysql' == $storageConfig['engine']) {
            require_once __DIR__ . '/storage/mysql.php';
            return new Catcher_Utility_Storage_Mysql();
        }

        require_once __DIR__ . '/storage/file.php';
        return new Catcher_Utility_Storage_File();
    }

}