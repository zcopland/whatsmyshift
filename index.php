<?php

/*
    index.php
    
    This PHP file is used for the front-end 
    of the first page the user is directed
    to, which is also the login page.
*/

session_start(); //starting session
//setting variable to false until they log in
$_SESSION['id'] = '';
$_SESSION['username'] = '';
date_default_timezone_set('America/New_York');
include_once("analyticstracking.php");

//see if they are already logged in
//if so, bring them to the main page
if (isset($_SESSION['mall'])  || !empty($_SESSION['mall'])) {
    header('Location: main.php');
}

include 'db/dbh_readOnly.php';

/* Site Under Construction Variable */
$query = "SELECT * FROM underConstruction WHERE id=1";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
   $val =  $row['value'];
}
if ($val == 1) {
    header('Location: maintenance.php');
} else if ($val == 0) {
    $underConstruction = false;
}

//Display the version of the site from DB
$sql = "SELECT * FROM versions ORDER BY id DESC";
$result = mysqli_query($conn_readOnly, $sql);
$index = 0;

/* Current Version Variable */
$currentVersion = '';

while ($row = mysqli_fetch_assoc($result)) {
    if ($index == 0) {
        $currentVersion = $row['version'];
    }
    $index++;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>What's My Shift</title>
	<?php include 'includes/header.php'; ?>
	<script type="text/javascript" src="script.js"></script>
	<script type="text/javascript" src="characterHandling.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div id="snow">
	</br></br>
    <h1 class="text-center">Please Log in</h1><br/><br/>
    <div id="wrapper">
        <div id="content" class="container">
            <div id="loginDiv" class="text-center grey-background center">
                <h2 class="text-center vermillion-color" id="credentials">Credentials</h2>
<!-- Password Alert -->
<?php
if (isset($_SESSION['pass-alert-index']) && $_SESSION['pass-alert-index'] == true) {
    echo <<<HTML
    <div class="alert alert-warning alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Warning!</strong> Password incorrect.
    </div>
HTML;
}
?>
      <form method="POST" class="loginForm" action="db/login.php">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="username" type="text" class="form-control" name="username" placeholder="Username" autofocus="true" required="true" onkeyup="someSymbols(this)">
        </div><br/>
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required="true">
        </div><br/>
        <div>
          <input type="checkbox" name="remember" id="remember" value="1" checked="true"/> <label for="remember" class="vermillion-color">Remember me</label>
      </div><br/>
        <button name="submit" value="submit" type="submit" class="btn btn-primary btn-md">Log in</button>
      </form><br/>
      <a href="create-account.php" class="vermillion-color">Don't have an account?</a>
      <br/><br/>
      <a href="reset-pass.php" class="vermillion-color">I forgot my password</a>
      <br/><br/>
      </div></div>
      <div id="footer">
          <p class="text-center">This site uses cookies to stay logged in.</p>
          <p class="text-center">Copyright Zach Copland <?php echo date("Y"); ?>. Version: <?php echo $currentVersion; ?></p>
      </div></div></div>
<?php
//check if cookies are set
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    echo <<<HTML
<script>
  document.getElementById('username').value = '{$username}';
  document.getElementById('password').value = '{$password}';
  document.getElementById('remember').checked = true;
</script>
HTML;
}
?>      
</body>
</html>