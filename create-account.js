/*
    create-account.js
    
    This JS file is used for the backend 
    for the create-account page.
*/

/* Globals */
var us_taken = $('#username-taken');
var us_allowed = $('#username-allowed');
var us_short = $('#username-short');
var pass_short = $('#password-short');
var sbtn = $('#submitbtn');
var role = $('#role').val();
var form = $('#restOfForm');
var ver = $('#verification');
var ver_div = $('#ver-div');
var org = $('#org');
var zip = $('#zip');
var billing = $('.billingDiv');
var ver_not = $('#ver-not');
var username_status = false;
var ver_match = false;
var phone_error = $('#phone-error');
var email_error = $('#email-error');
var companyID_taken = $('#companyID-taken');
var check_username = false;
var check_phone = false;
var check_email = false;
var check_ver = false;
var check_companyID = false;
var fields = [];
var empty_fields = [];
var pass_okay = false;
var status_okay = false;
companyID_taken.hide();
phone_error.hide();
email_error.hide();
form.hide();
us_allowed.hide();
us_taken.hide();
us_short.hide();
ver_not.hide();
pass_short.hide();

//Username field has focused out, check their username
$('#username').focusout(function() {
	var username = document.getElementById('username').value;
	if (username.length > 4) {
    	//username is at least 5 characters
    	//check the db to see if it is taken
    	us_allowed.hide();
    	us_taken.hide();
    	us_short.hide();
    	$.ajax({type: "POST", url: "db/check-username.php", data: {username: username}, success: function(result) {
            if (result) {
                username_status = true;
                check_username = true;
                ver.prop('readonly', false);
                us_allowed.show();
            } else {
                ver.prop('readonly', true);
                check_username = false;
                username_status = false;
                us_taken.show();
            }
        }});
	} else {
    	//username is less than 5 characters
    	//do nothing
    	check_username = false;
    	us_allowed.hide();
        us_taken.hide();
        us_short.show();
	}
});
//Check the IP of user with the blacklist DB
$.ajax({
    type: "POST",
    url: "db/check-ip.php",
    data: {ip: userip},
    success: function(result){
        if (!result) {
            window.location.href = "logout.php";
        }
    }
});
//Email input has focused out, check to see if it exists
$('#email').focusout(function() {
    var email = $(this).val();
    var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (email.length > 5) {
        $.ajax({type: "POST", url: "db/check-phone-email.php", data: {email: email}, success: function(result){
            if (result) {
                email_error.hide();
                if (re.test(email)) {
                    check_email = true;
                } else {
                    check_email = true;
                    email_error.html("Please enter a valid email!");
                    email_error.show();
                }
            } else {
                check_email = false;
                email_error.html("Email is already in use!");
                email_error.show();
            }
        }});
    }
});
//Phone input has focused out, check to see if it exists
$('#phone').focusout(function() {
    var phone = $(this).val();
    if (phone.length > 8) {
        $.ajax({type: "POST", url: "db/check-phone-email.php", data: {phone: phone}, success: function(result){
            if (result) {
                phone_error.hide();
                check_phone = true;
            } else {
                check_phone = false;
                phone_error.show();
            }
        }});
    }
});
//Toggle b/w admin and regular user by showing/hiding fields
$('input[type=radio][name=role]').change(function() {
	form.show(1000);
    if (this.value == 'admin') {
        role = 'admin';
        ver_div.show(500);
        ver.prop('required', true);
        org.prop('readonly', false);
        $('#asterix-email').html('* ');
        $('#asterix-email').prop('required', true);
        $('.zipDiv').show();
        zip.prop('required', true);
        billing.show(500);
        $('#billing').prop('required', true);
    }
    else if (this.value == 'regular') {
        role = 'regular';
        ver_div.hide(500);
        ver.prop('required', false);
        org.prop('readonly', true);
        $('.zipDiv').hide();
        zip.prop('required', false);
        $('#asterix-email').html('');
        $('#asterix-email').prop('required', false);
        billing.hide(500);
        $('#billing').prop('required', false);
    }
});
//Check the verification code the user provided
$('#verification').focusout(function(){
	var code = $(this).val();
	var username = document.getElementById('username').value;
	if (username_status) {
    	if (code.length >= 7 && ver_match === false) {
        	$.ajax({type: "POST", url: "db/check-code.php", data: {username: username, code: code}, success: function(result){
                if (result) {
                    check_ver = true;
                    ver_not.hide();
                    ver.prop('readonly', true);
                    ver_match = true;
                } else {
                    check_ver = false;
                    ver_not.show();
                    ver_match = false;
                }
            }});
    	} else if (code.length <= 0) {
        	ver_not.hide();
    	}
	} else {
    	$('#modal-text').html('Please ensure you have an approved username first!');
        $("#myModal").modal();
	}
});
//Check to make sure the password meets the requirements
$('#password').focusout(function() {
    var password = $(this).val();
    if (password.length <= 5 && password.length > 0) {
        pass_short.show();
        pass_okay = false;
    } else if (password.length < 1) {
        pass_short.hide();
        pass_okay = false;
    } else {
        pass_short.hide();
        pass_okay = true;
    }
});
//Check to see if the company ID exists or not
$('#companyID').focusout(function(){
	var companyID = $('#companyID').val();
	var username = document.getElementById('username').value;
	if (role == 'admin') {
    	if (username_status) {
        	if (companyID.length > 1) {
            	$.ajax({type: "POST", url: "db/check-company-id.php", data: {companyID: companyID}, success: function(result){
                    if (result) {
                        companyID_taken.hide();
                        check_companyID = true;
                    } else {
                        companyID_taken.show();
                        check_companyID = false;
                    }
                }});
        	}
    	} else {
        	$('#modal-text').html('Please ensure you have an approved username first!');
            $("#myModal").modal();
    	}
	} else if (role == 'regular') {
    	$.ajax({type: "POST", url: "db/find-org.php", data: {companyID: companyID}, success: function(result){
            if (result != 'Not found!') {
                check_companyID = true;
                $('#org').val(result);
                checkBlacklist();
            } else {
                check_companyID = false;
                $('#org').val(result);
            }
        }});
	}
});
//Check the fields for shortness or empties
function checkFields() {
    var fieldname;
    checkBlacklist();
    empty_fields = [];
    if (role == 'admin') {
        fields = ['firstName', 'lastName', 'username', 'password', 'securityQuestion', 'securityAnswer', 'email', 'phone', 'org', 'companyID', 'zip', 'billing', 'verification'];
        if (!check_ver && empty_fields.indexOf('verification') < 0) {
            empty_fields.push('verification code');
        }
    } else if (role == 'regular') {
        fields = ['firstName', 'lastName', 'username', 'password', 'securityQuestion', 'securityAnswer', 'phone', 'org', 'companyID'];
    } else {
        return false;
    }
    
    for (i = 0; i < fields.length; i++) {
        fieldname = fields[i];
        if (document.forms['create-account-form'][fieldname].value === "") {
            switch (fieldname) {
                case 'firstName':
                    fieldname = 'first name';
                    break;
                case 'lastName':
                    fieldname = 'last name';
                    break;
                case 'securityQuestion':
                    fieldname = 'security question';
                    break;
                case 'securityAnswer':
                    fieldname = 'security answer';
                    break;
                case 'org':
                    fieldname = 'organization';
                    break;
                case 'companyID':
                    fieldname = 'company ID';
                    break;
            }
            empty_fields.push(fieldname);
        }
    }
    
    if (!check_email && empty_fields.indexOf('email') < 0 && email.length > 5) {
        empty_fields.push('email');
    }
    if (!check_phone && empty_fields.indexOf('phone') < 0) {
        empty_fields.push('phone');
    }
    if (!check_username && empty_fields.indexOf('username') < 0) {
        empty_fields.push('username');
    }
    if (!check_companyID && empty_fields.indexOf('company ID') < 0) {
        empty_fields.push('company ID');
    }
    if (!pass_okay && empty_fields.indexOf('password') < 0) {
        empty_fields.push('password');
    }
    
    if (document.forms['create-account-form']['phone'].value.length < 9 && empty_fields.indexOf('phone') < 0) {
        empty_fields.push('phone');
    }
    
    if (empty_fields.length > 0) {
        return false;
    } else {
        return true;
    }
}

