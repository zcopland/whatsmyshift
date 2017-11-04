<?php
include 'dbh_readOnly.php';

$companyID = $_POST['companyID'];
$val = $_POST['val'];

$sql = "UPDATE `companies` SET defaultCalView='{$val}' WHERE companyID='{$companyID}';";
$result = mysqli_query($conn_readOnly, $sql);

if ($result) {
    echo true;
} else {
    echo $sql;
}
    
?>