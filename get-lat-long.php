<?php
$file = "includes/zip_lat_long.txt";
$fileArray = [];
$handle = fopen($file, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
       array_push($fileArray, $line);
    }

    fclose($handle);
} else {
    // error opening the file.
} 

$zips = [];
$lats = [];
$longs = [];

foreach ($fileArray as $line) {
    array_push($zips, substr($line, 0, 5));
    array_push($lats, substr($line, 6, 9));
    array_push($longs, substr($line, 17, 10));
}


?>