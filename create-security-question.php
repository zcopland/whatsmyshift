<?php
session_start();
include 'db/dbh_readOnly.php';
include 'Account.php';
include_once("analyticstracking.php");

/* Variables */
$Account = new Account($_SESSION['id'], $conn_readOnly);
$securityQuestion = $Account->getSecurityQuestion();
$securityAnswer = $Account->getSecurityAnswer();
$username = $Account->getUsername();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Create Security Question</title>
	<?php include 'includes/header.php'; ?>
</head>
<body>
    <script src="characterHandling.js"></script>
	<div class="container">
    	<div id="signUpDiv" class="text-center grey-background center">
		<h1 class="text-center vermillion-color">Create Security Question</h1><br/>
		<form action="db/db-create-security.php" method="POST">
			<div id="restOfForm">
    			<div class="input-group center signUpInput">
    				<label for="username" class="white-text"><p class="asterix">* </p>Username:</label>
    				<input type="text" name="username" id="username" class="form-control" value=<?php echo $username; ?> readonly="true"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="securityQuestion" class="white-text"><p class="asterix">* </p>Security Question:</label>
    				<select id="securityQuestion" name="securityQuestion" class="form-control" required="true">
        				<option value="What was your high school mascot?">What was your high school mascot?</option>
        				<option value="What was the name of your first pet?">What was the name of your first pet?</option>
        				<option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
        				<option value="What was your favorite sport in high school?">What was your favorite sport in high school?</option>
    				</select>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="securityAnswer" class="white-text"><p class="asterix">* </p>Security Answer:</label>
    				<input type="text" name="securityAnswer" id="securityAnswer" class="form-control" placeholder="" required="true" onkeyup="someSymbols(this)"/>
    			</div>
    			<br />
    			<small class="asterix">* fields are required.</small>
    			<br/><br/>
            	<button name="submit" value="submit" type="submit" id="submitbtn" class="btn btn-success btn-md">Update</button>
			</div>
		</form>
    	</div>
		<button class="btn vermillion-bg btn-md pull-right white-text" id="back">Back</button>
	</div>
	<script>
    	$(document).ready(function() {
            
        });
	</script>
</body>
</html>