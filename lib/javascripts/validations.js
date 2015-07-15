//Functions to validate forms

//Validate case note submitted from the Home page quick add widget
/* global notify */

var errString;
var emailFilter = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/igm ;
var phoneFilter = /^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/gm;

function validQuickCaseNote (cseVals) {
    var errors = [];

    //check description
    if (cseVals[4].value === '')
        {errors.push('<p>Please provide a description of what you did.</p>');}

    //check if the time entered is greater than 0 hours and 0 minutes
    if (cseVals[2].value === '0' && cseVals[3].value === '0') {
        errors.push('<p>Please indicate the amount of time for this activity.</p>');
    }
    errString = errors.join(' ');
    return errString;
}


//Validate case note submitted from the Cases page
function validCaseNote(cseVals) {

	var errors = [];

	//check if a description has been put in the textarea
	if (cseVals[6].value === 'Describe what you did...' || cseVals[6].value === '') {
        errors.push('<p>Please provide a description of what you did.</p>');
    }

	//check if the time entered is greater than 0 hours and 0 minutes
	if (cseVals[1].value === '0' && cseVals[2].value === '0') {
        errors.push('<p>Please indicate the amount of time for this activity.</p>');
    }
	errString = errors.join(' ');
	return errString;

}

function validContact(contactVals) {
    var errors = [];

    //check to see if the contact has a name or organization
    var cFirst = contactVals.find('input[name="first_name"]').val();
    var cLast = contactVals.find('input[name="last_name"]').val();
    var cOrg = contactVals.find('input[name="organization"]').val();
    if (cFirst === '' && cLast === '' && cOrg === '') {
        errors.push('<p>Please provide a name or organization for this contact.</p>');
    }
    if (contactVals.find('input[name="phone"]').val() !== ''){
        var phone = contactVals.find('input[name="phone"]');
        phone.each(function(){
            if (!$(this).val().trim().match(phoneFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone number appears invalid.</p>');
            }
        });
    }

    if (contactVals.find('input[name="email"]').val() !== ''){
        var email = contactVals.find('input[name="email"]');
        email.each(function(){
            if ($(this).val() !== '') {
                if (!$(this).val().trim().match(emailFilter)) {
                    $(this).addClass('ui-state-error');
                    errors.push('<p>Email appears invalid</p>');
                }
            }
        });
    }

    errString = errors.join(' ');
    return errString;
}

function validEvent(eventVals) {
    var errors = [];
    if ($.isEmptyObject(eventVals[0])) {
        errors.push('<p>You must select at least one responsible person.</p>');
    }

    if (eventVals[1].value === '') {
        errors.push('<p>Please provide a name ("What") for this event.</p>');
    }

    if (eventVals[3].value === '') {
        errors.push('<p>Please provide a start time for this event.</p>');
    }
    errString = errors.join(' ');
    return errString;
}

function validUser(formVals) {
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var email = formVals.find('input[name="email"]');
    var phone = formVals.find('input[name*="phone"]');
    var group = formVals.find('select[name="grp"]');

    if (fname.val() === '') {
        errors.push('<p>Please provide a first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '') {
        errors.push('<p>Please provide a last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '') {
        errors.push('<p>An email account is required for each user.</p>');
        email.addClass('ui-state-error');
    } else {
        if (!email.val().trim().match(emailFilter)) {
            errors.push('<p>Email appears invalid</p>');
            email.addClass('ui-state-error');
        }
    }

    phone.each(function(){
        if ($(this).val() !=='') {
            if (!$(this).val().trim().match(phoneFilter)){
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone number appears invalid</p>');
            }
        }
    });

    if (group.val() === '') {
        errors.push('<p>You must assign the user to a group.</p>');
        group.next().addClass('ui-state-error');
    }
    errString = errors.join(' ');
    return errString;
}

function validProfile(formVals) {
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var phone = formVals.find('input[name*="phone"]');
    var email = formVals.find('input[name="email"]');

    if (fname.val() === '') {
        errors.push('<p class="pref_error">You must provide your first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '') {
        errors.push('<p class="pref_error">You must provide your last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '') {
        errors.push('<p class="pref_error">You must provide an email address.</p>');
        email.addClass('ui-state-error');
    } else {
        if (!email.val().trim().match(emailFilter)) {
            errors.push('<p class="pref_error">Email appears invalid</p>');
            email.addClass('ui-state-error');
        }
    }

    phone.each(function(){
        if ($(this).val() !=='') {
            if (!$(this).val().trim().match(phoneFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p class="pref_error">Phone number appears invalid.</p>');
            }
        }
    });
    errString = errors.join(' ');
    return errString;
}

function validNewAccount(formVals) {
    var errors = [];
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var phone = formVals.find('input[name*="phone"]');
    var mobile = formVals.find('input[name="mobile_phone"]');
    var email = formVals.find('input[name="email"]');
    var password = formVals.find('input[name="password"]');
    var passwordConfirm = formVals.find('input[name="confirm_password"]');

    if (password.val() === '') {
        errors.push('<p>You must provide a password .</p>');
        password.addClass('ui-state-error');
    }

    if (password.val() !== passwordConfirm.val()) {
        errors.push('<p>The passwords you entered do not match.</p>');
        password.addClass('ui-state-error');
        passwordConfirm.addClass('ui-state-error');
    }

    if (fname.val() === '') {
        errors.push('<p>You must provide your first name.</p>');
        fname.addClass('ui-state-error');
    }

    if (lname.val() === '') {
        errors.push('<p>You must provide your last name.</p>');
        lname.addClass('ui-state-error');
    }

    if (email.val() === '') {
        errors.push('<p>You must provide an email address.</p>');
        email.addClass('ui-state-error');
    } else {
        if (!email.val().trim().match(emailFilter)) {
            errors.push('<p>Email appears invalid</p>');
            email.addClass('ui-state-error');
        }
    }

    if (mobile.val() === '') {
        errors.push('<p>You must provide a mobile phone number.</p>');
        fname.addClass('ui-state-error');
    }

    phone.each(function(){
        if ($(this).val() !=='') {
            if (!$(this).val().trim().match(phoneFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone numbers can contain only numbers and dashes</p>');
            }

            if ($(this).val().length < 10 && $(this).val().length > 1) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Please ensure that the phone number contains an area code.</p>');
            }
        }
    });

    if (password.val().length < 8) {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password.val()) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password.val()) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password.val()) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }
    errString = errors.join(' ');
    return errString;
}


//Validations for add document by url
function isUrl(s) { //used in cc7
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(s);
}


function isTitle(title) {//used in cc7

    if (title === '') {
        $('title').style.background='yellow';
        notify('You must supply a title.',true);
        return false;
    } else {
        return true;
    }

}

function validPassword(password) {
    var errors = [];
    if (password.length < 8) {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }
    errString = errors.join(' ');
    return errString;
}

function validPasswordChange(formVals) {
    var errors = [];
    var password = formVals.find('input[name="new_pword"]').val();
    var passwordEnter = formVals.find('input[name="new_pword"]');
    var passwordConfirm = formVals.find('input[name="new_pword_confirm"]');

    //Check if all fields are filled in
    var missingField = null;

    formVals.find('input[name*="pword"]').each(function(){
        if ($(this).val() === '') {
            missingField = true;
        }
    });

    if (missingField === true) {
        errors.push('<p>You must complete all three fields.</p>');
    }

    //Check if passwords match
    if (passwordEnter.val() !== passwordConfirm.val()) {
        errors.push('<p>The new passwords do not match.  Please try again.</p>');
        formVals.find('input[name*="new"]').addClass('ui-state-error');
        formVals.find('input[name="new_pword"]').click(function(){
                formVals.find('input[name*="new"]').removeClass('ui-state-error').val('');
            });
    }

    if (password.length < 8) {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password) === false) {
        errors.push('<p>Your password must contain at least one number.</p>');
    }
    errString = errors.join(' ');
    return errString;
}

function newCaseValidate(formVals) {
    var errors = [];
    //These fields are required for CC to work, marked 1 in cm_columns
    var fname = formVals.find('input[name="first_name"]');
    var lname = formVals.find('input[name="last_name"]');
    var org  = formVals.find('input[name="organization"]');
    var adv = formVals.find('input[name="adverse_parties"]');

    if (fname.val() === '' && lname.val() === '' && org.val() === '') {
        errors.push('<p>Please provide either a client name or organization name.</p>');
        fname.addClass('ui-state-error');
        lname.addClass('ui-state-error');
        org.addClass('ui-state-error');
    }

    //This is a crude attempt to stop users from putting
    //any data in adverse parties field that is not a name
    adv.each(function(){
        if ($(this).val() !== '') {
            String.prototype.countWords = function(){
                return this.split(/\s+/).length;
            };

            var numberOfWords = $(this).val().countWords();

            if (numberOfWords > 5) {//because nobody has a name with more than five words, right?
                $(this).addClass('ui-state-error');
                errors.push('<p>Please ensure that the adverse parties field has only proper names</p>');
            }
        }
    });

    //These fields are not required. May be removed by user
    if (formVals.find('input[name="ssn"]').length > 0){
        var ssn = formVals.find('input[name="ssn"]');
        if (ssn.val() !== '') {
            var stripped = ssn.val().replace(/[\(\)\.\-\ ]/g, '');
            var isNum = /^\d+$/.test(stripped);

            if (isNum === false) {
                errors.push('<p>Social Security Numbers must contain numbers only.</p>');
                ssn.addClass('ui-state-error');

            }
            else if (stripped.length !== 9) {
                errors.push('<p>This SSN appears to be invalid.  Please check it.</p>');
                ssn.addClass('ui-state-error');
            }
        }
    }

    if (formVals.find('input[name="dob"]').length > 0){
        var dob = formVals.find('input[name="dob"]');
        if(dob.val() !== '') {
            var dobVal = formVals.find('input[name="dob"]').val();
            var currentDate = new Date();
            var year = currentDate.getFullYear();
            if (dobVal.charAt(2) !== '/' || dobVal.charAt(5) !== '/') {
                errors.push('<p>DOB must be in the mm/dd/yyyy format.</p>');
                dob.addClass('ui-state-error');
            }

            var parts = [];
            parts = dobVal.split('/');

            if (parseInt(parts[1]) > 31 || parseInt(parts[1] < 1)) {
                errors.push('<p>DOB Day must be between 1 and 31.</p>');
                dob.addClass('ui-state-error');
            }

            if (parts[2] >  year || parseInt(parts[2]) < 1899) {
                errors.push('<p>DOB Year must be between 1899 and ' + year + '.</p>');
                dob.addClass('ui-state-error');
            }

            var okMonths = new Array('01','02','03','04','05','06','07','08','09','10','11','12');
            var checker = false;
            for(var i=0;i < okMonths.length;i++){
                if(parts[0] === okMonths[i]){
                    checker = true;
                    break;
                }
            }

            if (checker === false) {
                errors.push('<p>DOB Months must be in the mm format, e.g 01,02,03 etc.</p>');
                dob.addClass('ui-state-error');
            }

        }
    }

    var phone = formVals.find('input[name="phone"]');
    phone.each(function(){
        if ($(this).val() !== '') {
            if (!$(this).val().trim().match(phoneFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Phone number appears invalid.</p>');
            }
        }
    });

    var email = formVals.find('input[name="email"]');
    email.each(function(){
        if ($(this).val() !== '') {
            if (!$(this).val().trim().match(emailFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Email appears invalid</p>');
            }
        }
    });

    errString = errors.join(' ');

    return errString;
}

