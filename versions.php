<?php
include 'db/dbh_readOnly.php';
include_once("analyticstracking.php");

$sql = "SELECT * FROM versions ORDER BY id DESC";
$result = mysqli_query($conn_readOnly, $sql);
$index = 0;
$date = date("Y");

/* Current Version Variable */
$currentVersion = '';

while ($row = mysqli_fetch_assoc($result)) {
    if ($index == 0) {
        $currentVersion = $row['version'];
    }
    $index++;
}

echo <<<HTML
<!DOCTYPE html>
  <head>
    <title>Versions</title>
HTML;
include 'includes/header.php';
echo <<<HTML
  </head>
  <body>
HTML;
include 'includes/navbar.php';
echo <<<HTML
  <div class="container">
    <h1>Versions</h1>
    <h2>Current version: {$currentVersion}</h2>
    <table class="table">
        <tr><th>Version</th><th>Update</th></tr>
HTML;
$sql = "SELECT * FROM versions ORDER BY id DESC";
$result = mysqli_query($conn_readOnly, $sql);
while ($row = mysqli_fetch_assoc($result)) { 
		echo <<<HTML
<tr>
  <td>{$row['version']}</td>
  <td>{$row['description']}</td>
</tr>
HTML;
    }
    echo <<<HTML
    </table>
    <div class="">
        <button class="btn btn-sm pull-right vermillion-bg"><a href="index.php" class="white-text">Back</a></button>
        <footer class="text-center">Copyright Zach Copland {$date}. Version: {$currentVersion}</footer>
    </div>
    </div>
  </body>
</html>
HTML;

?>