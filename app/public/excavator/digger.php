<?php
/**
 * Interesting way to measure plain VS gzipped:
 *  - plain:   curl -i -H "Accept-Encoding: nothing" http://beacon-catcher.loc/excavator/digger.php | wc -c
 *  - gzipped: curl -i -H "Accept-Encoding: gzip, deflate" http://beacon-catcher.loc/excavator/digger.php | wc -c
 *
 * This also might be helpful when we decompress the response:
 *  curl --compressed "http://example.com"
 *
 *  OR
 *   |
 *   |
 *  https://unix.stackexchange.com/questions/290546/how-to-decompress-gzipped-http-response
 *
 *  command: sed -e '1,/^[[:space:]]*$/d' resp | gzip -d > resp.decompressed
 *
 */
ini_set('memory_limit', -1);

include __DIR__ . "/../../utility/basic_auth.php";
include __DIR__ . "/../../utility/storage.php";

$storage = Catcher_Utility_Storage::getStorage();

header('Content-Type: application/json');
echo json_encode($storage->fetchBeacons());