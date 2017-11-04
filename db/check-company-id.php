<?php
include 'dbh_addOnly.php';
date_default_timezone_set('America/New_York');

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$pwd = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => 10));
$email = $_POST['email'];
$phone = $_POST['phone'];
$org = $_POST['org'];
$companyID = $_POST['companyID'];
$date = date("m/d/Y @ g:ia");
$isAdmin = 0;
if ($_POST['role'] == 'admin') {
    $isAdmin = 1;
} else if ($_POST['role'] == 'regular') {
    $isAdmin = 0;
}


$sql = "INSERT INTO employees(firstName, lastName, username, password, email, phone, organization, companyID, isAdmin, lastLogin) VALUES ('{$firstName}', '{$lastName}', '{$username}', '{$pwd}', '{$email}', '{$phone}', '{$org}', '{$companyID}', {$isAdmin}, '{$date}')";
echo $sql;
$result = mysqli_query($conn_addOnly, $sql);
if ($result) {
    //header("Location: ../index.php");
    //header("Location: send-email.php?firstName={$firstName}&lastName={$lastName}&username={$username}");
} else {
    echo "Failed to process this request. Please go back and try to submit again.";
}


?>
