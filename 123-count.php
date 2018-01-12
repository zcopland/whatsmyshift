<?php
session_start();
date_default_timezone_set('America/New_York');
include 'db/dbh_addOnly.php';
include 'Account.php';
include_once("analyticstracking.php");

/* Variables */
$Account = new Account($_SESSION['id'], $conn_addOnly);
$isAdmin = $_SESSION['isAdmin'];
$username = $Account->getUsername();
if ($username != 'root') {
    header('Location: logout.php');
}
$allEmployees = $Account->getAllEmployees($conn_addOnly);

echo <<<HTML
<!DOCTYPE html>
  <head>
    <title>Update password</title>
    <meta charset='utf-8' />
	<!-- Start of Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- End of Bootsrap -->
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <div class="container">
        <h1 id="panel" class="vermillion-color">Update password</h1><hr/>
        <form method="post" action="db/123-count-process.php" onsubmit="return validate();">
        <div class="input-group">
            <label class="white-text" for="user-list">User</label>
            <select class="black-color form-control" id="user-list" name="user-list" required="true">
                <option value="--">--</option>
HTML;
foreach ($allEmployees as $fullName => $username) {
    echo <<<HTML
        <option value="{$username}">{$fullName} ({$username})</option>
HTML;
}
echo <<<HTML
            </select>
        </div>
        <div id="user-short-alert" class="vermillion-color"><small>Please select a user!</small></div>
        <br/>
        <div class="input-group">
            <label class="white-text" for="new-pass1">New password</label>
            <input type="password" id="new-pass1" name="new-pass1" class="form-control" placeholder="*******" required="true" />
        </div>
        <div id="password1-short" class="vermillion-color"><small>Password must be at least 6 characters!</small></div>
        <br/>
        <div class="input-group">
            <label class="white-text" for="new-pass2">Re-type password</label>
            <input type="password" id="new-pass2" name="new-pass2" class="form-control" placeholder="*******" required="true" />
        </div>
        <div id="password2-short" class="vermillion-color"><small>Password must be at least 6 characters!</small></div>
        <br/>
        <div id="password-match" class="vermillion-color"><small>Passwords do not match!</small></div>
        <br/>
        <div class="input-group">
            <label class="white-text" for="root-pass">Root password</label>
            <input type="password" id="root-pass" name="root-pass" class="form-control" placeholder="*******" required="true" />
        </div>
        <div id="root-alert" class="vermillion-color"><small>Root password is incorrect!</small></div>
        <br/>
HTML;
//include 'includes/reCAPTCHA.php';
echo <<<HTML
        <button name="submit" value="submit" type="submit" id="submitbtn" class="btn btn-success btn-md">Update</button>
        </form>
    </div>
    <script type="text/javascript" src="123-count.js"></script>
  </body>
</html>

HTML;

?>
