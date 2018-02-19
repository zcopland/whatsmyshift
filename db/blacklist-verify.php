<?php
include 'dbh_readOnly.php';

$username = $_POST['username'];
$password = $_POST['password'];
$companyID = $_POST['companyID'];

$sql = "SELECT * FROM `employees` WHERE `username`='{$username}';";
$result = mysqli_query($conn_readOnly, $sql);
$row = mysqli_fetch_assoc($result);
$numrows = mysqli_num_rows($result);

if ($numrows < 1) {
    echo "Username not found!";
} else {
    if (password_verify($password, $row['password'])) {
        if ($companyID == $row['companyID'] && $row['isAdmin'] == 1) {
            echo true;
        } else if ($companyID != $row['companyID']) {
            echo "Company ID is incorrect!";
        } else if ($row['isAdmin'] != 1) {
            echo "You must be an admin!";
        }
    } else  {
        echo "Username and password do not match up!";
    }
}




?>