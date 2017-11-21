<?php
date_default_timezone_set('America/New_York');
include 'dbh_readOnly.php';

/* Variables */
$username = $_POST['username'];
$mode = $_POST['mode'];
$securityAnswer_guess = '';
if (isset($_POST['securityAnswer_guess'])) {
    $securityAnswer_guess = $_POST['securityAnswer_guess'];
}
$sql = "SELECT * FROM `employees` WHERE `username`='{$username}';";
$result = mysqli_query($conn_readOnly, $sql);
$row = mysqli_fetch_assoc($result);
$securityQuestion = $row['securityQuestion'];
$securityAnswer_actual = $row['securityAnswer'];


if ($mode == 'question') {
    echo $securityQuestion;
} else if ($mode == 'answer') {
    if ($securityAnswer_guess == $securityAnswer_actual) {
        echo true;
    } else {
        echo false;
    }
}



?>
