<?php
session_start();
include 'dbh_readOnly.php';

$username = $_POST['username'];
$username = sanitize($username);
$password1 = $_POST['password1'];
$password1 = sanitize($password1);
$password2 = $_POST['password2'];
$password2 = sanitize($password2);

if ($password1 == $password2) {
	$sql = "SELECT * FROM `employees` WHERE `username`='{$username}';";
    $result = mysqli_query($conn_readOnly, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row > 0) {
        /* found row, now update password */
        $id = $row['id'];
        if ($row['canReset'] == 1) {
            $pwd = password_hash($password1, PASSWORD_BCRYPT, array("cost" => 10));
        	$sql = "UPDATE employees SET password='{$pwd}', canReset=0 WHERE id={$id};";
            $result2 = mysqli_query($conn_readOnly, $sql);
            if ($result2) {
                $_SESSION['alert-message'] = '';
                unset($_SESSION['alert-message']);
                header("Location: ../index.php");
            }
        } else {
            showAlert('You are not authorized to reset your password. Please contact your administrator.');
            header("Location: ../reset-pass.php");
        }
        
        
    } else if ($row <= 0) {
        /* could not find email or phone */
        showAlert('Could not find account.');
		header("Location: ../reset-pass.php");
    }

} else {
	/* passwords do not match */
	showAlert('Passwords do not match.');
	header("Location: ../reset-pass.php");
}

function showAlert($message) {
    $_SESSION['alert-message'] = $message;
}

function sanitize($dirty) {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

?>