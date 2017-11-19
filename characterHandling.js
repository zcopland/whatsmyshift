function lettersOnly(input) {
    var regex = /[^a-z]/gi;
    input.value = input.value.replace(regex, "");
}
function someSymbols(input) {
    // _ is allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";'<>?@,.\/]/;
    input.value = input.value.replace(regex, "");
}
function orgSymbols(input) {
    // _ and . and , and ' are allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";<>?@\/]/;
    input.value = input.value.replace(regex, "");
}
function emailSymbols(input) {
    // _ and . and @ are allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";'<>?,\/]/;
    input.value = input.value.replace(regex, "");
}
function numbersOnly(input) {
    //only numbers
    var regex = /[^0-9]/;
    input.value = input.value.replace(regex, "");
}