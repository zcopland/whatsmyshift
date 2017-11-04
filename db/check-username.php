<?php

include 'dbh_readOnly.php';

$username = $_POST['username'];

$sql = "SELECT * FROM employees WHERE username='{$username}'";
$result = mysqli_query($conn_readOnly, $sql);
$rows = mysqli_num_rows($result);

if ($rows > 0) {
    //username was found, they cannot create account
    echo false;
} else if ($rows <= 0) {
    //username was not found, they can create account
    echo true;
}

?>