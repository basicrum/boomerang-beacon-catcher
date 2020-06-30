<?php

declare(strict_types=1);

require_once __DIR__ . '/interface.php';

class Catcher_Utility_Storage_File
    implements Catcher_Utility_Storage_Interface
{

    /** @var bool */
    private $debug;

    /** @var string */
    private $storageRootDirectory = '/var/tmp/beacons';

    /** @var string */
    const UNKNOWN_HOST_PLACEHOLDER = 'unknown';

    /**
     * Catcher_Utility_Storage_File constructor.
     * @param bool $debug
     */
    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @param string $beacon
     */
    public function storeBeacon(string $beacon)
    {
        $name = $this->generateFileName($beacon);

        if ($this->debug) {
            echo 'File: ' .  $name;
        }

        file_put_contents($name, $beacon);
    }

    /**
     * @param $host string
     * @return array
     */
    public function fetchBeacons(string $host) : array
    {
        $dir = $this->getStorageDir($this->hostToPath($host));

        if (!is_dir($dir)) {
            return [];
        }

        $beaconFiles = glob($dir . '/*.json');

        $beacons = [];

        foreach ($beaconFiles as $filePath) {
            $beacons[] = [
                'id'          => basename($filePath),
                'beacon_data' => file_get_contents($filePath)
            ];
        }

        if (!empty($beaconFiles)) {
            $this->deleteFiles($beaconFiles, $dir);
        }

        return $beacons;
    }

    /**
     * @param string $beacon
     * @return string
     */
    private function generateFileName($beacon) : string
    {
        $origin = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : self::UNKNOWN_HOST_PLACEHOLDER;

        if (self::UNKNOWN_HOST_PLACEHOLDER !== $origin) {
            $parsed = parse_url($origin);

            $suffix = $this->hostToPath($parsed['host']);
        } else {
            $suffix = self::UNKNOWN_HOST_PLACEHOLDER;
        }

        $dir = $this->getStorageDir($suffix);

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir . '/' . $suffix . '_' . md5($beacon) . '-' . time() . '-' . rand(1, 99999) . '.json';
    }

    /**
     * @param string $suffix
     * @return string
     */
    private function getStorageDir(string $suffix) : string
    {
        return $this->storageRootDirectory . '/' . $suffix;
    }

    /**
     * @param string $host
     * @return string
     */
    private function hostToPath(string $host) : string
    {
        if (self::UNKNOWN_HOST_PLACEHOLDER === $host) {
            return self::UNKNOWN_HOST_PLACEHOLDER;
        }

        return str_replace('.', '_', $host);
    }

    /**
     * @param array $files
     * @param string $dir
     */
    private function deleteFiles(array $files, string $dir)
    {
        foreach ($files as $fileName) {
            unlink($dir . '/' . $fileName);
        }
    }

    /**
     * @return array
     */
    public function info() : array
    {
        $dirs = glob($this->storageRootDirectory . '/*', GLOB_ONLYDIR);

        $data = [
            'beacon_dirs' => []
        ];

        foreach ($dirs as $dir) {
            $beaconsInDir = glob($dir . '/*.json');

            $data['beacon_dirs'][$dir]['beacons_count'] = count($beaconsInDir);
        }

        return $data;
    }

}