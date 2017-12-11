<?php
session_start();

include 'db/dbh_readOnly.php';

/* Globals */
$username = $_SESSION['username'];
$tableInfo = '';
$organization = "What's My Shift";

if (!isset($username)  || empty($username)) {
    header('Location: index.php');
}

$query = "SELECT * FROM `employees` ORDER BY `organization`;";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) { 
    $role = 'Employee';
    if ($row['isAdmin']) {
        $role = 'Admin';
    }
    $tableInfo .= <<<TEXT
<div class="checkbox">
 <label><input type="checkbox" id="{$row['firstName']}_check" name="check_employees[]" class="check" value="{$row['id']}">{$row['firstName']} {$row['lastName']} - {$role} @ {$row['organization']}</label>
</div>
TEXT;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Root Notification Blast</title>
	<?php include 'includes/header.php'; ?>
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
			<input type="hidden" name="role" value="root"/>
			<br/><br/>
        	<button name="submit" value="submit" type="submit" class="btn btn-success btn-lg">Notify!</button>
		</form><br/>
		<button class="btn vermillion-bg btn-md pull-right white-text" id="back">Back</button>
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
      $('#back').click(function() {
        window.history.back(-1);
      });
	});
	</script>
</body>
</html>