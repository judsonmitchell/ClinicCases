/* global notify, validNewAccount */

document.addEventListener('DOMContentLoaded', () => {
  const submitButton = document.querySelector('#sbmt');
  const formVals = submitButton.closest('form');

  submitButton.addEventListener('click', (event) => {
    event.preventDefault();
    var errString = validNewAccount(formVals);
    if (errString.length) {
      notify(errString, true);
      const error = formVals.querySelector('.ui-state-error');
      error.addEventListener('click', () => {
        error.classList.remove('ui-state-error');
      });
      return false;
    } else {
      var formValsArray = new FormData(formVals);
      submitForm(formValsArray);
    }
  });

  async function submitForm(formValsArray) {
    console.log({ formValsArray });
    try {
      // Fetch the inital state column visibility information
      const registerResponse = await axios.post(
        `../lib/php/users/new_account_process.php`,
        formValsArray,
        {
          headers: {
            'Content-type': 'application/json',
          },
        }
      );
      const data = registerResponse.data;
      notify(data, false);
      formVals.remove();
      const newAccountRight = document.querySelector('div.new_account_right');
      const newAccountLeft = document
        .querySelector('div.new_account_left p')
        .remove();
      newAccountRight.innerHTML = data;
    } catch (error) {
      notify(error.message, true);
    }
  }
  
});

