/*
    characterHandling.js
    
    This JS file is used for the backend 
    for validating form inputs.
*/

//Only letters are allowed
function lettersOnly(input) {
    var regex = /[^a-z-]/gi;
    input.value = input.value.replace(regex, "");
}
//Some symbols are allowed
function someSymbols(input) {
    // _ is allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";'<>?@,.\/]/;
    input.value = input.value.replace(regex, "");
    input.value = input.value.replace(/ /g, "_");
}
//3 symbols are allowed
function orgSymbols(input) {
    // _ and . and , and ' are allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";<>?@\/]/;
    input.value = input.value.replace(regex, "");
}
//Email symbols are allowed
function emailSymbols(input) {
    // _ and . and @ are allowed
    var regex = /[-!$%^&*()+|~=`{}\[\]:";'<>?,\/]/;
    input.value = input.value.replace(regex, "");
}
//Only numbers are allowed
function numbersOnly(input) {
    //only numbers
    var regex = /[^0-9]/;
    input.value = input.value.replace(regex, "");
}