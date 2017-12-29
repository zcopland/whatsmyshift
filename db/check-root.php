<?php
include 'dbh_readOnly.php';

$password = $_POST['password'];
$query = "SELECT * FROM `employees` WHERE username='root';";
$result = mysqli_query($conn_readOnly, $query);
$row = mysqli_fetch_assoc($result);

if (password_verify($password, $row['password'])) {
    echo true;
} else {
    echo false;
}

?>