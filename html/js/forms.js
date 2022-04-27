/**
 * This file contains utility functions for forms.
 * Import this on any page that uses forms.
 */

// For styling floating labels
document.addEventListener('DOMContentLoaded', setUpFloatingLabelStyles);

function setUpFloatingLabelStyles() {
  const inputs = [
    ...document.querySelectorAll('input'),
    ...document.querySelectorAll('textarea'),
  ];
  inputs.forEach((input) => {
    input.addEventListener('focus', () => {
      const lableId = input.dataset.label;
      const labelEl = document.querySelector(lableId);
      labelEl.classList.add('float');
    });
    input.addEventListener('blur', () => {
      const lableId = input.dataset.label;
      const labelEl = document.querySelector(lableId);
      if (!input.value) {
        labelEl.classList.remove('float');
      }
    });
  });
}

// Returns a json object of form values
function getFormValues(form) {
  const elements = [...form.elements];
  const values = elements.reduce((obj, current) => {
    console.log({ obj });
    obj[current.name] = current.value;
    return obj;
  }, {});

  return values;
}

const checkFormValidity = (form) => {
  const isValid = form.checkValidity();
  const invalidFields = [];
  if (isValid) {
    return true;
  } else {
    form.elements.forEach((el) => {
      if (!el.checkValidity()) {
        invalidFields.push(el.name);
      }
    });
    return invalidFields.join(', ');
  }
};

const resetForm = (form) => {
  const elements = [...form.elements];
	form.classList.remove('invalid');
  elements.forEach((el) => {
    el.value = '';
  });
};
