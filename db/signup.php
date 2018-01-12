<?php
include 'dbh_addOnly.php';
include 'dbh_readOnly.php';
include 'config.php';
date_default_timezone_set('America/New_York');
include "../includes/recaptchalib.php";

if ($recap_error) {
    exit();
}

$firstName = $_POST['firstName'];
$firstName = sanitize($firstName);
$lastName = $_POST['lastName'];
$lastName = sanitize($lastName);
$username = $_POST['username'];
$username = strtolower($username);
$username = sanitize($username);
$pwd = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => 10));
$email = $_POST['email'];
$email = sanitize($email);
$phone = $_POST['phone'];
$phone = sanitize($phone);
$org = $_POST['org'];
$companyID = $_POST['companyID'];
$companyID = sanitize($companyID);
$companyID = strtoupper($companyID);
$securityQuestion = $_POST['securityQuestion'];
$securityAnswer = $_POST['securityAnswer'];
$adminEmail = '';

$date = date("m/d/Y @ g:ia");
$billing = $_POST['billing'];
$isAdmin = 0;
$weatherZip = $_POST['zip'];
if ($_POST['role'] == 'admin') {
    $isAdmin = 1;
} else if ($_POST['role'] == 'regular') {
    $isAdmin = 0;
}

function sanitize($dirty) {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

function insertIntoEmployees() {
    global $firstName, $lastName, $username, $pwd, $email, $phone, $org, $companyID, $isAdmin, $date, $conn_addOnly, $securityAnswer, $securityQuestion;
    $sql = "INSERT INTO employees(firstName, lastName, username, password, email, phone, organization, companyID, isAdmin, securityQuestion, securityAnswer, lastLogin) VALUES ('{$firstName}', '{$lastName}', '{$username}', '{$pwd}', '{$email}', '{$phone}', '{$org}', '{$companyID}', {$isAdmin}, '{$securityQuestion}', '{$securityAnswer}', '{$date}')";
    $result = mysqli_query($conn_addOnly, $sql);
    if ($result) {
        return true;
    } else {
        echo 'Failed 1/4 to process this request. Please go back and try to submit again.' . "<br/>";
        return false;
    }
}
function updateCompanies() {
    global $firstName, $lastName, $username, $email, $phone, $org, $companyID, $isAdmin, $date, $conn_addOnly, $weatherZip, $billing;
    if ($isAdmin == 1) {
        $sql = "INSERT INTO companies(organization, companyID, adminUsername, adminEmail, organizationCount, weatherZip, weatherShow, defaultCalView, billing, totalTextSent, totalEmailSent, canViewLogins, dateCreated) VALUES ('{$org}', '{$companyID}', '{$username}', '{$email}', 1, '{$weatherZip}', 1, 'month', '{$billing}', 0, 0, 0, '{$date}')";
    } else if ($isAdmin == 0) {
        $sql = "SELECT * FROM companies WHERE `companyID`='{$companyID}';";
        $result1 = mysqli_query($conn_addOnly, $sql);
        $row = mysqli_fetch_assoc($result1);
        $count = $row['organizationCount'];
        $count++;
        $sql = "UPDATE companies SET organizationCount='{$count}' WHERE `companyID`='{$companyID}'";
    }
    $result2 = mysqli_query($conn_addOnly, $sql);
    
    if ($result2) {
        return true;
    } else {
        echo 'Failed 2/4 to process this request. Please go back and try to submit again.' . "<br/>";
        return false;
    }
}
function createCal() {
    global $isAdmin, $companyID, $con;
    if ($isAdmin == 1) {
        $sql = "CREATE TABLE `calendar_{$companyID}` (`id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) NOT NULL, `startdate` varchar(48) NOT NULL, `enddate` varchar(48) NOT NULL, `allDay` varchar(5) NOT NULL DEFAULT 'true', `dateStamp` date NOT NULL);";
        $result = mysqli_query($con, $sql);
        if ($result) {
            return true;
        } else {
            echo 'Failed 3/4 to process this request. Please go back and try to submit again.' . "<br/>";
            return false;
        }
    } else {
        return true;
    }
}

function getAdminEmail() {
    global $companyID, $conn_readOnly, $adminEmail;
    $sql = "SELECT * FROM `companies` WHERE `companyID`='{$companyID}';";
    $result = mysqli_query($conn_readOnly, $sql);
    $row = mysqli_fetch_assoc($result);
    $adminEmail = $row['adminEmail'];
    if (!empty($adminEmail)) {
        return true;
    } else {
        echo 'Failed 4/4 to process this request. Please go back and try to submit again.' . "<br/>";
        return false;
    }
    
}

if (insertIntoEmployees() && updateCompanies() && createCal() && getAdminEmail()) {
    header("Location: send-email.php?adminEmail={$adminEmail}&companyID={$companyID}&firstName={$firstName}&lastName={$lastName}&username={$username}&organization={$org}");
}


?>
