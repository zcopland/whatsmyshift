<?php
session_start();
include_once("analyticstracking.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Password Reset</title>
	<!-- Start of Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- End of Bootsrap -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="container">
		<h1>Password Reset</h1><br/>

<!-- Password Alert -->
<?php
if (isset($_SESSION['alert-message']) && $_SESSION['alert-message'] == true) {
	echo <<<HTML
	<div class="alert alert-warning alert-dismissable">
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
				<input type="text" name="email" id="email" class="form-control" required="true" placeholder="john@doe.com" />
			</div><br/>
			<div class="input-group">
				<label for="phone"><p class="asterix">* </p>Phone Number:</label>
				<input type="text" name="phone" id="phone" class="form-control" required="true" placeholder="5551234567" />
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
			<button name="submit" value="submit" type="submit" class="btn btn-success btn-md">Reset</button>
		</form>
		<button class="btn vermillion-bg btn-md pull-right"><a href="index.php" class="white-text">Back</a></button>
	</div>
</body>
</html>