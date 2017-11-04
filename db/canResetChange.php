<?php
include 'dbh_readOnly.php';

$val = $_POST['val'];
$companyID = $_POST['companyID'];

$sql = "UPDATE `employees` SET canReset=1 WHERE id={$val};";
$result = mysqli_query($conn_readOnly, $sql);

if ($result) {
    echo true;
} else {
    echo $sql;
}
    
?>