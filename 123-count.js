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

$('#user-list').change(function() {
    user = this.value;
    console.log(user);
    if (user == '--') {
        user_alert.show();
    } else {
        user_alert.hide();
    }
});
$('#new-pass1').focusout(function() {
    pass1 = $('#new-pass1').val();
    pass_match.hide();
    if (pass1.length <= 5) {
        pass1_short.show();
    } else {
        pass1_short.hide();
    }
    
    if (pass1.length > 5 && pass2.length > 5 && pass1 == pass2) {
        pass_okay = true;
    } else if (pass1.length > 5 && pass2.length > 5 && pass1 != pass2) {
        pass_okay = false;
        $('#new-pass1').val('');
        $('#new-pass2').val('');
        pass1 = '';
        pass2 = '';
        pass_match.show();
    }
});
$('#new-pass2').focusout(function() {
    pass2 = $('#new-pass2').val();
    pass_match.hide();
    if (pass2.length <= 5) {
        pass2_short.show();
    } else {
        pass2_short.hide();
    }
    
    if (pass1.length > 5 && pass2.length > 5 && pass1 == pass2) {
        pass_okay = true;
    } else if (pass1.length > 5 && pass2.length > 5 && pass1 != pass2) {
        pass_okay = false;
        $('#new-pass1').val('');
        $('#new-pass2').val('');
        pass1 = '';
        pass2 = '';
        pass_match.show();
    }
});
$('#root-pass').focusout(function() {
    var temp = this.value;
    if (temp.length > 1) {
        $.ajax({type: "POST", url: "db/check-root.php", data: {password: temp}, success: function(result) {
            if (result) {
                root_pass_okay = true;
                root_alert.hide();
            } else {
                root_pass_okay = false;
                root_alert.show();
            }
        }});
    } else {
        root_pass_okay = false;
        root_alert.hide();
    }
});

function validate() {
    if (pass_okay && user.length > 2 && root_pass_okay) {
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
        return false;
    }
}
