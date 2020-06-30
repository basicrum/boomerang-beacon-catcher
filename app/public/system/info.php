<?php
ini_set('memory_limit', -1);

//Return non 200 in case origin GET param is missing

include __DIR__ . "/../../utility/basic_auth.php";
include __DIR__ . "/../../utility/storage.php";

$storage = Catcher_Utility_Storage::getStorage();

header('Content-Type: application/json');
echo json_encode($storage->info());