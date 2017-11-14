<?php
$conn_root = mysqli_connect("localhost", "root", "", "whatsmyshift");
if (!$conn_root) {
    die("Connection failed: " . mysqli_connect_error());
}
?>