/* global notify, validNewAccount */

import { checkFormValidity, getFormValues } from '../html/js/forms.js';

document.addEventListener('DOMContentLoaded', () => {
  const submitButton = document.querySelector('#sbmt');

  submitButton.addEventListener('click', (event) => {
    event.preventDefault();
    const form = submitButton.closest('form');
    const values = getFormValues(form);
    const isValid = checkFormValidity(form);
    if (isValid != true) {
      form.classList.add('invalid');
      alertify.error(`Please correct the following fields: ${isValid}`);

      return;
    }
    if (values.password != values.confirmPassword) {
      form.classList.add('invalid');
      document.querySelector('#password').classList.add('invalid');
      document.querySelector('#confirmPassword').classList.add('invalid');
      alertify.error(`Your passwords must match`);
      return;
    }
    form.classList.remove('invalid');
    document.querySelector('#password').classList.remove('invalid');
    document.querySelector('#confirmPassword').classList.remove('invalid');
    submitForm(values);
  });

  async function submitForm(formValsArray) {
    try {
      // Fetch the inital state column visibility information
      const registerResponse = await axios.post(
        `../lib/php/users/new_account_process.php`,
        formValsArray,
        {
          headers: {
            'Content-type': 'application/json',
          },
        },
      );

      const data = registerResponse.data;

      if (data.error) {
        throw new Error(data.message);
      } else {
        alertify.success(data.message);
        document.querySelector('#newAccount').innerHTML = data.html;
      }
    } catch (error) {
      alertify.error(error.message);
    }
  }
});
