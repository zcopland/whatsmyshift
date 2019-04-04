<?php
include 'dbh_root.php';

$val = $_POST['val'];
$companyID = $_POST['companyID'];

$sql = "INSERT INTO `employees_deleted` SELECT * FROM `employees` WHERE employees.id = {$val};";
$result = mysqli_query($conn_root, $sql);


if ($result) {
    $sql = "DELETE FROM `employees` WHERE id = {$val};";
    $result = mysqli_query($conn_root, $sql);
    if ($result) {
        $sql = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn_root, $sql);
        $row = mysqli_fetch_assoc($result);
        $count = $row['organizationCount'] - 1;
        $sql = "UPDATE `companies` SET organizationCount={$count} WHERE companyID='{$companyID}';";
        $result2 = mysqli_query($conn_root, $sql);
        echo true;
    } else {
        echo $sql;
    }
} else {
    echo $sql;
}
    
?>