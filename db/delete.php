<?php
include 'config.php';

$val = $_POST['val'];
$companyID = $_POST['companyID'];
date_default_timezone_set('America/New_York');
$date = date("Y-m-d", time() - 86400 * (int)$val); //86400 = 1 day

//First query
$sql = "INSERT INTO calendararchive (title, startdate, enddate, allDay, dateStamp) SELECT title, startdate, enddate, allDay, dateStamp FROM calendar WHERE dateStamp <= '{$date}'";
if (mysqli_query($con, $sql)) {
    echo true;
} else {
    echo "Failed. 1/2 Query: " . $sql;
    exit();
}

//Second query
$sql = "DELETE FROM calendar_{$companyID} WHERE dateStamp <= '{$date}';";
if (mysqli_query($con, $sql)) {
    echo true;
} else {
    echo "Failed. 2/2 Query: " . $sql;
}


?>