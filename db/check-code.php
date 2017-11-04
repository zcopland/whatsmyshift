<?php
include 'dbh_readOnly.php';
date_default_timezone_set('America/New_York');
$date = date("m/d/Y @ g:ia");

$username = $_POST['username'];
$code = $_POST['code'];

$sql = "SELECT * FROM `codes` WHERE `code`={$code}";
$result = mysqli_query($conn_readOnly, $sql);
$rows = mysqli_num_rows($result);

if ($rows > 0) {
    //code was found
    if ($rows['used'] == 0) {
        //code has not been used
        $sql = "UPDATE `codes` SET `used`=1, `usedOn`='{$date}', `usedBy`='{$username}' WHERE `code`={$code};";
        $result2 = mysqli_query($conn_readOnly, $sql);
        if ($result2) {
            echo true;
        } else {
            echo false;
        }
    } else {
        //code has been found but already used
        echo false;
    }
    
} else if ($rows <= 0) {
    //code was not found
    echo false;
}

?>