//Variables
var verified = false;
var empty_fields = [];
var submitMessage = '';

$('#back').click(function() {
    window.history.back(-1);
});
//Prevent user from hitting enter key and submitting
$(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
});
$('#verify').click(function() {
    var username = $('#username').val();
    var password = $('#password').val();
    var companyID = $('#companyID').val();
    var fields = [];
    var lengths = 0;
    if (username.length <= 3) {
        fields.push('username');
    }
    if (password.length <= 3) {
        fields.push('password');
    }
    if (companyID.length < 2) {
        fields.push('company ID');
    }
    if (fields.length == 0) {
        $.ajax({type: "POST", url: "db/blacklist-verify.php", data: {username: username, password: password, companyID: companyID}, success: function(result) {
            if (result == true) {
                //make their information so they cannot change it
                $('#username').prop('readonly', true);
                $('#password').prop('readonly', true);
                $('#companyID').prop('readonly', true);
                //make the blacklist fields editable
                $('#blacklistFirstName').prop('readonly', false);
                $('#blacklistLastName').prop('readonly', false);
                $('#ip').prop('readonly', false);
                $('#notes').prop('readonly', false);
                verified = true;
            } else {
                $('#modal-text').html(result);
                $("#myModal").modal();
                //safety procaution
                //make their information so they cannot change it
                $('#username').prop('readonly', false);
                $('#password').prop('readonly', false);
                $('#companyID').prop('readonly', false);
                //make the blacklist fields editable
                $('#blacklistFirstName').prop('readonly', true);
                $('#blacklistLastName').prop('readonly', true);
                $('#ip').prop('readonly', true);
                $('#notes').prop('readonly', true);
            }
        }});
    } else {
        var message = "The following field(s) must be filled out: ";
        if (fields.length == 1) {
            message += fields[0] + '.';
        } else if (fields.length > 1 && fields.length < 3) {
            message += fields[0] + ' and ' + fields[1] + '.';
        } else {
            for (i = 0; i < fields.length; i++) {
                if (i < fields.length - 1) {
                    message += fields[i] + ', ';
                } else {
                    message += 'and ' + fields[i] + '.';
                }
            }
        }
        $('#modal-text').html(message);
        $("#myModal").modal();
    }
});

function checkFields() {
    var fieldname;
    emply_fields = [];
    var fields = ['username', 'password', 'companyID', 'blacklistFirstName', 'blacklistLastName', 'notes'];
    if (!verified) {
        submitMessage = 'You need to verify your information first.';
        return false;
    } else {
        for (i = 0; i < fields.length; i++) {
            fieldname = fields[i];
            if (document.forms['request-blacklist'][fieldname].value === "") {
                switch (fieldname) {
                    case 'username':
                        fieldname = 'username';
                        break;
                    case 'password':
                        fieldname = 'password';
                        break;
                    case 'companyID':
                        fieldname = 'company ID';
                        break;
                    case 'blacklistFirstName':
                        fieldname = 'blacklist first name';
                        break;
                    case 'blacklistLastName':
                        fieldname = 'blacklist last name';
                        break;
                    case 'notes':
                        fieldname = 'notes';
                        break;
                }
                empty_fields.push(fieldname);
            }
        }
    }
    if (empty_fields.length > 0) {
        return false;
    } else {
        return true;
    }
}

function validate() {
    if (checkFields()) {
        if (confirm('Are you sure you want to block this user permanently from the site?')) {
            return true;
        } else {
            return false;
        }
    } else {
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
    }
}



