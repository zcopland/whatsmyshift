<?php
/*
    Account.php
    
    This PHP file is a class to get necessary fields
    from the whatsmyshift DB.
*/

class Account {
    //Globals
    protected $query = "";
    protected $result;
    protected $row;
    
    //2 arg constructor
    public function __construct($id, $conn) {
        $this->query = "SELECT * FROM `employees` WHERE id={$id};";
        $this->result = mysqli_query($conn, $this->query);
        $this->row = mysqli_fetch_assoc($this->result);
    }
    
    //Get the first name of user
    public function getFirstName() {
        return $this->row['firstName'];
    }
    
    //Get the last name of user
    public function getLastName() {
        return $this->row['lastName'];
    }
    
    //Get the username of user
    public function getUsername() {
        return $this->row['username'];
    }
    
    //Get the email of user
    public function getEmail() {
        return $this->row['email'];
    }
    
    //Get the phone of user
    public function getPhone() {
        return $this->row['phone'];
    }
    
    //Get the last login String of user
    public function getLastLogin() {
        return $this->row['lastLogin'];
    }
    
    //Get the organization of user
    public function getOrganization() {
        return $this->row['organization'];
    }
    
    //Get the companyID of user
    public function getCompanyID() {
        return $this->row['companyID'];
    }
    
    //Get the security question of user
    public function getSecurityQuestion() {
        return $this->row['securityQuestion'];
    }
    
    //Get the security answer of user
    public function getSecurityAnswer() {
        return $this->row['securityAnswer'];
    }
    
    //Get the boolean of canReset of user
    public function getCanReset() {
        return $this->row['canReset'];
    }
    
    //Get the full name of the admin of the user's associated company
    public function getAdminName($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $username = $row['adminUsername'];
        $query = "SELECT * FROM `employees` WHERE username='{$username}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $fullName = "{$row['firstName']} {$row['lastName']}";
        return $fullName;
    }
    
    //Get the admin email of user's associated company
    public function getAdminEmail($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['adminEmail'];
    }
    
    //Get the zip code of the user's associated company
    public function getZip($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['weatherZip'];
    }
    
    //Get the weatherShow boolean of user's associated company
    public function getWeatherShow($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['weatherShow'];
    }
    
    //Get the number of employees of user's associated company
    public function getEmployeeCount($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['organizationCount'];
    }
    
    //Get the calendar view of user's associated company
    public function getDefaultCalView($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['defaultCalView'];
    }
    
    //Get the total number of texts sent of user's associated company
    public function getTotalTextSent($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['totalTextSent'];
    }
    
    //Get the total number of emails sent of user's associated company
    public function getTotalEmailSent($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['totalEmailSent'];
    }
    
    //Get the boolean if the user is allowed to see login attempts
    public function getCanViewLogins($companyID, $conn) {
        $query = "SELECT * FROM `companies` WHERE companyID='{$companyID}';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['canViewLogins'];
    }
    
    //Get the HTML employee table used in the admin panel
    public function getEmployeeTable($companyID, $conn) {
        $query = "SELECT * FROM `employees` WHERE companyID='{$companyID}' ORDER BY lastName;";
        $result = mysqli_query($conn, $query);
        $html = "<div id=\"employeeTable\" class=\"container table-responsive\"><table class='table table-hover'><tr><th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Last Login</th><th align=\"center\">Delete</th><th align=\"center\">Reset Password</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            $button_delete = '';
            if ($row['isAdmin'] != 1) {
                $button_delete .= "<button value=\"{$row['id']}\" class='deleteUser btn btn-xs btn-default'><span class='glyphicon glyphicon-remove'></span></button>";
                if ($row['canReset'] == 0) {
                    $button_reset = "<button value=\"{$row['id']}\" class='btn resetUser btn-xs btn-default'><span class='glyphicon glyphicon-ok'></span></button>";
                } else if ($row['canReset'] == 1) {
                    $button_reset = "<button value=\"{$row['id']}\" class='btn btn-xs btn-default'><span class='glyphicon glyphicon-minus'></span></button>";
                }
                
            }
    		$html .= <<<TEXT
    <tr>
      <td>{$row['lastName']}, {$row['firstName']}</td>
      <td>{$row['username']}</td>
      <td>{$row['email']}</td>
      <td>{$row['phone']}</td>
      <td>{$row['lastLogin']}</td>
      <td><div class="text-center">{$button_delete}</div></td>
      <td><div class="text-center">{$button_reset}</div></td>
TEXT;
            
        }
        $html .= "</tr></table></div>";
        return $html;
    }
    
    //Get the HTML notification table used in the admin panel
    public function getNotificationTable($companyID, $conn) {
        $query = "SELECT * FROM `notifications` WHERE companyID='{$companyID}' ORDER BY `id` DESC;";
        $result = mysqli_query($conn, $query);
        $html = "<div id=\"notificationTable\" class=\"container pre-scrollable table-responsive\"><table class='table table-hover'><tr><th>Sent By</th><th>Text(s) Sent</th><th>Email(s) Sent</th><th>Date Sent</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
    		$html .= <<<TEXT
    <tr>
      <td>{$row['username']}</td>
      <td>{$row['numberOfTexts']}</td>
      <td>{$row['numberOfEmails']}</td>
      <td>{$row['date']}</td>
TEXT;
            
        }
        $html .= "</tr></table></div>";
        return $html;
    }
    
    //Get the HTML login table used in the admin panel
    public function getLoginTable($companyID, $conn) {
        $query = "SELECT * FROM `logins` WHERE companyID='{$companyID}' AND `username`<>'zcopland' ORDER BY `id` DESC LIMIT 15;";
        $result = mysqli_query($conn, $query);
        $html = "<div class='row'><h2>Login Table</h2></div><br/><div id=\"loginTable\" class=\"container table-responsive\"><table class='table table-hover'><tr><th>Username</th><th>Successful</th><th>Date</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
    		$successful = '';
    		if ($row['successful']) {
        		$successful = 'Yes';
    		} else {
        		$successful = 'No';
    		}
    		$html .= <<<TEXT
    <tr>
      <td>{$row['username']}</td>
      <td>{$successful}</td>
      <td>{$row['date']}</td>
TEXT;
            
        }
        $html .= "</tr></table></div>";
        return $html;
    }
    
    //Get the HTML full employee list used in the root panel
    public function getAllEmployees($conn) {
        $allEmployees = [];
        // $allEmployees['Full Name'] = "username"
        $query = "SELECT * FROM `employees` ORDER BY `lastName`;";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $fullName = "{$row['firstName']} {$row['lastName']}";
            $allEmployees[$fullName] = $row['username'];
        }
        return $allEmployees;
    }
    
    //Get the list of all of the IPs that have been blacklisted
    public function getBlacklistIPs($conn) {
        $ips = [];
        $query = "SELECT * FROM `blacklist`;";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['ip'])) {
                array_push($ips, $row['ip']);
            }
        }
        return $ips;
    }
    
    //Get the boolean if the user has agreed to Terms & Conditions
    public function getBooleanAgreeTC($conn) {
        return $this->row['booleanAgreeTC'];
    }
}

?>