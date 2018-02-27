<?php
include 'dbh_readOnly.php';
date_default_timezone_set('America/New_York');

$username = $_POST['username'];
$date = date("m/d/Y @ g:ia");

$query = "UPDATE `employees` SET `booleanAgreeTC`=1, `dateAgreeTC`='{$date}' WHERE `username`='{$username}';";
$result = mysqli_query($conn_readOnly, $query);

if ($result) {
    echo true;
} else {
    echo false;
}


?>