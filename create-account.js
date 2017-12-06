var us_taken = $('#username-taken');
var us_allowed = $('#username-allowed');
var us_short = $('#username-short');
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
phone_error.hide();
email_error.hide();
form.hide();
us_allowed.hide();
us_taken.hide();
us_short.hide();
sbtn.hide();
ver_not.hide();
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
                ver.prop('readonly', false);
                us_allowed.show();
                sbtn.show();
            } else {
                ver.prop('readonly', true);
                username_status = false;
                us_taken.show();
                sbtn.hide();
            }
        }});
	} else {
    	//username is less than 5 characters
    	//do nothing
    	us_allowed.hide();
        us_taken.hide();
        us_short.show();
        sbtn.hide();
	}
});
$('#email').focusout(function() {
    var email = $(this).val();
    if (email.length > 5) {
        $.ajax({type: "POST", url: "db/check-phone-email.php", data: {email: email}, success: function(result){
            if (result == true) {
                email_error.hide()
            } else {
                sbtn.hide();
                email_error.show();
            }
        }});
    }
});
$('#phone').focusout(function() {
    var phone = $(this).val();
    if (phone.length > 8) {
        $.ajax({type: "POST", url: "db/check-phone-email.php", data: {phone: phone}, success: function(result){
            if (result == true) {
                phone_error.hide()
            } else {
                sbtn.hide();
                phone_error.show();
            }
        }});
    }
});
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
$('#verification').focusout(function(){
	var code = $(this).val();
	var username = document.getElementById('username').value;
	if (username_status) {
    	if (code.length >= 7 && ver_match == false) {
        	$.ajax({type: "POST", url: "db/check-code.php", data: {username: username, code: code}, success: function(result){
                if (result == true) {
                    sbtn.show();
                    ver_not.hide();
                    ver.prop('readonly', true);
                    ver_match = true;
                } else {
                    sbtn.hide();
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

$('#companyID').focusout(function(){
	var code = $(this).val();
	var username = document.getElementById('username').value;
	if (username_status) {
    	if (code.length >= 7 && ver_match == false) {
        	$.ajax({type: "POST", url: "db/check-company-id.php", data: {username: username, code: code}, success: function(result){
                if (result == true) {
                    
                } else {
                    
                }
            }});
    	} else if (code.length <= 0) {
        	ver_not.hide();
    	}
	} else {
    	$('#modal-text').html('Please ensure you have an approved username first!');
        $("#myModal").modal();
	}
	if (role == 'regular') {
    	$.ajax({type: "POST", url: "db/find-org.php", data: {companyID: code}, success: function(result){
            if (result != false) {
                $('#org').val(result);
            } else {
                
            }
        }});
	}
});