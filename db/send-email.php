<?php
require_once '../info/Information.php';
//PHPMailer requirements
date_default_timezone_set('America/New_York');
require '../PHPMailer/PHPMailerAutoload.php';
include 'dbh_readOnly.php';

$information = new Information();

$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$username = $_GET['username'];
$org = $_GET['organization'];

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
$mail->addAddress('zcopland16@gmail.com');

$message = "{$firstName} {$lastName} ({$username}) has just created an account with {$org}!";

//Set who the message is to be sent from
$mail->setFrom('zach@zachcopland.com', 'WhatsMyShift');
//Set an alternative reply-to address
$mail->addReplyTo('zcopland16@gmail.com', 'Zach Copland');
//Set the subject line
$mail->Subject = 'WhatsMyShift Sign Up';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);
//Replace the plain text body with one created manually
$mail->AltBody = $body;

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: ../index.php");
}



?>