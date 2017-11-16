<?php
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
	<div class="container">
		<h1>Password Reset</h1>
		<small>*Make sure you have gotten permission from your administrator to reset your password.</small><br/><br/>

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

		<form method="POST" action="db/reset.php">
			<div class="input-group">
				<label for="username"><p class="asterix">* </p>Username:</label>
				<input type="text" name="username" class="form-control" id="username" autofocus="true" required="true" />
			</div><br/>
			<div class="input-group">
				<label for="email"><p class="asterix">* </p>Email:</label>
				<input type="text" name="email" id="email" class="form-control" required="true" placeholder="john@doe.org" />
			</div><br/>
			<div class="input-group">
				<label for="phone"><p class="asterix">* </p>Phone Number:</label>
				<input type="text" name="phone" id="phone" class="form-control" required="true" placeholder="2075551234" />
			</div><br/>
			<div class="input-group">
				<label for="password1"><p class="asterix">* </p>Password:</label>
				<input type="password" name="password1" class="form-control" id="password1" required="true" />
			</div><br/>
			<div class="input-group">
				<label for="password2"><p class="asterix">* </p>Re-type Password:</label>
				<input type="password" name="password2" class="form-control" id="password2" required="true" />
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
        $('#username').focusout(function(){
            if ($(this).val().length >= 4) {
                var username = document.getElementById('username').value;
                $.ajax({type: "POST", url: "db/check-can-reset.php", data: {username: username}, success: function(result) {
                    if (result) {
                        $('#resetButton').show();
                        $('#alert-reset').hide(400);
                    } else {
                        $('#resetButton').hide();
                        $('#alert-reset').show(400);
                    }
                }});
            }
        });
    });
</script>
</html>