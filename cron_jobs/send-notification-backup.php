<?php
include '../db/dbh_readOnly.php';
require_once '../info/Information.php';
require '../PHPMailer/PHPMailerAutoload.php';

/* Testing purposes */
ini_set('display_errors',1);
error_reporting(E_ALL);


/* Globals */
$information = new Information();
$companyIDs = [];
$texts_assoc = [0 => 'null'];
$emails_assoc = [0 => 'null'];
$usernames = [];
$numberOfTexts = [];
$numberOfEmails = [];
$dates = [];
$message = '';

/* Get all the data from the db */
$query = "SELECT * FROM notifications;";
$result = mysqli_query($conn_readOnly, $query);
while ($row = mysqli_fetch_assoc($result)) {
   array_push($companyIDs, $row['companyID']);
   array_push($usernames, $row['username']);
   array_push($numberOfTexts, $row['numberOfTexts']);
   array_push($numberOfEmails, $row['numberOfEmails']);
   array_push($dates, $row['date']);
}

/* Set up the message for sending */
for ($i = 0; $i < count($companyIDs); $i++) {
    $number = $i + 1;
    if (count($texts_assoc) > 1) {
        foreach ($texts_assoc as $text => $company) {
            if ($company == $companyIDs[$i]) {
                $text = $text + $numberOfTexts[$i];
                //$texts_assoc[$text => $company] = $texts_assoc[$newText => $company];
            } else {
                $texts_assoc = $texts_assoc + array($numberOfTexts[$i] => $companyIDs[$i]);
                $emails_assoc = $emails_assoc + array($numberOfEmails[$i] => $companyIDs[$i]);
            }
        }
    } else {
        $texts_assoc = $texts_assoc + array($numberOfTexts[$i] => $companyIDs[$i]);
        $emails_assoc = $emails_assoc + array($numberOfEmails[$i] => $companyIDs[$i]);
    }
    
    $message .= <<<HTML
{$number}. {$companyIDs[$i]}, {$usernames[$i]} sent {$numberOfTexts[$i]} text(s) and {$numberOfEmails[$i]} email(s) on {$dates[$i]}.<br/>
HTML;
}

/*
$message .= <<<HTML

HTML;
*/

print_r($texts_assoc);
print_r($emails_assoc);

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
$mail->setFrom("zcopland16@gmail.com", "Zach Copland");
//Set an alternative reply-to address
$mail->addReplyTo("zcopland16@gmail.com", "Zach Copland");
//Set the subject line
$mail->Subject = "WhatsMyShift Notification Backup";
//Read an HTML message body from an external file, convert referenced images to embedded,
$mail->msgHTML($message);

/*
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "<h3>Email sent!</h3>";
    
}
*/

?>