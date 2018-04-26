<?php

/*
    blacklist-request.php
    
    This PHP file is used for the front-end 
    for admins to request a user to be 
    blacklisted from the site.
*/

include_once("analyticstracking.php");
?>
<!DOCTYPE html>
  <head>
    <title>Request a blacklist</title>
    <?php include 'includes/header.php'; ?>
  </head>
  <body>
      <?php include 'includes/navbar.php'; ?>
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
        <form method="POST" id="request-blacklist" action="db/blacklist-insert.php" onsubmit="return validate();">
            <h1>Request a blacklist</h1>
            <br/>
            <h3>Your information:</h3>
            <div class="input-group">
    			<label for="username" class="white-text"><p class="asterix">* </p>Username:</label>
    			<input type="text" name="username" id="username" class="form-control" placeholder="johnsmith" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="password" class="white-text"><p class="asterix">* </p>Password:</label>
    			<input type="password" name="password" id="password" class="form-control" placeholder="••••••••••••" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="companyID" class="white-text"><p class="asterix">* </p>Company ID:</label>
    			<input type="text" name="companyID" id="companyID" class="form-control" placeholder="WM" required="true"/>
    		</div>
    		<br/>
    		<button type="button" id="verify" class="btn btn-sm btn-default">Verify</button>
    		<br/>
    		<h3><strong>Their</strong> information:</h3>
            <div class="input-group">
    			<label for="blacklistFirstName" class="white-text"><p class="asterix">* </p>First Name:</label>
    			<input type="text" name="blacklistFirstName" id="blacklistFirstName" class="form-control" placeholder="James" readonly="true" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="blacklistLastName" class="white-text"><p class="asterix">* </p>Last Name:</label>
    			<input type="text" name="blacklistLastName" id="blacklistLastName" class="form-control" placeholder="Doe" readonly="true" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="ip" class="white-text">IP Address:</label>
    			<input type="text" name="ip" id="ip" class="form-control" placeholder="192.0.0.1" readonly="true"/>
    		</div>
    		<div class="input-group">
    			<label for="notes" class="white-text"><p class="asterix">* </p>Notes (reason for blacklist):</label> <br/>
    			<textarea id="notes" name="notes" cols="35" rows="8" readonly="true" required="true"></textarea>
    		</div>
    		<br/>
    		<?php //include 'includes/reCAPTCHA.php'; ?>
    		<button type="submit" class="btn btn-med btn-success">Submit</button>
        </form>
        <br/>
        <button id="back" class="btn vermillion-bg btn-md pull-right">Back</button>
      </div>
  </body>
  <script src="blacklist-request.js"></script>
</html>