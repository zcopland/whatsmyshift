<?php
session_start();
$_SESSION['loggedIn'] = false;
date_default_timezone_set('America/New_York');
include 'dbh_readOnly.php';
include 'dbh_addOnly.php';

$username = $_POST['username'];
$username = sanitize($username);
$password = $_POST['password'];
$password = sanitize($password);
$companyID = '';
$remember = '';
$date = date("m/d/Y @ g:ia");
if (isset($_POST['remember'])) {
    $remember = $_POST['remember'];
}

$sql = "SELECT * FROM `employees` WHERE username='{$username}';";
$result = mysqli_query($conn_readOnly, $sql);
$row = mysqli_fetch_assoc($result);
$companyID = $row['companyID'];

if (password_verify($password, $row['password'])) {
    //Correct
    if ($remember == 1) {
        setcookie('username', $username, time() + 3600 * 7, '/');
        setcookie('password', $password, time() + 3600 * 7, '/');
    }
    $sql = "UPDATE `employees` SET lastLogin='{$date}' WHERE username='{$username}';";
    $result = mysqli_query($conn_readOnly, $sql);
    $sql = "INSERT INTO `logins`(username, companyID, successful, `date`) VALUES ('{$username}', '{$companyID}', 1, '{$date}');";
    $result = mysqli_query($conn_addOnly, $sql);
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
    $sql = "INSERT INTO `logins`(username, companyID, successful, `date`) VALUES ('{$username}', '{$companyID}', 0, '{$date}');";
    $result = mysqli_query($conn_addOnly, $sql);
    $_SESSION['loggedIn'] = false;
    $_SESSION['incorrect'] = true;
    $_SESSION['pass-alert-index'] = true;
    header("Location: ../index.php");
}

function sanitize($dirty) {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

/*
    For Development:
    jbrown: hello123
    mlittle: 12345
*/

?>