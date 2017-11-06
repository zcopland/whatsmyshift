<!DOCTYPE html>
  <head>
    <title>Employer Inquiry</title>
    <meta charset='utf-8' />
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
        <form method="POST" action="send-inquiry.php">
            <h2>Sender information</h2>
            <div class="input-group">
    			<label for="firstName" class="white-text"><p class="asterix">* </p>First Name:</label>
    			<input type="text" name="firstName" id="firstName" class="form-control" placeholder="John" required="true" />
    		</div>
    		<div class="input-group">
    			<label for="lastName" class="white-text"><p class="asterix">* </p>Last Name:</label>
    			<input type="text" name="lastName" id="lasttName" class="form-control" placeholder="Smith" required="true" />
    		</div>
    		<div class="input-group">
    			<label for="email" class="white-text"><p class="asterix" id="asterix-email">* </p>Email:</label>
    			<input type="email" name="email" id="email" class="form-control" placeholder="john@doe.org"/>
    		</div>
            <div class="input-group">
    			<label for="employeeNumber" class="white-text">Employees:</label>
    			<input type="text" name="employeeNumber" id="employeeNumber" class="form-control" placeholder="7" />
    		</div>
    		<div class="input-group">
    			<label for="inquery" class="white-text"><p class="asterix">* </p>Inquiry:</label> <br/>
    			<textarea id="inquiry" name="inquiry" cols="35" rows="8"></textarea>
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