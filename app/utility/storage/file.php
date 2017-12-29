<?php
require_once __DIR__ . '/interface.php';

class Catcher_Utility_Storage_File
    implements Catcher_Utility_Storage_Interface
{

    private $storageDirectory = '/var/tmp';

    /**
     * @param string $beacon
     */
    public function storeBeacon($beacon)
    {
        file_put_contents($this->generateFileName($beacon), $beacon);
    }

    /**
     * Borrowed an idea from: https://stackoverflow.com/a/11597482/1016533
     *
     * Very interesting that the time inside the container drifts from my current time
     * at my workstation. I was surprised that it does not drift just bu complete hour
     * but actually it looks like "- 1h 12m 4s". THat is strange, isn't it?
     *
     * @return array
     */
    public function fetchBeacons()
    {
        $beaconFiles = scandir($this->storageDirectory, SCANDIR_SORT_DESCENDING);
        $beaconFiles = array_diff($beaconFiles, array('..', '.'));

        $beacons = [];

        foreach ($beaconFiles as $fileName) {
            $time = end(explode('-', $fileName));
            $timestamp =  date('Y-m-d G:i:s', $time);

            /**
             * Mimicking same structure that we get from MySql.
             *
             * We depend on internal container time for 'created_at'
             *
             * Also we don't have a real 'id' but for now I just
             * use beacon filename as 'id'.
             *
             */
            $beacons[] = [
                'id'          => $fileName,
                'beacon_data' => file_get_contents($this->storageDirectory . '/' . $fileName),
                'created_at'  => $timestamp
            ];
        }

        if (!empty($beaconFiles)) {
            $this->deleteFiles($beaconFiles);
        }

        return $beacons;
    }

    /**
     * @param string $beacon
     * @return string
     */
    private function generateFileName($beacon)
    {
        return $this->storageDirectory . '/' . md5($beacon) . '-' . mktime();
    }

    /**
     * @param array $files
     */
    private function deleteFiles(array $files)
    {
        foreach ($files as $fileName) {
            unlink($this->storageDirectory . '/' . $fileName);
        }
    }

}