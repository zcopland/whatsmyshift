<?php

/*
    logout.php
    
    This PHP file is used for the backend 
    of logging the user out.
*/

session_start();
$_SESSION['isAdmin'] = false;
$_SESSION['mall'] = '';
session_destroy();
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    unset($_COOKIE['username']);
    unset($_COOKIE['password']);
    setcookie('username', '', time() - 3600, '/');
    setcookie('password', '', time() - 3600, '/');
}
header("Location: index.php");
?>