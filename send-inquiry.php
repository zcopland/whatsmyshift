<?php
date_default_timezone_set('America/New_York');

require_once 'info/Information.php';
//PHPMailer requirements
require 'PHPMailer/PHPMailerAutoload.php';
include "includes/recaptchalib.php";

if ($recap_error) {
    exit();
}

/* Variables */
$information = new Information();
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$employeeNumber = '';
if (!empty($_POST['employeeNumber'])) {
    $employeeNumber = $_POST['employeeNumber'];
}
$inquiry = $_POST['inquiry'];
$headers  = "From: {$firstName} {$lastName} <{$email}>\n";
$headers .= 'X-Mailer: PHP/' . phpversion();
$headers .= "X-Priority: 1\n"; // Urgent message!
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
$subject = "WhatsMyShift Inquiry";
$message = <<<HTML
Hello Zach,
<br/><br/>
{$firstName} {$lastName} has inquired about What's My Shift.<br/>
Email: {$email}<br/>
Number of employees: {$employeeNumber}<br/>
<br/>
Inquiry:<br/>
{$inquiry}<br/>
HTML;
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
//Set who the message is to be sent from
$mail->setFrom("{$email}", "{$firstName} {$lastName}");
//Set an alternative reply-to address
$mail->addReplyTo("{$email}", "{$firstName} {$lastName}");
//Set the subject line
$mail->Subject = "WhatsMyShift Inquiry";
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);
//Replace the plain text body with one created manually
$mail->AltBody = $body;

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "<h3>Email sent!</h3>";
    header("Location: http://www.whatsmyshift.com"); /* Redirect browser */
    exit();
    
}


?>