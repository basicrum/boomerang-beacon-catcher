<?php
interface Catcher_Utility_Storage_Interface
{

    /**
     * @param string $beacon
     */
    public function storeBeacon(string $beacon);


    /**
     * @param string $host
     * @return array
     */
    public function fetchBeacons(string $host) : array;

    /**
     * @return array
     */
    public function info() : array;

}