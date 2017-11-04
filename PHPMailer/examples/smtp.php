<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer - SMTP test</title>
</head>
<body>
<?php

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('America/New_York');

require '../PHPMailerAutoload.php';

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
$mail->Host = "smtp.gmail.com";

//enable this if you are using gmail smtp, for mandrill app it is not required
$mail->SMTPSecure = 'tls';

$body = <<<HTML
<h1>Hello World!</h1>
<p>My name is <strong>Zach Copland</strong> and this is a test!</p>
HTML;

//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "zcopland16@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "zksfrfdddeczaxzy";
//Set who the message is to be sent from
$mail->setFrom('zach@zachcopland.com', 'zachcopland.com');
//Set an alternative reply-to address
$mail->addReplyTo('zcopland16@gmail.com', 'Zach Copland');
//Set who the message is to be sent to
$mail->addAddress('zwisecopland@stetson.edu', 'Zachary Wise-Copland');
//Set the subject line
$mail->Subject = 'Send mail from localhost';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($body);
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
?>
</body>
</html>
