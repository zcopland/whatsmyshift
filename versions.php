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
    <!-- Start of Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- End of Bootsrap -->
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
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
        <button class="btn btn-sm pull-right"><a href="index.php" class="white-text">Back</a></button>
        <footer class="text-center">Copyright Zach Copland {$date}. Version: {$currentVersion}</footer>
    </div>
    </div>
  </body>
</html>
HTML;

?>