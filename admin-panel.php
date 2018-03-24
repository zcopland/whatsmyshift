<?php
/*
    admin-panel.php
    
    This PHP file is used to display useful
    information on the admin panel page and
    uses the Account class.
*/

//Start session, set timezone, and include necessary files
session_start();
date_default_timezone_set('America/New_York');
include 'db/dbh_readOnly.php';
include 'Account.php';
include_once("analyticstracking.php");

/* Variables */
$Account = new Account($_SESSION['id'], $conn_readOnly);
$isAdmin = $_SESSION['isAdmin'];
$firstName = $Account->getFirstName();
$lastName = $Account->getLastName();
$username = $Account->getUsername();
$email = $Account->getEmail();
$phone = $Account->getPhone();
$organization = $Account->getOrganization();
$companyID = $Account->getCompanyID();
$lastLogin = $Account->getLastLogin();
$zip = $Account->getZip($companyID ,$conn_readOnly);
$weatherShow = $Account->getWeatherShow($companyID ,$conn_readOnly);
$canReset = $Account->getCanReset();
$checked = '';
$employeeCount = $Account->getEmployeeCount($companyID, $conn_readOnly);
$employeeTable = $Account->getEmployeeTable($companyID, $conn_readOnly);
$notificationTable = $Account->getNotificationTable($companyID, $conn_readOnly);
$canViewLogins = $Account->getCanViewLogins($companyID, $conn_readOnly);
$loginTable = '';
if ($canViewLogins) {
    $loginTable = $Account->getLoginTable($companyID, $conn_readOnly);
}


//If username is not set, send them back to login page
if (!isset($isAdmin)  || empty($isAdmin)) {
    header('Location: index.php');
}

//If weatherShow show true, make the checkbox checked
if ($weatherShow == 1) {
    $checked = 'checked';
}

echo <<<HTML
<!DOCTYPE html>
  <head>
    <title>Admin Panel</title>
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
        <h1 id="panel" class="vermillion-color">Admin Panel</h1><hr/>
        <div class="row">
            <h2>Your information:</h2>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h4 id="firstName">First name: <strong class="info">{$firstName}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="lastName">Last name: <strong class="info">{$lastName}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="username">Username: <strong class="info">{$username}</strong></h4>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h4 id="email">Email: <strong class="info">{$email}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="phone">Phone number: <strong class="info">{$phone}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="companyID">Company ID: <strong class="info">{$companyID}</strong></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h4 id="organization">Organization: <strong class="info">{$organization}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="zip">Zip code: <strong class="info">{$zip}</strong></h4>
            </div>
            <div class="col-sm-4">
                <h4 id="employeeCount">Employees: <strong class="info">{$employeeCount}</strong></h4>
            </div>
        </div>
        <div class="row">
            <small>To change any of <strong>your</strong> information, please contact the site's administrator <a href="mailto:zcopland16@gmai.com">Zach Copland</a>.</small>
        </div>
        <br/>
        <div class="row">
            <h2>Calendar view:</h2>
        </div>
        <div class="row">
            <form>
                <div class="col-sm-4">
                    <h4>Show weather : <input type="checkbox" id="weatherShow" name="weatherShow" {$checked} /></h4>
                </div>
                <div class="col-sm-4">
                    <h4>Default calendar view : <select class="defaultCalView"><option value="month">--</option><option value="month">Month</option><option value="basicWeek">Week</option><option value="basicDay">Day</option></select></h4>
                </div>
            </form>
        </div>
        <div class="row">
            <small id="saved-small" style="color: #fff">Saved!</small>
        </div>
        <div class="row">
            <h2>Employee Table</h2>
        </div><br/>
        {$employeeTable}
        <div class="row">
            <small>Password reset column symbols: </small> 
        </div>
        <div class="row">
            <span class="glyphicon glyphicon-ok"></span><small> = they can reset their password</small>
        </div>
        <div class="row">
            <span class="glyphicon glyphicon-minus"></span><small> = you have already reset it</small>
        </div>
        <div class="row">
            <h2>Notification Table</h2>
        </div><br/>
        {$notificationTable}
        {$loginTable}
    <button id="back" class="btn vermillion-bg btn-md pull-right">Back</button>
    <button id="blacklist" class="btn vermillion-bg btn-md pull-left">Blacklist</button>
    </div>
    <script>
        $(document).ready(function() {
            $('#saved-small').hide();
            $('#back').click(function() {
                window.history.back(-1);
            });
            $('#blacklist').click(function() {
                window.location.href = "blacklist-request.php";
            });
            $('#weatherShow').click(function() {
              var val;
              var companyID = '{$companyID}';
              if ($('#weatherShow').prop('checked') == true) {
                  val = 1;
              } else {
                  val = 0;
              }
              $.ajax({
                type: "POST",
                url: "db/weatherShow.php",
                data: {val: val, companyID: companyID},
                success: function(result){
                    if (result) {
                        $('#saved-small').show().delay(1500).fadeOut();
                    } else {
                        console.log("Query: " + result);
                    }
                }
            });
          });
          $('.defaultCalView').change(function() {
              var val = $('.defaultCalView').val();
              var companyID = '{$companyID}';
              $.ajax({
                type: "POST",
                url: "db/defaultCalViewChange.php",
                data: {val: val, companyID: companyID},
                success: function(result){
                    if (result) {
                        $('#saved-small').show().delay(1500).fadeOut();
                    } else {
                        console.log("Query: " + result);
                    }
                }
            });
          });
          $('.deleteUser').click(function() {
              var val = $(this).val();
              var companyID = '{$companyID}';
              var com = confirm('Are you sure you want to delete this user permanently?');
              if (com) {
                  $.ajax({
                    type: "POST",
                    url: "db/deleteUser.php",
                    data: {val: val, companyID: companyID},
                    success: function(result){
                        if (result) {
                            location.reload();
                        } else {
                            console.log("Query: " + result);
                        }
                    }
                });
              }
          });
          $('.resetUser').click(function() {
              var val = $(this).val();
              var companyID = '{$companyID}';
              var com = confirm('Are you sure you want to reset password for this user?');
              if (com) {
                  $.ajax({
                    type: "POST",
                    url: "db/canResetChange.php",
                    data: {val: val, companyID: companyID},
                    success: function(result){
                        if (result) {
                            location.reload();
                        } else {
                            console.log("Query: " + result);
                        }
                    }
                });
              }
          });
        });
    </script>
  </body>
</html>
HTML;
?>