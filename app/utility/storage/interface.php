<?php
interface Catcher_Utility_Storage_Interface
{

    /**
     * @param string $beacon
     */
    public function storeBeacon($beacon);


    /**
    * @return array
    */
    public function fetchBeacons();

}