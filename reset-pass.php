<?php

/*
    reset-pass.php
    
    This PHP file is used for the front-end 
    for users to reset their password.
*/

session_start();
include_once("analyticstracking.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Password Reset</title>
	<?php include 'includes/header.php'; ?>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <script src="characterHandling.js"></script>
	<div class="container">
		<h1>Password Reset</h1><br/>
		<small>*Make sure you have gotten permission from your administrator to reset your password.</small><br/>

<!-- Password Alert -->
<?php
if (isset($_SESSION['alert-message']) && $_SESSION['alert-message'] == true) {
	echo <<<HTML
	<div class="alert alert-warning alert-dismissable" id="alert-reset">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		<strong>Warning!</strong> {$_SESSION['alert-message']}
	</div>
HTML;
}
?>
<!-- Password Alert -->

		<form method="POST" id="resetForm" action="db/reset.php">
			<div class="input-group">
				<label for="username"><p class="asterix">* </p>Username:</label>
				<input type="text" name="username" class="form-control" id="username" autofocus="true" required="true" onkeyup="someSymbols(this)" />
			</div><br/>
			<div class="input-group">
				<label for="answer"><p class="asterix">* </p>Question: <span id="securityQuestion"></span></label>
				<input type="text" name="securityAnswer" id="securityAnswer" class="inputResize" required="true" placeholder="" />
				<span class="help-block">* Case sensitive</span>
			</div>
			<div class="input-group">
				<label for="password1"><p class="asterix">* </p>Password:</label>
				<input type="password" name="password1" class="form-control" id="password1" required="true" />
			</div><br/>
			<div class="input-group">
				<label for="password2"><p class="asterix">* </p>Re-type Password:</label>
				<input type="password" name="password2" class="inputResize" id="password2" required="true" />
			</div>
			<br/><br/>
			<button name="submit" value="submit" type="submit" class="btn btn-success btn-md" id="resetButton">Reset</button>
		</form>
		<button class="btn vermillion-bg btn-md pull-right"><a href="index.php" class="white-text">Back</a></button>
	</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        $('#resetButton').hide();
        //Username input has focused out
        $('#username').focusout(function(){
            if ($(this).val().length >= 4) {
                var username = document.getElementById('username').value;
                //Check to make sure they can reset their password
                $.ajax({type: "POST", url: "db/check-can-reset.php", data: {username: username}, success: function(result) {
                    if (result) {
                        //$('#resetButton').show();
                        $('#alert-reset').hide(400);
                    } else {
                        $('#resetButton').hide();
                        $('#alert-reset').show(400);
                    }
                }});
                //Get their security question
                $.ajax({type: "POST", url: "db/check-security-info.php", data: {username: username, mode: 'question'}, success: function(result) {
                    $('#securityQuestion').text(result);
                }, error: function() {
                    alert('Could not find security question!');
                }
                });
            }
        });
        //Check their security answer
        $('#securityAnswer').focusout(function(){
            var username = document.getElementById('username').value;
            if ($(this).val().length >= 2 && username.length >= 4) {
                var securityAnswer_guess = $(this).val();
                $.ajax({type: "POST", url: "db/check-security-info.php", data: {username: username, securityAnswer_guess: securityAnswer_guess, mode: 'answer'}, success: function(result) {
                    if (result) {
                        $('#resetButton').show();
                    } else {
                        $('#resetButton').hide();
                    }
                }});
            }
        });
        //Prevent the user from submitting the form on enter
        $('#resetForm').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) { 
            e.preventDefault();
            return false;
          }
        });
    });
</script>
</html>