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
    var cFirst = contactVals.querySelector('input[name="first_name"]').value;
    var cLast = contactVals.querySelector('input[name="last_name"]').value;
    var cOrg = contactVals.querySelector('input[name="organization"]').value;
    if (cFirst === '' && cLast === '' && cOrg === '') {
        errors.push('<p>Please provide a name or organization for this contact.</p>');
    }
    if (contactVals.querySelector('input[name="phone"]').value !== ''){
        var phone = contactVals.querySelectorAll('input[name="phone"]');
        phone.forEach((phn) => {
            const value = phn.value;
            if(value) {
                const isValid = value.trim().match(phoneFilter);
                if(!isValid){
                    phn.classList.add('ui-state-error');
                    errors.push('<p>Phone number appears invalid.</p>');
                    
                }
            }

        });
    }

    if (contactVals.querySelector('input[name="email"]').value !== ''){
        var email = contactVals.querySelector('input[name="email"]');
        email.each( eml => {
            const value = eml.value;
            if(value){
                const isValid = value.trim().match(emailFilter);
                if(!isValid){
                    eml.classList.add('ui-state-error');
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
    const isEmpty = JSON.stringify(eventVals[0]) == JSON.stringify({});
    if (isEmpty) {
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
    var fname = formVals.querySelector('input[name="first_name"]');
    var lname = formVals.querySelector('input[name="last_name"]');
    var email = formVals.querySelector('input[name="email"]');
    var phone = formVals.querySelectorAll('input[name*="phone"]');
    var group = formVals.querySelector('select[name="grp"]');

    if (fname.value === '') {
        errors.push('<p>Please provide a first name.</p>');
        fname.classList.add('ui-state-error');
    }

    if (lname.value === '') {
        errors.push('<p>Please provide a last name.</p>');
        lname.classList.add('ui-state-error');
    }

    if (email.value === '') {
        errors.push('<p>An email account is required for each user.</p>');
        email.classList.add('ui-state-error');
    } else {
        if (!email.value.trim().match(emailFilter)) {
            errors.push('<p>Email appears invalid</p>');
            email.classList.add('ui-state-error');
        }
    }

    phone.forEach((phn) => {
        const value = phn.value;
        if(value) {
            const isValid = value.trim().match(phoneFilter);
            if(!isValid){
                phn.classList.add('ui-state-error');
                errors.push('<p>Phone number appears invalid.</p>');
                
            }
        }

    });

    if (group.value === '') {
        errors.push('<p>You must assign the user to a group.</p>');
        group.next().classList.add('ui-state-error');
    }
    errString = errors.join(' ');
    return errString;
}

function validProfile(formVals) {
    var errors = [];
    var fname = formVals.querySelector('input[name="first_name"]');
    var lname = formVals.querySelector('input[name="last_name"]');
    var phone = formVals.querySelectorAll('input[name*="phone"]');
    var email = formVals.querySelector('input[name="email"]');

    if (fname.value === '') {
        errors.push('<p class="pref_error">You must provide your first name.</p>');
        fname.classList.add('ui-state-error');
    }

    if (lname.value === '') {
        errors.push('<p class="pref_error">You must provide your last name.</p>');
        lname.classList.add('ui-state-error');
    }

    if (email.value === '') {
        errors.push('<p class="pref_error">You must provide an email address.</p>');
        email.classList.add('ui-state-error');
    } else {
        if (!email.value.trim().match(emailFilter)) {
            errors.push('<p class="pref_error">Email appears invalid</p>');
            email.classList.add('ui-state-error');
        }
    }

    phone.forEach((phn) => {
        const value = phn.value;
        if(value) {
            const isValid = value.trim().match(phoneFilter);
            if(!isValid){
                phn.classList.add('ui-state-error');
                errors.push('<p>Phone number appears invalid.</p>');
                
            }
        }

    });
    errString = errors.join(' ');
    return errString;
}

function validNewAccount(formVals) {
    var errors = [];
    var fname = formVals.querySelector('input[name="first_name"]');
    var lname = formVals.querySelector('input[name="last_name"]');
    var phone = formVals.querySelectorAll('input[name*="phone"]');
    var mobile = formVals.querySelector('input[name="mobile_phone"]');
    var email = formVals.querySelector('input[name="email"]');
    var password = formVals.querySelector('input[name="password"]');
    var passwordConfirm = formVals.querySelector('input[name="confirm_password"]');

    if (password.value === '') {
        errors.push('<p>You must provide a password .</p>');
        password.classList.add('ui-state-error');
    }

    if (password.value !== passwordConfirm.value) {
        errors.push('<p>The passwords you entered do not match.</p>');
        password.classList.add('ui-state-error');
        passwordConfirm.classList.add('ui-state-error');
    }

    if (fname.value === '') {
        errors.push('<p>You must provide your first name.</p>');
        fname.classList.add('ui-state-error');
    }

    if (lname.value === '') {
        errors.push('<p>You must provide your last name.</p>');
        lname.classList.add('ui-state-error');
    }

    if (email.value === '') {
        errors.push('<p>You must provide an email address.</p>');
        email.classList.add('ui-state-error');
    } else {
        if (!email.value.trim().match(emailFilter)) {
            errors.push('<p>Email appears invalid</p>');
            email.classList.add('ui-state-error');
        }
    }

    if (mobile.value === '') {
        errors.push('<p>You must provide a mobile phone number.</p>');
        fname.classList.add('ui-state-error');
    }


    phone.forEach(
        (phn) => {
            // const value = phn.value;
            // const isValidPattern = value.trim().match(phoneFilter);
            // const isValidLength = value.length < 10 && value.length > 1;
            // if(value && !isValidPattern) {
            //     if(!isValid){
            //         phn.classList.add('ui-state-error');
            //         errors.push('<p>Phone number appears invalid.</p>');
                    
            //     }
            // }
            // if(value && !isValidLength){
            //     phn.classList.add('ui-state-error');
            //     errors.push('<p>Please ensure that the phone number contains an area code.</p>');
            // }
    });

    if (password.value.length < 8) {
        errors.push('<p>Your password is not at least 8 characters long.</p>');
    }

    //UpperCase
    if( /[A-Z]/.test(password.value) === false) {
        errors.push('<p>Your password must contain at least one upper case letter.</p>');
    }

    //Lowercase
    if( /[a-z]/.test(password.value) === false ) {
        errors.push('<p>Your password must contain at least one lower case letter.</p>');
    }

    //Numbers
    if( /\d/.test(password.value) === false) {
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
        const titleEl = documenet.querySelector('title');
        titleEl.style.background='yellow';
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
    var password = formVals.querySelector('input[name="new_pword"]').value;
    var passwordEnter = formVals.querySelector('input[name="new_pword"]');
    var passwordConfirm = formVals.querySelector('input[name="new_pword_confirm"]');

    //Check if all fields are filled in
    var missingField = null;

    formVals.querySelector('input[name*="pword"]').forEach(
        val => {
            if(val == ''){
                missingField = true;
            }
    });

    if (missingField === true) {
        errors.push('<p>You must complete all three fields.</p>');
    }

    //Check if passwords match
    if (passwordEnter.value !== passwordConfirm.value) {
        errors.push('<p>The new passwords do not match.  Please try again.</p>');
        formVals.querySelector('input[name*="new"]').classList.add('ui-state-error');
        formVals.querySelector('input[name="new_pword"]').addEventListener('click', () => {
                const name = formVals.querySelector('input[name*="new"]');
                name.classList.remove('ui-state-error');
                name.value = '';
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
    var fname = formVals.querySelector('input[name="first_name"]');
    var lname = formVals.querySelector('input[name="last_name"]');
    var org  = formVals.querySelector('input[name="organization"]');
    var adv = formVals.querySelector('input[name="adverse_parties"]');

    if (fname.value === '' && lname.value === '' && org.value === '') {
        errors.push('<p>Please provide either a client name or organization name.</p>');
        fname.classList.add('ui-state-error');
        lname.classList.add('ui-state-error');
        org.classList.add('ui-state-error');
    }

    //This is a crude attempt to stop users from putting
    //any data in adverse parties field that is not a name
    adv.forEach( el => {
        const value = el.value;
        if(value != '') {
            String.prototype.countWords = function(){
                return this.split(/\s+/).length;
            };
            var numberOfWords = value.split(' ').length;
            if (numberOfWords > 5) {//because nobody has a name with more than five words, right?
                el.classList.add('ui-state-error');
                errors.push('<p>Please ensure that the adverse parties field has only proper names</p>');
            }

        }
    });

    //These fields are not required. May be removed by user
    if (formVals.querySelector('input[name="ssn"]').length > 0){
        var ssn = formVals.querySelector('input[name="ssn"]');
        if (ssn.value !== '') {
            var stripped = ssn.value.replace(/[\(\)\.\-\ ]/g, '');
            var isNum = /^\d+$/.test(stripped);

            if (isNum === false) {
                errors.push('<p>Social Security Numbers must contain numbers only.</p>');
                ssn.classList.add('ui-state-error');

            }
            else if (stripped.length !== 9) {
                errors.push('<p>This SSN appears to be invalid.  Please check it.</p>');
                ssn.classList.add('ui-state-error');
            }
        }
    }

    if (formVals.querySelector('input[name="dob"]').length > 0){
        var dob = formVals.querySelector('input[name="dob"]');
        if(dob.value !== '') {
            var dobVal = formVals.querySelector('input[name="dob"]').value;
            var currentDate = new Date();
            var year = currentDate.getFullYear();
            if (dobVal.charAt(2) !== '/' || dobVal.charAt(5) !== '/') {
                errors.push('<p>DOB must be in the mm/dd/yyyy format.</p>');
                dob.classList.add('ui-state-error');
            }

            var parts = [];
            parts = dobVal.split('/');

            if (parseInt(parts[1]) > 31 || parseInt(parts[1] < 1)) {
                errors.push('<p>DOB Day must be between 1 and 31.</p>');
                dob.classList.add('ui-state-error');
            }

            if (parts[2] >  year || parseInt(parts[2]) < 1899) {
                errors.push('<p>DOB Year must be between 1899 and ' + year + '.</p>');
                dob.classList.add('ui-state-error');
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
                dob.classList.add('ui-state-error');
            }

        }
    }

    var phone = formVals.querySelector('input[name="phone"]');
    phone.forEach((phn)=> {
        const value = phn.value;
        if(value) {
            const isValid = value.trim().match(phoneFilter);
            if(!isValid){
                phn.classList.add('ui-state-error');
                errors.push('<p>Phone number appears invalid.</p>');
                
            }
        }
    
    });

    var email = formVals.querySelector('input[name="email"]');
    email.each(function(){
        if ($(this).value !== '') {
            if (!$(this).value.trim().match(emailFilter)) {
                $(this).addClass('ui-state-error');
                errors.push('<p>Email appears invalid</p>');
            }
        }
    });

    errString = errors.join(' ');

    return errString;
}

