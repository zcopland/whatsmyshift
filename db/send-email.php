<?php
require_once '../info/Information.php';
//PHPMailer requirements
date_default_timezone_set('America/New_York');
require '../PHPMailer/PHPMailerAutoload.php';
include 'dbh_readOnly.php';
include 'dbh_addOnly.php';

/* Variables */
$information = new Information();
$adminEmail = $_GET['adminEmail'];
$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$username = $_GET['username'];
$org = $_GET['organization'];
$companyID = $_GET['companyID'];
$date = date("m/d/Y @ g:ia");

/* PHPMailer Code */
//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = $information->getHost();
//enable this if you are using gmail smtp, for mandrill app it is not required
$mail->SMTPSecure = 'tls';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = $information->getUsername();
//Password to use for SMTP authentication
$mail->Password = $information->getPassword();
//Blank to email
$mail->addAddress($adminEmail);

$message = "{$firstName} {$lastName} ({$username}) has just created an account with {$org}!";

//Set who the message is to be sent from
$mail->setFrom('notify@whatshift.com', 'WhatsMyShift');
//Set an alternative reply-to address
$mail->addReplyTo('zcopland16@gmail.com', 'Zach Copland');
//Set the subject line
$mail->Subject = 'WhatsMyShift Sign Up';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);

function updateEmailCount() {
    global $conn_addOnly, $conn_readOnly, $companyID, $username, $date;
    //get the number of emails they have sent
    $sql = "SELECT * FROM companies WHERE `companyID`='{$companyID}';";
    $result1 = mysqli_query($conn_readOnly, $sql);
    $row = mysqli_fetch_assoc($result1);
    $count = $row['totalEmailSent'];
    //increase by 1
    $count++;
    $sql = "UPDATE companies SET totalEmailSent='{$count}' WHERE `companyID`='{$companyID}'";
    //update the number to the db
    $result2 = mysqli_query($conn_readOnly, $sql);
    //insert new row into notification table
    $sql = "INSERT INTO `notifications` (companyID, username, numberOfTexts, numberOfEmails, date) VALUES ('{$companyID}', '{$username}', 0, 1, '{$date}');";
    $result3 = mysqli_query($conn_addOnly, $sql);
    if ($result1 && $result2 && $result3) {
        return true;
    } else {
        return false;
    }
}

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else if (updateEmailCount()) {
    
    header("Location: ../index.php");
}



?>