<?php
session_start();
date_default_timezone_set('America/New_York');

/* Testing purposes *
ini_set('display_errors',1);
error_reporting(E_ALL);
*/

require_once 'info/Information.php';
include 'Account.php';
include 'db/dbh_addOnly.php';
//Twilio requirements
require 'twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;
//PHPMailer requirements
require 'PHPMailer/PHPMailerAutoload.php';
include_once("analyticstracking.php");


/* Globals */
$Account = new Account($_SESSION['id'], $conn_addOnly);
$Information = new Information();
$method = $_POST['how-update'];
$numberOfTexts = 0;
$numberOfEmails = 0;
$companyID = $_SESSION['companyID'];
$username = $_SESSION['username'];
$organization = $_SESSION['organization'];
$firstName = $Account->getFirstName();
$lastName = $Account->getLastName();
$email = $Account->getEmail();
$totalTextSent = $Account->getTotalTextSent($companyID, $conn_addOnly);
$totalEmailSent = $Account->getTotalEmailSent($companyID, $conn_addOnly);
$fullName = '';
$headers  = "From: {$firstName} {$lastName} <{$email}>\n";
$headers .= 'X-Mailer: PHP/' . phpversion();
$headers .= "X-Priority: 1\n"; // Urgent message!
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
$subject = "{$organization} Update";
$to_email = '';
$body = $_POST['message'];
$date = date("m/d/Y @ g:ia"); 
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
$mail->Host = $Information->getHost();
//enable this if you are using gmail smtp, for mandrill app it is not required
$mail->SMTPSecure = 'tls';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = $Information->getUsername();
//Password to use for SMTP authentication
$mail->Password = $Information->getPassword();
//Blank to email
$mail->addAddress('');


/* Twilio Code */
//get the employees who were check off
$em = $_POST['check_employees'];
$count_em = count($em); //length of array
$people = array("key1" => "value1"); //set dummy value
unset($people['key1']); //remove dummy value
//loop through array and set it to people array
for ($i=0; $i < $count_em; $i++) { 
    //get the info for the i-th employee
    $sql = "SELECT * FROM `employees` WHERE id={$em[$i]}";
    $result = mysqli_query($conn_addOnly, $sql);
    $row = mysqli_fetch_assoc($result);
    //get full name
    $fullName = $row['firstName'];
    $fullName .= ' ';
    $fullName .= $row['lastName'];
    //get email
    $email = $row['email'];
    //set beginning of phone number
    $phone = '+1';
    //get phone number
    $phone .= $row['phone'];
    //set people variable for Twilio
    $people[$phone] = $row['firstName'];
    //Set who the message is to be sent to
    //but first, check to make sure they actually have an email
    if (!empty($email) && ($method == 'both' || $method == 'email')) {
        $mail->addBCC($email, $fullName);
        $numberOfEmails++;
    }
    
}

function sendText() {
    global $Information, $people, $numberOfTexts, $organization, $body;
    // Step 2: set our AccountSid and AuthToken from https://twilio.com/console
    $AccountSid = $Information->getAccountSid();
    $AuthToken = $Information->getAuthToken();

    // Step 3: instantiate a new Twilio Rest Client
    $client = new Client($AccountSid, $AuthToken);
    // Step 5: Loop over all our friends. $number is a phone number above, and 
    // $name is the name next to it
    foreach ($people as $number => $name) {
        $sms = $client->account->messages->create(
            // the number we are sending to - Any phone number
            $number,
    
            array(
                // Step 6: Change the 'From' number below to be a valid Twilio number 
                // that you've purchased
                'from' => "+12076185206", 
                
                // the sms body
                'body' => "Hello {$name},\n" . $body . "\n--Sent from {$organization}"
            )
        );
        // Display a confirmation message on the screen
        echo "<h3>Sent text to {$name}</h3>";
        $numberOfTexts++;
    }
    echo "<h4>Text total: $numberOfTexts</h4>";
}

function sendEmail() {
    global $body, $organization, $message, $firstName, $lastName, $email, $to_email, $subject, $headers, $numberOfEmails, $mail;
    $upper = strtoupper($organization);
    $body = nl2br($body);
    $message = <<<HTML
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>{$organization} Update</title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
	<!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
        <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

	<!-- CSS Reset -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for iOS meddling in triggered links. */
        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }

        /* What it does: A work-around for Gmail meddling in triggered links. */
        .x-gmail-data-detectors,
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
	        display: none !important;
	        opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
	        display:none !important;
	   	}

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit @ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

    </style>

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
	<!--[if gte mso 9]>
	<xml>
		<o:OfficeDocumentSettings>
			<o:AllowPNG/>
			<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml>
	<![endif]-->

    <!-- Progressive Enhancements -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

    </style>

