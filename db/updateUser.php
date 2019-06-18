<?php
include 'dbh_readOnly.php';

$val = $_POST['val'];
$phone = $_POST['phone'];
$email = $_POST['email'];

if (strlen($phone) > 10) {
    echo "Phone number too long.";
    exit();
}
if (strlen($email) > 40) {
    echo "Email too long.";
    exit();
}

$sql = "UPDATE employees SET `phone`='{$phone}', `email`='{$email}' WHERE `id`={$val};";
if (mysqli_query($conn_readOnly, $sql)) {
    echo true;
} else {
    echo "Failed. Query: " . $sql;
    exit();
}


?>