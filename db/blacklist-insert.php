<?php
include 'dbh_addOnly.php';
date_default_timezone_set('America/New_York');

$username = $_POST['username'];
$blacklistFirstName = $_POST['blacklistFirstName'];
$blacklistLastName = $_POST['blacklistLastName'];
$ip = '';
if (isset($_POST['ip'])) {
    $ip = $_POST['ip'];
} else {
    $ip = NULL;
}
$companyID = $_POST['companyID'];
$notes = $_POST['notes'];
$date = date("m/d/Y @ g:ia");

$sql = "INSERT INTO `blacklist` (blacklistFirstName, blacklistLastName, ip, submittedBy, companyID, dateSubmitted, notes) VALUES ('{$blacklistFirstName}', '{$blacklistLastName}', '{$ip}', '{$username}', '{$companyID}', '{$date}', '{$notes}');";
$result = mysqli_query($conn_addOnly, $sql);
if ($result) {
    header("Location: ../index.php");
} else {
    echo "Error processing request. Please go back and try again.";
}




?>