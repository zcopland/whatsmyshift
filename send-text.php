<?php
session_start();

include 'db/dbh_readOnly.php';
include 'Account.php';
include_once("analyticstracking.php");

/* Globals */
$Account = new Account($_SESSION['id'], $conn_readOnly);
$isAdmin = $_SESSION['isAdmin'];
$companyID = $_SESSION['companyID'];
$username = $_SESSION['username'];
$tableInfo = '';
$organization = $Account->getOrganization();

if (!isset($_SESSION['isAdmin'])  || $isAdmin != 1) {
    header('Location: main.php');
}

$query = "SELECT * FROM employees WHERE companyID='{$companyID}';";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) { 
    $tableInfo .= <<<TEXT
<div class="checkbox">
 <label><input type="checkbox" id="{$row['firstName']}_check" name="check_employees[]" class="check" value="{$row['id']}">{$row['firstName']} {$row['lastName']}</label>
</div>
TEXT;
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Notification Blast</title>
	<?php include 'includes/header.php'; ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
	<div class="container">
    	<div class="row">
        	<h1>Notification Details</h1>
    	</div>
		<form method="POST" action="sendnotifications.php">
			<div class="input-group">
				<label for="message" class="white-text"><p class="asterix">* </p>Message:</label><br/>
				<textarea type="text" name="message" id="message" required="true" rows="5" cols="50" placeholder="There has been a shift change for next Thursday, please view the online schedule."></textarea>
			</div>
			<div class="row">
    			<div class="col-sm-4">
        			<p>Character count: <span id="countSpan">0</span> / 255</p>
    			</div>
			</div><br/>
			<div class="row">
    			<div class="col-sm-4" style="border-style: double; border-color: #DFDCE3;">
        			<h3 class="white-text">Your message will read:</h3><p>Hello NAME,</p><p id="messageBody"></p><p>--Sent from <?php echo $organization ?></p>
    			</div>
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
    			<?php echo $tableInfo ?>
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
			<br/>
			<?php //include 'includes/reCAPTCHA.php'; ?>
			<br/>
        	<button name="submit" value="submit" type="submit" class="btn btn-success btn-lg">Notify!</button>
		</form><br/>
		<button class="btn vermillion-bg btn-md pull-right white-text" id="back">Back</button>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
	  //initially hide the employee select options
      $("#check-employ").hide();
      var max = 254;
      $('#message').keyup(function(e) {
          var count = $('#message').val().length;
          console.log(count);
          $('#countSpan').text(count);
          if (this.value.length == max) {
              e.preventDefault();
          } else if (this.value.length > max) {
              // Maximum exceeded
              this.value = this.value.substring(0, max);
          }
      });
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
      $('#back').click(function() {
        window.history.back(-1);
      });
	});
	</script>
</body>
</html>