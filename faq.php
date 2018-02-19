<?php
include_once("analyticstracking.php");
?>
<!DOCTYPE html>
  <head>
    <title>FAQ</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="http://vjs.zencdn.net/6.2.8/video-js.css" rel="stylesheet">
  </head>
  <body>
      <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <h1 class="vermillion-color">Frequently Asked Questions</h1><hr><br/>
        <div class="row">
            <video id="my-video" class="video-js" controls preload="auto" width="550" height="275"
  poster="media/AdminTutorialPreview.png" data-setup="{}">
            <source src="media/WhatsMyShift%20Tutorial%20Administrator.mp4" type='video/mp4'>
            <source src="media/WhatsMyShift_Tutorial_Administrator.webm" type='video/webm'>
            <p class="vjs-no-js">
              To view this video please enable JavaScript.
            </p>
          </video>
        </div>
        <div class="row">
            <h4><b>How do I sign up?</b></h4>
            <h5>It is easy for administrators and employees to sign up! Take a look at either the administrator or employee tutorial videos.</h5>
        </div>
        <div class="row">
            <h4><b>How do I reset my password?</b></h4>
            <h5>If you are an employee, contact your administrator and they will allow you to reset your password. After they have reset your password, go to the home page and click "I forgot my password" below the login screen. If you are an administrator, please send an email <a href="mailto:zcopland16@gmail.com?subject=WhatsMyShift Admin Password Reset">here</a> to reset your password.</h5>
        </div>
        <div class="row">
            <h4><b>How will I know when my schedule changes?</b></h4>
            <h5>Your administrator has the ability to text or email you once they make changes to your schedule or if they have any announcements to make.</h5>
        </div>
        <div class="row">
            <h4><b>How do I delete a shift?</b></h4>
            <h5>Click on the shift and drag it and drop it over the trash bin on the far left of the page.</h5>
        </div>
        <div class="row">
            <h4><b>Does it cost any money to sign up as an employee?</b></h4>
            <h5>Signing up as an employee is 100% free.</h5>
        </div>
        <div class="row">
            <h4><b>How do I know my Company ID?</b></h4>
            <h5>When signing up, your administrator should tell you what the Company ID is. If they have forgotten, they can login and view their admin panel and see what it is.</h5>
        </div>
        <div class="row">
            <h4><b>How old do my employees have to be to sign up?</b></h4>
            <h5>To use this service, all users must be 16 years or older, per our <a href="terms-and-conditions.html">Terms and Conditions</a>.</h5>
        </div>
        <div class="row">
            <h4><b>How do I see my employee's login times?</b></h4>
            <h5>All login attempts are stored in a database. To view specific login attempts, contact the <a href="mailto:zcopland16@gmail.com?subject=WhatsMyShift Login Attempts">site administrator</a>.</h5>
        </div>
        <div class="row">
            <h4><b>How do I prevent an (ex) employee from creating an account?</b></h4>
            <h5>Employers are able to blacklist a specific user from creating an account with their organization. To submit a blacklist, click <a href="blacklist-request.php">here</a>.</h5>
        </div>
    </div>
    <script type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            
        });
    </script>
    <script src="http://vjs.zencdn.net/6.2.8/video.js"></script>
  </body>
</html>