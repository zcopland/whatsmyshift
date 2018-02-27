<?php
include 'dbh_readOnly.php';

$query = "UPDATE `employees` SET `booleanAgreeTC` = 0 WHERE id > 0;";
$result = mysqli_query($conn_readOnly, $query);

if ($result) {
    echo true;
} else {
    echo false;
}

?>