<?php
session_start();
include 'dbh_readOnly.php';

$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$email = $_POST['email'];
$phone = $_POST['phone'];

if ($password1 == $password2) {
	$sql = "SELECT * FROM `employees` WHERE `username`='{$username}' AND `email`='{$email}' AND `phone`='{$phone}';";
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
        showAlert('Phone number or email address is incorrect.');
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

?>