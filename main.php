<?php

/*
    main.php
    
    This PHP file is used for the front-end 
    of the main page where users can see the
    schedule.
*/

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
$adminName = $Account->getAdminName($companyID, $conn_readOnly);
$adminEmail = $Account->getAdminEmail($companyID, $conn_readOnly);
$lastLogin = $Account->getLastLogin();
$securityQuestion = $Account->getSecurityQuestion();
$securityAnswer = $Account->getSecurityAnswer();
$zip = $Account->getZip($companyID, $conn_readOnly);
$defaultCalView = $Account->getDefaultCalView($companyID, $conn_readOnly);
$weatherShow = $Account->getWeatherShow($companyID ,$conn_readOnly);
$booleanAgreeTC = $Account->getBooleanAgreeTC($conn_readOnly);

//if username is not set, send them back to login page
if (!isset($_SESSION['username'])  || empty($_SESSION['username'])) {
    header('Location: index.php');
}
//If root is trying to log in, redirect
if ($username == 'root') {
    header('Location: root-panel.php');
}

//if they do not have a security question/answer redirect
//remove after everyone has created one
if (empty($securityQuestion) || empty($securityAnswer)) {
    header('Location: create-security-question.php');
}

/* Site Under Construction Variable */
$query = "SELECT * FROM underConstruction WHERE id=1";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
   $val = $row['value'];
}
if ($val == 1) {
    $underConstruction = true;
} else if ($val == 0) {
    $underConstruction = false;
}
//Get the current version of site from DB
$sql = "SELECT * FROM versions ORDER BY id DESC";
$result = mysqli_query($conn_readOnly, $sql);
$index = 0;

/* Current Version Variable */
$VERSION = '';

while ($row = mysqli_fetch_assoc($result)) {
    if ($index == 0) {
        $VERSION = $row['version'];
    }
    $index++;
}

$date = (String) date("Y-m-d");

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $organization; ?> Schedule</title>
	<?php include 'includes/header.php'; ?>
    <!-- Start of FullCalendar -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.min.css'/>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src='fullcalendar/moment.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.min.js'></script>
	<!-- End of FullCalendar -->
	<script type="text/javascript" src="script.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.simpleWeather/3.1.0/jquery.simpleWeather.min.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" type="text/css" href="main-styles.css">
	<script type="text/javascript">var userip;</script>
	<script type="text/javascript" src="https://l2.io/ip.js?var=userip"></script>
</head>
<body>
    <!--button id="refresh-btn" onclick="location.reload(true);">Refresh</button-->
<?php if ($underConstruction): ?>
    <!-------- SITE UNDER CONSTRUCTION ALERT -------->
    <div class="alert alert-warning alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Warning!</strong> Site is currently being worked on
    </div>
    <script>alert("Warning! Site is currently being worked on.");</script>
    <!-------- SITE UNDER CONSTRUCTION ALERT -------->
<?php endif; ?>
    <div class="container">
    <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title vermillion-color">Attention</h4>
            </div>
            <div class="modal-body">
              <p id="modal-text" class="vermillion-color">The <a href="terms-and-conditions.html">Terms and Conditions</a> have been updated. Please accept to access your schedule.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss">Dismiss</button>
              <button type="button" class="btn btn-success" data-dismiss="modal" id="accept">Accept</button>
            </div>
          </div>
          
        </div>
      </div>
    </div>
      <!-- /Modal-->

<!-- CALENDAR DIV -->
	<?php if (isset($_SESSION['organization'])) { ?>
    <h1 class="text-center"><?php echo $_SESSION['organization']; ?> Schedule</h1>
    <?php } ?>
    <div id='wrap'>
<?php 
echo <<<HTML
<input id="firstName" type="hidden" value="{$firstName}" />
<input id="lastName" type="hidden" value="{$lastName}" />
<input id="username" type="hidden" value="{$username}" />
<input id="email" type="hidden" value="{$email}" />
<input id="phone" type="hidden" value="{$phone}" />
<input id="lastLogin" type="hidden" value="{$lastLogin}" />
<div class="isAdmin" style="display: none;">{$isAdmin}</div>
<input id="organization" type="hidden" value="{$organization}" />
<input id="companyID" type="hidden" value="{$companyID}" />
<input id="adminName" type="hidden" value="{$adminName}" />
<input id="adminEmail" type="hidden" value="{$adminEmail}" />
<input id="zip" type="hidden" value="{$zip}" />
<input id="weatherShow" type="hidden" value="{$weatherShow}" />
<input id="defaultCalView" type="hidden" value="{$defaultCalView}" />
<input id='date' type='hidden' value='{$date}'/>
<input id='booleanAgreeTC' type='hidden' value='{$booleanAgreeTC}'/>
HTML;
//If the user is an admin, display editing tools
if ($isAdmin) {
	echo <<<HTML
		<div id='external-events'>
			<h4 class="drag-label">Drag a new shift</h4>
			<div class='fc-event'>New Shift</div>
			<p>
				<img src="media/trash.png" title="Drag a shift and drop to delete" style="width:43px;height:55px;" id="trash" alt="">
			</p><br/><br/>
			<div class="notifyDiv"><button class="btn vermillion-bg text-left notify"><a class="white-text" href="send-text.php">Notify</a></button></div>
			<div class="notifyDiv"><button class="btn vermillion-bg text-left notify"><a class="white-text" href="admin-panel.php">Panel</a></button></div><br/>
			<div class="delete-group">
		    <h4 class="vermillion-color">Archive shifts older than:</h4>
		    <select id="delete-month" name="delete-month" class="blue-color">
		      <option value="null">--</option>
		      <option value="30">1 month</option>
		      <option value="60">2 months</option>
		      <option value="90">3 months</option>
		    </select><br/><br/>
		    <button id="deleteBtn" class="btn-sm btn-danger">Archive</button>
            </div>
		</div>
		<div id="weather"></div>
		<div id='wrap-admin' class="text-center"><div id='calendar' class='admin-calendar'></div></div>
HTML;
} else {
    echo "<div id='wrap-reg' class='text-center'><div id='calendar' class='reg-calendar'></div></div>";
}
?>
		
		<div style='clear:both'></div>
	</div>