//Check the blacklist for the user
function checkBlacklist() {
    var firstName = $('#firstName').val();
    var lastName = $('#lastName').val();
    var companyID = $('#companyID').val();
    if (firstName.length > 2 && lastName.length > 2 && companyID.length > 1) {
        $.ajax({type: "POST", url: "db/blacklist-search.php", data: {companyID: companyID, firstName: firstName, lastName: lastName}, success: function(result){
            status_okay = result;
        }});
    } else {
        status_okay = false;
    }
}

//Validate the form, if okay then submit the form
function validate() {
    if (status_okay === true && checkFields() === true) {
        return true;
    } else if (!checkFields()) {
        var message = "The following field(s) must be filled out: ";
        if (empty_fields.length == 1) {
            message += empty_fields[0] + '.';
        } else if (empty_fields.length > 1 && empty_fields.length < 3) {
            message += empty_fields[0] + ' and ' + empty_fields[1] + '.';
        } else {
            for (i = 0; i < empty_fields.length; i++) {
                if (i < empty_fields.length - 1) {
                    message += empty_fields[i] + ', ';
                } else {
                    message += 'and ' + empty_fields[i] + '.';
                }
            }
        }
        $('#modal-text').html(message);
        $("#myModal").modal();
        return false;
    } else if (!status_okay) {
        $('#modal-text').html('You are unable to create an account with this company.');
        $("#myModal").modal();
        return false;
    }
}
