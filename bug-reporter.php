<!DOCTYPE html>
  <head>
    <title>Bug Reporter</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
      <?php include 'includes/navbar.php'; ?>
      <div class="container">
        <form method="POST" action="send-bug.php">
            <h2>Report a Bug</h2>
            <div class="input-group">
    			<label for="firstName" class="white-text"><p class="asterix">* </p>First Name:</label>
    			<input type="text" name="firstName" id="firstName" class="form-control" placeholder="John" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="lastName" class="white-text"><p class="asterix">* </p>Last Name:</label>
    			<input type="text" name="lastName" id="lasttName" class="form-control" placeholder="Smith" required="true"/>
    		</div>
    		<div class="input-group">
    			<label for="email" class="white-text"><p class="asterix">* </p>Email:</label>
    			<input type="email" name="email" id="email" class="form-control" placeholder="john@doe.org" required="true"/>
    		</div>
            <div class="input-group">
    			<label for="issue_type" class="white-text"><p class="asterix">* </p>Type of issue:</label>
    			<select class="form-control" name="issue_type" id="issue_type" required="true">
                    <option>Bug</option>
                    <option>Change Request</option>
                    <option>Feature Request</option>
                    <option>Task</option>
                </select>
    		</div>
    		<div class="input-group">
    			<label for="platform" class="white-text"><p class="asterix">* </p>Platform:</label>
    			<select class="form-control" name="platform" id="platform" required="true">
                    <option>Mobile (iOS)</option>
                    <option>Mobile (Android)</option>
                    <option>Desktop</option>
                </select>
    		</div>
    		<div class="input-group">
    			<label for="description" class="white-text"><p class="asterix">* </p>Describe the issue:</label> <br/>
    			<textarea id="descption" name="description" cols="35" rows="8" required="true"></textarea>
    		</div>
    		<br/>
    		<button type="submit" class="btn btn-med btn-success">Send</button>
        </form>
        <br/>
        <button id="back" class="btn vermillion-bg btn-md pull-right">Back</button>
      </div>
  </body>
  <script>
      $(document).ready(function() {
            $('#back').click(function() {
                window.history.back(-1);
            });
      });
  </script>
</html>