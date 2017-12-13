<?php
include 'dbh_readOnly.php';
date_default_timezone_set('America/New_York');

$companyID = $_POST['companyID'];


$sql = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
$result = mysqli_query($conn_readOnly, $sql);
$rows = mysqli_num_rows($result);
if ($rows > 0) {
    echo false;
} else if ($rows <= 0) {
    echo true;
}



?>
