/*
  123-count.js
*/

//Globals
var pass1 = '';
var pass2 = '';
var pass1_short = $('#password1-short');
var pass2_short = $('#password2-short');
var pass_match = $('#password-match');
var root_alert = $('#root-alert');
var user_alert = $('user-short-alert');
var pass_okay = false;
var user = '--';
var root_pass_okay = false;
pass1_short.hide();
pass2_short.hide();
pass_match.hide();
root_alert.hide();
user_alert.hide();

//If the user list changes, show an alert
$('#user-list').change(function() {
    user = this.value;
    console.log(user);
    if (user == '--') {
        user_alert.show();
    } else {
        user_alert.hide();
    }
});

//When the new pass field is clicked out, do this
$('#new-pass1').focusout(function() {
    //Get the value and store in pass1
    pass1 = $('#new-pass1').val();
    //Hide the alert if it is showing
    pass_match.hide();
    //Check to make sure it is at least 6 characters
    if (pass1.length <= 5) {
        //If not, show the alert
        pass1_short.show();
    } else {
        //Hide the alert
        pass1_short.hide();
    }
    
    //If the length requirements are all set & they are equal
    if (pass1.length > 5 && pass2.length > 5 && pass1 == pass2) {
        pass_okay = true;
    } else if (pass1.length > 5 && pass2.length > 5 && pass1 != pass2) {
        //Reset password fields and display alert
        pass_okay = false;
        $('#new-pass1').val('');
        $('#new-pass2').val('');
        pass1 = '';
        pass2 = '';
        pass_match.show();
    }
});

//If the new pass 2 field is clicked out of
$('#new-pass2').focusout(function() {
    //Get the new value
    pass2 = $('#new-pass2').val();
    pass_match.hide();
    //Make sure it is 6 characters or longer
    if (pass2.length <= 5) {
        pass2_short.show();
    } else {
        pass2_short.hide();
    }
    
    //If the length requirements are all set & they are equal
    if (pass1.length > 5 && pass2.length > 5 && pass1 == pass2) {
        pass_okay = true;
    } else if (pass1.length > 5 && pass2.length > 5 && pass1 != pass2) {
        //Reset password fields and display alert
        pass_okay = false;
        $('#new-pass1').val('');
        $('#new-pass2').val('');
        pass1 = '';
        pass2 = '';
        pass_match.show();
    }
});

//Root pass text field is focused out of
$('#root-pass').focusout(function() {
    //Get value of password
    var temp = this.value;
    //If the length is at least 2 characters, check it
    if (temp.length > 1) {
        //Use AJAX to check password to root password in DB
        $.ajax({type: "POST", url: "db/check-root.php", data: {password: temp}, success: function(result) {
            if (result) {
                //Password is correct
                root_pass_okay = true;
                root_alert.hide();
            } else {
                //Password is incorrect
                root_pass_okay = false;
                root_alert.show();
            }
        }});
    } else {
        //Password was not long enough to check
        root_pass_okay = false;
        root_alert.hide();
    }
});

//Validate the fields and the variables previously set
function validate() {
    if (pass_okay && user.length > 2 && root_pass_okay) {
        //The form can submit
        return true;
    } else {
        if (!pass_okay) {
            pass_match.show();
        }
        if (user.length < 2) {
            user_alert.show();
        }
        if (!root_pass_okay) {
            root_alert.show();
        }
        //The form will not submit
        return false;
    }
}
