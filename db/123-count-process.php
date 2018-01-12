<?php
include 'dbh_addOnly.php';
include "../includes/recaptchalib.php";

if ($recap_error) {
    exit();
}

/* Globals */
$username = $_POST['user-list'];
$newPass = $_POST['new-pass1'];
$newPass = password_hash($newPass, PASSWORD_BCRYPT, array("cost" => 10));

$query = "UPDATE `employees` SET `password`='{$newPass}' WHERE `username`='{$username}';";
$result = mysqli_query($conn_addOnly, $query);

if ($result) {
    header('Location: ../index.php');
} else {
    echo 'Query failed: ' . mysqli_error($conn_addOnly);
}

    
?>