</head>
<body width="100%" bgcolor="#222222" style="margin: 0; mso-line-height-rule: exactly;">
    <center style="width: 100%; background: #222222; text-align: left;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
            Please view update from {$organization}.
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 600px; margin: auto;" class="email-container">
            <!--[if mso]>
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Header : BEGIN -->
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center">
                        <!--img src="http://placehold.it/200x50" aria-hidden="true" width="200" height="50" alt="alt_text" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;"-->
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

                <!-- Hero Image, Flush : BEGIN -->
                <!--tr>
                    <td bgcolor="#ffffff">
                        <img src="media/windham-front.jpg" aria-hidden="true" width="600" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;" class="g-img">
                    </td>
                </tr-->
                <!-- Hero Image, Flush : END -->

                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                    <p style="text-align: center">ATTENTION {$upper} EMPLOYEE:</p>
                                    {$body}
                                    <!-- Clear Spacer : BEGIN -->
                                    <tr>
                                        <td height="40" style="font-size: 0; line-height: 0;">
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <!-- Clear Spacer : END -->
                                    <!-- Button : BEGIN -->
                                    <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                        <tr>
                                            <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                                <a href="http://www.whatsmyshift.com" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                                    <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;View Schedule&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Button : END -->
                <!-- 1 Column Text : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                    If there are any questions regarding this update, please contact <a href="mailto:{$email}">{$firstName} {$lastName}</a>. Questions about this service? Please contact <a href="mailto:zcopland16@gmail.com">Zach Copland</a>.
                                </td>
                                </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text : END -->
            </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; line-height:18px; text-align: center; color: #888888;" class="x-gmail-data-detectors">
                        <br><br>
                        {$organization}<br>This email was sent by your administrator. 
                        <br><br>
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->
            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>
    </center>
</body>
HTML;

    //Set who the message is to be sent from
    $mail->setFrom("{$email}", "{$firstName} {$lastName}");
    //Set an alternative reply-to address
    $mail->addReplyTo("{$email}", "{$firstName} {$lastName}");
    //Set the subject line
    $mail->Subject = "{$organization} Update";
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($message);
    //Replace the plain text body with one created manually
    $mail->AltBody = $body;
    
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "<h3>Email(s) sent!</h3>";
        echo "<h4>Email total: $numberOfEmails</h4>";
    }
}

function updateNotificationDB() {
    global $conn_addOnly, $companyID, $username, $numberOfEmails, $numberOfTexts, $date, $totalEmailSent, $totalTextSent;
    $query = "INSERT INTO `notifications` (companyID, username, numberOfTexts, numberOfEmails, date) VALUES ('{$companyID}', '{$username}', {$numberOfTexts}, {$numberOfEmails}, '{$date}');";
    $result = mysqli_query($conn_addOnly, $query);
    echo mysqli_error($conn_addOnly);
    if ($result) {
        $totalTextSent = $numberOfTexts + $totalTextSent;
        $totalEmailSent = $numberOfEmails + $totalEmailSent;
        $query2 = "UPDATE `companies` SET totalTextSent={$totalTextSent}, totalEmailSent={$totalEmailSent} WHERE companyID='{$companyID}';";
        $result2 = mysqli_query($conn_addOnly, $query2);
        echo mysqli_error($conn_addOnly);
        if ($result2) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function sendFailedEmail() {
    global $username, $companyID, $numberOfEmails, $numberOfTexts, $date;
    $email_message = "{$username} ({$companyID}), attempted to send {$numberOfEmails} email(s) and {$numberOfTexts} text(s) but failed to write to the DB on {$date}.";
    mail('zcopland16@gmail.com', 'Failed DB Insertion', $email_message, $headers);
}

switch ($method) {
    case 'email':
        sendEmail();
        if (!updateNotificationDB()) {
            sendFailedEmail();
        }
        break;
    case 'text':
        sendText();
        if (!updateNotificationDB()) {
            sendFailedEmail();
        }
        break;
    case 'both':
        sendText();
        sendEmail();
        if (!updateNotificationDB()) {
            sendFailedEmail();
        }
        break;
    default:
        echo "<h3>Error processing request. Please go back and try again.</h3>";
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications Sent</title>
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
        <h1>Notifications have been sent to employees.</h1>
        <button class="btn vermillion-bg btn-md pull-right white-text" id="back">Back</a></button>
    </div>
    <script>
        $(document).ready(function() {
            $('#back').click(function() {
                window.location.href = "main.php";
            });
        });
    </script>
</body>
</html>