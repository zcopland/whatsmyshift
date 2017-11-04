<?php
session_start();
date_default_timezone_set('America/New_York');
include 'db/dbh_readOnly.php';
include 'Account.php';
include_once("analyticstracking.php");

/* Variables */
$Account = new Account($_SESSION['id'], $conn_readOnly);
$isAdmin = $_SESSION['isAdmin'];
$username = $Account->getUsername();
if ($username != 'root') {
    header('Location: logout.php');
}
/* Employee database */
$employeeTable = '';
$query = "SELECT * FROM `employees` ORDER BY companyID, isAdmin DESC;";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['username'] != 'root') {
        $employeeTable .= <<<TEXT
<tr>
<td>{$row['lastName']}, {$row['firstName']}</td>
<td>{$row['username']}</td>
<td>{$row['organization']}</td>
<td>{$row['companyID']}</td>
<td>{$row['email']}</td>
TEXT;
        if ($row['isAdmin'] == 1) {
            $employeeTable .= "<td>Admin</td>";
        } else if ($row['isAdmin'] == 0) {
            $employeeTable .= "<td>Employee</td>";
        }
        $employeeTable .= <<<TEXT
<td>{$row['lastLogin']}</td>
TEXT;
    }
    
}
/* Company database */
$companyTable = '';
$query = "SELECT * FROM `companies` ORDER BY organization DESC;";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $companyTable .= <<<TEXT
<tr>
<td>{$row['organization']}</td>
<td>{$row['companyID']}</td>
<td>{$row['adminUsername']}</td>
<td>{$row['adminEmail']}</td>
<td>{$row['organizationCount']}</td>
<td>{$row['billing']}</td>
<td>{$row['totalTextSent']}</td>
<td>{$row['totalEmailSent']}</td>
<td>{$row['dateCreated']}</td>
TEXT;
}
/* Verification database */
$verificationTable = '';
$query = "SELECT * FROM `codes`;";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $verificationTable .= <<<TEXT
<tr>
<td>{$row['code']}</td>
TEXT;
if ($row['used'] == 1) {
    $verificationTable .= "<td>Yes</td>";
} else if ($row['used'] == 0) {
    $verificationTable .= "<td>No</td>";
}
    $verificationTable .= <<<TEXT
<td>{$row['usedBy']}</td>
<td>{$row['usedOn']}</td>
TEXT;
}

echo <<<HTML
<!DOCTYPE html>
  <head>
    <title>Root Panel</title>
    <meta charset='utf-8' />
	<!-- Start of Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- End of Bootsrap -->
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <link rel="stylesheet" type="text/css" href="admin-styles.css"/>
  </head>
  <body>
    <div class="container">
        <h1 id="panel" class="vermillion-color">Root Panel</h1><hr/>
        <div class="row">
            <h2>Full Employee Database</h2>
        </div><br/>
        <table class="table">
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Company</th>
                <th>Company ID</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last login</th>
            </tr>
            {$employeeTable}
        </table>
        <br/>
        <div class="row">
            <h2>Company Database</h2>
        </div><br/>
        <table class="table">
            <tr>
                <th>Company</th>
                <th>Company ID</th>
                <th>Admin username</th>
                <th>Admin email</th>
                <th>Employees</th>
                <th>Billing</th>
                <th>Texts sent</th>
                <th>Emails sent</th>
                <th>Date Created</th>
            </tr>
            {$companyTable}
        </table>
        <div class="row">
            <h2>Verification Code Database</h2>
        </div><br/>
        <table class="table">
            <tr>
                <th>Code</th>
                <th>Used</th>
                <th>Used by</th>
                <th>Used on</th>
            </tr>
            {$verificationTable}
        </table>
    </div>
    <button id="back" class="btn vermillion-bg btn-md pull-right">Back</button>
    <script>
        $(document).ready(function() {
            $('#back').click(function() {
                window.history.back(-1);
            });
        });
    </script>
  </body>
</html>

HTML;
?>