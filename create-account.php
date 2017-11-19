<?php 
/* Site Under Construction Variable */
$underConstruction = false;
include_once("analyticstracking.php");

?>


<!DOCTYPE html>
<html>
<head>
	<title>Create Account</title>
	<?php include 'includes/header.php'; ?>
</head>
<body>
    <script src="characterHandling.js"></script>
<?php if ($underConstruction): ?>
    <!-------- SITE UNDER CONSTRUCTION ALERT -------->
    <div class="alert alert-warning alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Warning!</strong> This page is currently under construction
    </div>
    <!-------- SITE UNDER CONSTRUCTION ALERT -------->
<?php endif; ?>
	<div class="container">
        <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title vermillion-color">Attention</h4>
            </div>
            <div class="modal-body">
              <p id="modal-text" class="vermillion-color">TEXT</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
            </div>
          </div>
          
        </div>
      </div>
      <!-- /Modal-->
    	<div id="signUpDiv" class="text-center grey-background center">
		<h1 class="text-center vermillion-color">Create an account</h1><br/>
		<form action="db/signup.php" method="POST">
    		<div class="input-group center signUpInput">
				<label class="white-text"><p class="asterix">* </p>Role:</label><br/>
				<input type="radio" name="role" id="adminRole" value="admin" class="" required="true"/>
				<label for="adminRole" class="white-text" style="padding-right:10px; padding-left:5px;">Admin</label>
				<input type="radio" name="role" id="regularRole" value="regular" class=""/>
				<label for="regularRole" class="white-text" style="padding-left:5px;">Employee</label>
			</div>
			<div id="restOfForm">
    			<div class="input-group center signUpInput">
    				<label for="firstName" class="white-text"><p class="asterix">* </p>First Name:</label>
    				<input type="text" name="firstName" id="firstName" class="form-control" placeholder="John" required="true" onkeyup="lettersOnly(this)"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="lastName" class="white-text"><p class="asterix">* </p>Last Name:</label>
    				<input type="text" name="lastName" id="lastName" class="form-control" placeholder="Doe" required="true" onkeyup="lettersOnly(this)"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="username" class="white-text"><p class="asterix">* </p>Username:</label>
    				<input type="text" name="username" id="username" class="form-control" placeholder="johndoe" required="true" onkeyup="someSymbols(this)"/>
    			</div>
    			<div id="username-short" class="vermillion-color"><small>Username is too short!</small></div>
    			<div id="username-taken" class="vermillion-color"><small><img src="media/red-x.png" height="20" width="20" /> Username is taken!</small></div>
    			<div id="username-allowed" class="vermillion-color"><small><img src="media/green-check.png" height="20" width="20" /> Username is available!</small></div>
    			<div class="input-group center signUpInput">
    				<label for="password" class="white-text"><p class="asterix">* </p>Password:</label>
    				<input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required="true"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="email" class="white-text"><p class="asterix" id="asterix-email">* </p>Email:</label>
    				<input type="email" name="email" id="email" class="form-control" placeholder="john@doe.org" onkeyup="emailSymbols(this)"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="phone" class="white-text"><p class="asterix">* </p>Phone Number:</label>
    				<input type="tel" name="phone" id="phone" class="form-control" placeholder="2075551234" required="true" onkeyup="numbersOnly(this)" maxlength="10"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="org" class="white-text"><p class="asterix">* </p>Organization:</label>
    				<input type="text" name="org" id="org" class="form-control" placeholder="" onkeyup="orgSymbols(this)"/>
    			</div>
    			<div class="input-group center signUpInput">
    				<label for="companyID" class="white-text"><p class="asterix">* </p>Company ID:</label>
    				<input type="text" name="companyID" id="companyID" class="form-control" placeholder="" required="true" onkeyup="someSymbols(this)"/>
    			</div>
    			<div class="input-group center signUpInput zipDiv">
    				<label for="zip" class="white-text"><p class="asterix">* </p>Zip Code:</label>
    				<input type="text" pattern="[0-9]{5}" name="zip" id="zip" class="form-control" placeholder="04062"/>
    			</div>
    			<div class="input-group center signUpInput billingDiv">
    				<label for="billing" class="white-text"><p class="asterix">* </p>Billing Cycle:</label>
    				<select id="billing" name="billing" class="form-control" required="true">
        				<option value="Monthly">Monthly</option>
        				<option value="Yearly">Yearly</option>
    				</select>
    			</div>
    			<div class="input-group center signUpInput" id="ver-div">
    				<label for="verification" class="white-text"><p class="asterix">* </p>Verification Code:</label>
    				<input type="text" name="verification" id="verification" class="form-control" readonly="true" placeholder=""/>
    			</div>
    			<div id="ver-not">
                    <div class="alert alert-warning alert-dismissable">
                        <strong>Attention</strong> Verification code is incorrect!
                    </div>
                </div>
    			<br />
    			<small class="asterix">* fields are required.</small>
    			<br/>
    			<small class="white-text">By creating an account, you agree to the <a href="terms-and-conditions.html">Terms and Conditions</a>.</small>
    			<br/><br/>
            	<button name="submit" value="submit" type="submit" id="submitbtn" class="btn btn-success btn-md">Create</button>
			</div>
		</form>
    	</div>
		<button class="btn vermillion-bg btn-md pull-right white-text" id="back">Back</button>
	</div>
	<script src='create-account.js'></script>
	<script>
    	$(document).ready(function() {
            $('#back').click(function() {
                window.history.back(-1);
            });
        });
	</script>
</body>
</html>