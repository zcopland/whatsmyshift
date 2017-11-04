<?php
session_start();
$_SESSION['loggedIn'] = false;
date_default_timezone_set('America/New_York');
include 'dbh_readOnly.php';

$username = $_POST['username'];
$password = $_POST['password'];
$remember = '';
if (isset($_POST['remember'])) {
    $remember = $_POST['remember'];
}

$sql = "SELECT * FROM employees WHERE username='{$username}'";
$result = mysqli_query($conn_readOnly, $sql);

$row = mysqli_fetch_assoc($result);

if (password_verify($password, $row['password'])) {
    //Correct
    if ($remember == 1) {
        setcookie('username', $username, time() + 3600 * 7, '/');
        setcookie('password', $password, time() + 3600 * 7, '/');
    }
    $date = date("m/d/Y @ g:ia");
    $sql = "UPDATE employees SET lastLogin='{$date}' WHERE username='{$username}'";
    $result = mysqli_query($conn_readOnly, $sql);
    $_SESSION['loggedIn'] = true;
    $_SESSION['incorrect'] = false;
    $_SESSION['pass-alert-index'] = false;
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['companyID'] = $row['companyID'];
    $_SESSION['organization'] = $row['organization'];
    $_SESSION['isAdmin'] = $row['isAdmin'];
    if ($row['username'] == 'root') {
        header("Location: ../root-panel.php");
    } else {
        header("Location: ../main.php");
    }
} else  {
    //Incorrect
    $_SESSION['loggedIn'] = false;
    $_SESSION['incorrect'] = true;
    $_SESSION['pass-alert-index'] = true;
    header("Location: ../index.php");
}

/*
    For Development:
    jalbert: 11111
    mlittle: 12345
*/

?>