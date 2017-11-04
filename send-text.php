<?php
session_start();

include 'db/dbh_readOnly.php';
include_once("analyticstracking.php");

/* Globals */
$isAdmin = $_SESSION['isAdmin'];
$companyID = $_SESSION['companyID'];
$username = $_SESSION['username'];

if (!isset($_SESSION['isAdmin'])  || $isAdmin != 1) {
    header('Location: main.php');
}

//TODO - add id inside checklist so it's easier to find employeen -- DONE
//TODO - fix 'your message will read'
//TODO - update --Windham Mall on 'your message will read'



?>
<!DOCTYPE html>
<html>
<head>
	<title>Notification Blast</title>
	<!-- Start of Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- End of Bootsrap -->
    <link rel= "stylesheet" type= "text/css" href= "styles.css">
</head>
<body>
	<div class="container">
		<h1>Notification Details</h1><br/>
		<form method="POST" action="sendnotifications.php">
			<div class="input-group">
				<label for="message" class="white-text"><p class="asterix">* </p>Message:</label><br/>
				<textarea type="text" name="message" id="message" required="true" rows="5" cols="50" placeholder="Schedule change (see below)"></textarea>
			</div>
			<br/>
			<label class="white-text"><p class="asterix">* </p>Who would you like to notify?</label>
			<div class="radio">
			  <label><input type="radio" name="for-who" class="for-who" value="all" id="all" required="true">All Employees</label>
			</div>
			<div class="radio">
			  <label><input type="radio" name="for-who" class="for-who" value="specific" id="specific">Specific Employee(s)</label>
			</div><br/>
			<div id="check-employ">
<?php
  $query = "SELECT * FROM employees WHERE companyID='{$companyID}';";
  $result = mysqli_query($conn_readOnly, $query);
	while ($row = mysqli_fetch_assoc($result)) { 
		echo <<<TEXT
<div class="checkbox">
 <label><input type="checkbox" id="{$row['firstName']}_check" name="check_employees[]" class="check" value="{$row['id']}">{$row['firstName']} {$row['lastName']}</label>
</div>
TEXT;
    }
  
?>
			</div>
			<label class="white-text"><p class="asterix">* </p>How would you like to notify employees?</label>
			<div class="radio">
			  <label><input type="radio" name="how-update" class="how-update" value="both" id="both" required="true">Text &amp; Email</label>
			</div>
			<div class="radio">
			  <label><input type="radio" name="how-update" class="how-update" value="text" id="text">Text only</label>
			</div>
			<div class="radio">
			  <label><input type="radio" name="how-update" class="how-update" value="email" id="email">Email only</label>
			</div>
			<br/><br/>
        	<button name="submit" value="submit" type="submit" class="btn btn-success btn-lg">Notify!</button>
		</form><br/>
		<div class="container col-sm-offset-6" style="position: fixed; top: 90px;"><h3 class="white-text">Your message will read:</h3><p>Hello NAME,</p><p id="messageBody"></p><p>--Sent from Windham Mall</p></div>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
	  //initially hide the employee select options
      $("#check-employ").hide();
      //if the radio buttons are clicked, run this function
      $('.for-who').click(function(){
        if($(this).val()=='specific'){
        	//show the select options
            $('#check-employ').show();
            //check nothing off
            $('#check-employ .check').prop("checked", false);
        } else {
        	//hide the select options
            $('#check-employ').hide();
            //check everything off
            $('#check-employ .check').prop("checked", true);
        }
      });
      $('#message').keyup(function() {
      	$('#messageBody').text($(this).val());
      });
	});
	</script>
</body>
</html>