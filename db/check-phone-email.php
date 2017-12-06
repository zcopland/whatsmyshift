<?php
include 'dbh_readOnly.php';

$phone = '';
$email = '';
$phoneFound = false;
$emailFound = false;

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    $sql = "SELECT * FROM `employees` WHERE phone='{$phone}'";
    $result = mysqli_query($conn_readOnly, $sql);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) { $phoneFound = true; } 
    else if ($rows <= 0) { $phoneFound = false; }
} else if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM `employees` WHERE email='{$email}'";
    $result = mysqli_query($conn_readOnly, $sql);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) { $emailFound = true; } 
    else if ($rows <= 0) { $emailFound = false; }
}

if ($emailFound || $phoneFound) {
    echo false;
} else {
    echo true;
}

?>