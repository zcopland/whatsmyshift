<?php
include "dbh_readOnly.php";

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$companyID = $_POST['companyID'];
$ip = '';
if (isset($_POST['ip'])) {
    $ip = $_POST['ip'];
} else {
    $ip = NULL;
}

$sql = "SELECT * FROM `blacklist` WHERE `blacklistFirstName`='{$firstName}' AND `blacklistLastName`='{$lastName}' AND `companyID`='{$companyID}';";
$result = mysqli_query($conn_readOnly, $sql);
$rows = mysqli_num_rows($result);


if ($rows > 0) { echo false; } 
else if ($rows <= 0) { echo true; }



?>