<!-- PHP FOR NON-ADMINS -->
<?php 
if (!$isAdmin) {
	echo <<<HTML
<br/>
<div class="container">
	<button class="btn pull-left vermillion-bg"><a class="white-text" href="mailto:zcopland16@gmail.com?subject=WhatsMyShift">Contact Support</a></button>
	<button class="btn pull-right vermillion-bg"><a class="white-text" href="mailto:{$adminEmail}">Contact {$adminName}</a></button>
</div>
HTML;
}
?>
<!-- END OF PHP FOR NON-ADMINS -->


<!-- PHP FOR ADMIN STUFF -->
<?php
if ($isAdmin) {
    echo <<<HTML
<div class="container">
    <button class="btn pull-left vermillion-bg" id="admin-panel-button-mobile"><a class="white-text" href="admin-panel.php">Panel</a></button>
    <button class="btn pull-right vermillion-bg" id="notify-button-mobile"><a class="white-text" href="send-text.php">Notify</a></button>
</div>
HTML;
} 
?>
<!-- END OF PHP FOR ADMIN STUFF -->
<br/>
<button class="btn vermillion-bg" id="logout" onclick="logout();">Logout</button>
<footer class="text-center">Copyright Zach Copland <?php echo date("Y"); ?>. Version: <?php echo $VERSION; ?></footer>
<script type="text/javascript">
$(document).ready(function() {
  $('#employee-list').hide();
  var viewportWidth = $(window).width();
  var booleanAgreeTC = $('#booleanAgreeTC').val();
  if (viewportWidth <= 1279) {
      $('#admin-panel-button-mobile').show();
      $('#notify-button-mobile').show();
  } else {
      $('#admin-panel-button-mobile').hide();
      $('#notify-button-mobile').hide();
  }
  //Action for the deleting batch shifts button
  $('#deleteBtn').click(function() {
      var val = $('#delete-month').val();
      var companyID = $('#companyID').val();
      if (val != 'null') {
          if (confirm("Are you sure you want to archive shifts older than " + val + " days?") == true) {
            $.ajax({
                type: "POST",
                url: "db/delete.php",
                data: {val: val, companyID: companyID},
                success: function(result){
                    if (result) {
                        alert("Success! Shifts older than " + val + " days have been archived!");
                        window.location.href = "main.php";
                    } else {
                        console.log("Query: " + result);
                    }
                }
            });
          } else {$('#delete-month').val("")}
      } else {
          alert("Please select a range to delete from!");
      }
  });
  $('#underConstruction').click(function() {
      var val;
      if ($('#underConstruction').prop('checked') == true) {
          val = 1;
      } else {
          val = 0;
      }
      $.ajax({
        type: "POST",
        url: "db/construction.php",
        data: {val: val},
        success: function(result){
            if (result) {
                console.log("Success!");
                //window.location.href = "main.php";
            } else {
                console.log("Query: " + result);
            }
        }
    });
  });
  $.ajax({
        type: "POST",
        url: "db/check-ip.php",
        data: {ip: userip},
        success: function(result){
            if (!result) {
                window.location.href = "logout.php";
            }
        }
    });
  //Check if the user has agreed to terms & conditions
  if (booleanAgreeTC == 0) {
      $("#myModal").modal();
  }
  $('#myModal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);
      $(this).closest('.modal').one('hidden.bs.modal', function() {
        var username = $('#username').val();
        if ($button[0].id == 'accept') {
            $.ajax({
                type: "POST",
                url: "db/updateAgreeTC.php",
                data: {username: username},
                success: function(result){
                    if (!result) {
                        logout();
                    }
                }
            });
        } else { logout(); }
      });
  });
});
//Logout button
function logout() {
    window.location.href = "logout.php";
}
</script>
</body>
</html>