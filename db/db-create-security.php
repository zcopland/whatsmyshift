<?php
include 'dbh_readOnly.php';
date_default_timezone_set('America/New_York');

$username = $_POST['username'];
$securityQuestion = $_POST['securityQuestion'];
$securityAnswer = $_POST['securityAnswer'];

$sql = "UPDATE `employees` SET `securityQuestion`='{$securityQuestion}', `securityAnswer`='{$securityAnswer}' WHERE `username`='{$username}';";
$result = mysqli_query($conn_readOnly, $sql);

if ($result) {
    header('Location: ../main.php');
} else {
    echo $securityQuestion . " -> " . $securityAnswer;
}



?>
