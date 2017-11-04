<?php
include 'dbh_readOnly.php';

$companyID = $_POST['companyID'];


$sql = "SELECT * FROM companies WHERE `companyID`='{$companyID}';";
$result = mysqli_query($conn_readOnly, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($row)) {
    echo $row['organization'];
} else {
    echo "Failed to process this request. Please go back and try to submit again.";
}


?>
