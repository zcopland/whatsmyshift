<?php
include_once("analyticstracking.php");
?>
<!DOCTYPE html>
  <head>
    <title>Pricing</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
      <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <h2 class="vermillion-color">Pricing Tier</h2><br/>
        <table class="table">
            <tr>
                <th>Employees</th>
                <th>1-15</th>
                <th>16-30</th>
                <th>31-50</th>
                <th>50+</th>
            </tr>
            <tr>
                <td>Monthly</td>
                <td>$15</td>
                <td>$30</td>
                <td>$60</td>
                <td>$90</td>
            </tr>
            <tr>
                <td>Yearly</td>
                <td>$200</td>
                <td>$390</td>
                <td>$750</td>
                <td>$1,100</td>
            </tr>
            <tr>
                <td>Per text</td>
                <td>$0.25</td>
                <td>$0.25</td>
                <td>$0.25</td>
                <td>$0.25</td>
            </tr>
            <tr>
                <td>Per email</td>
                <td>$0.15</td>
                <td>$0.15</td>
                <td>$0.15</td>
                <td>$0.15</td>
            </tr>
            <tr>
                <td>Admins</td>
                <td colspan="4">2 free; any additional are $5 each.</td>
            </tr>
            <tr>
                <td>Technical support</td>
                <td colspan="4">first 15 days free; $15/hr after.</td>
            </tr>
        </table>
        <br/>
        <h2 class="vermillion-color">Use</h2>
        <p>Use this web application to schedule your employees from your desk or while you're on the go on your laptop. This web application is made for schedules that are constantly changing or static schedules.</p>
        <p>Need to make a last minute change to the schedule? No problem! You have the ability to alert your employees via text or email of your change.</p>
        <p>The admin panel allows administrators to see login times and monitor each employee.</p>
        <div class="row">
            <button class="btn btn-sm vermillion-bg white-text" id="inquire-btn">Inquire</button>
        </div>
    </div>
    <script type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('#inquire-btn').click(function() {
                location.href = 'employer-inquiry.php';
            });
        });
    </script>
  </body>
</html>