<?php
session_start();
include 'dbh_readOnly.php';

$username = $_POST['username'];

$sql = "SELECT * FROM employees WHERE username='{$username}'";
$result = mysqli_query($conn_readOnly, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['canReset'] == true) {
    $_SESSION['alert-message'] = '';
    unset($_SESSION['alert-message']);
    echo true;
} else {
    showAlert('Your administrator has not allowed you to reset your password!');
    echo false;
}

function showAlert($message) {
    $_SESSION['alert-message'] = $message;
}

?>