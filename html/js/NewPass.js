import { changePassword } from '../../lib/javascripts/axios.js';
import { checkFormValidity, getFormValues } from './forms.js';
// $(document).ready(function() {

// 	//"Disable" navigation tabs
// 	$("#tabs a").click(function(event){
// 		event.preventDefault();
// 		notify('<p>Please change your password before proceeding.</p>',true);
// 		return false;
// 	});

// 	//Submit new password
// 	$('button').click(function(event){
// 		event.preventDefault();

// 		var pass = $('input[name="new_pass"]').val();
// 		var passCheck = $('input[name="new_pass_check"]').val();
// 		var error = null;

// 		if (pass != passCheck)
// 			{
// 				$('input').val('');
// 				notify("<p>The passwords you entered do not match</p>",true);
// 			}
// 		else
// 		{
// 			var errors = validPassword(pass);
// 			if (errors.length < 1)
// 				{
// 					//submit the form
// 					$.post('lib/php/auth/change_password.php',{'upgrade' : 'y','pass':pass},function(data){
// 						var serverResponse = $.parseJSON(data);
// 						if (serverResponse.error === true)
// 						{
// 							notify(serverResponse.message, true);
// 						}
// 						else
// 						{
// 							notify(serverResponse.message);
// 							var successText = '<p><b>Password change successful.</b></p><p><a href="index.php?i=Home.php">Continue</a></p>';
// 							$('div.force_new_password_content').html(successText);

// 						}
// 					});
// 				}
// 			else
// 				{
// 					$('input').val('');
// 					notify(errors,true);
// 				}
// 		}
//   });

// });

const checkPasswordStrength = (e) => {
  const password = e.target.value;
  var minCharIndicator = document.getElementById('min-char');
  var uppercaseIndicator = document.getElementById('uppercase');
  var lowercaseIndicator = document.getElementById('lowercase');
  var numberIndicator = document.getElementById('number');

  // Minimum 8 characters
  if (password.length >= 8) {
    minCharIndicator.classList.remove('indicator');
    minCharIndicator.classList.add('valid');
    minCharIndicator.innerHTML = '&#10004; Minimum 8 characters';
  } else {
    minCharIndicator.classList.remove('valid');
    minCharIndicator.classList.add('indicator');
    minCharIndicator.innerHTML = '&#10006; Minimum 8 characters';
  }

  // At least 1 uppercase character
  if (/[A-Z]/.test(password)) {
    uppercaseIndicator.classList.remove('indicator');
    uppercaseIndicator.classList.add('valid');
    uppercaseIndicator.innerHTML = '&#10004; At least 1 uppercase character';
  } else {
    uppercaseIndicator.classList.remove('valid');
    uppercaseIndicator.classList.add('indicator');
    uppercaseIndicator.innerHTML = '&#10006; At least 1 uppercase character';
  }

  // At least 1 lowercase character
  if (/[a-z]/.test(password)) {
    lowercaseIndicator.classList.remove('indicator');
    lowercaseIndicator.classList.add('valid');
    lowercaseIndicator.innerHTML = '&#10004; At least 1 lowercase character';
  } else {
    lowercaseIndicator.classList.remove('valid');
    lowercaseIndicator.classList.add('indicator');
    lowercaseIndicator.innerHTML = '&#10006; At least 1 lowercase character';
  }

  // At least 1 number
  if (/\d/.test(password)) {
    numberIndicator.classList.remove('indicator');
    numberIndicator.classList.add('valid');
    numberIndicator.innerHTML = '&#10004; At least 1 number';
  } else {
    numberIndicator.classList.remove('valid');
    numberIndicator.classList.add('indicator');
    numberIndicator.innerHTML = '&#10006; At least 1 number';
  }
};

const submitNewPasswordForm = async (e) => {
  e.preventDefault();
  const passwordRegex = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/);
  const newPasswordForm = document.getElementById('force_password_change');
  const isValid = checkFormValidity(newPasswordForm);
  const values = getFormValues(newPasswordForm);
  const { new_pass, new_pass_check } = values;
  const passwordsMatch = new_pass == new_pass_check;
  const passwordIsStrong = passwordRegex.test(new_pass);
  if (isValid != true || !passwordsMatch || !passwordIsStrong) {
    newPasswordForm.classList.add('invalid');
    const message = !passwordsMatch
      ? 'Passwords must match'
      : !passwordIsStrong
      ? 'Password is not strong enough'
      : `Please correct the following fields: ${isValid}`;
    alertify.error(message);
    return;
  } else {
    newPasswordForm.classList.remove('invalid');
  }
  try {
    const res = await changePassword(values);
    if (res.error) {
      throw new Error(err.message);
    } else {
      alertify.success(res.message);
      setTimeout(() => {
        const newHref = window.location.href
          .toLowerCase()
          .replace('/index.php?i=new_pass.php', "/index.php?i=Home.php");
        window.location.href = newHref;
      }, 1000);
    }
  } catch (err) {
    alertify.error(err.message);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const submit_new_password = document.querySelector('.submit_new_password');
  submit_new_password.addEventListener('click', submitNewPasswordForm);
  const passwordInput = document.getElementById('new_pass');
  passwordInput.addEventListener('input', checkPasswordStrength);
});
