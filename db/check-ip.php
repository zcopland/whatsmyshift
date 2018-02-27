<?php
include 'dbh_readOnly.php';
include "../Account.php";

$Account = new Account(1, $conn_readOnly);
$blacklistIPs = $Account->getBlacklistIPs($conn_readOnly);
$ip = $_POST['ip'];

if (in_array($ip, $blacklistIPs)) {
    echo false;
} else {
    echo true;
}


?>