/**
 * This file contains utility functions for forms.
 */

// Returns a json object of form values
const getFormValues = (form) => {
  const elements = [...form.elements];
  const values = elements.reduce((obj, current) => {
    if (current.type === 'checkbox') {
      obj[current.name] = current.checked;
    } else {
      obj[current.name] = current.value;
    }

    return obj;
  }, {});

  return values;
};

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

const addNewItem = (button) => {
  const containerId = button?.dataset?.container;
  const container = document.querySelector(containerId);
  const elementToClone = container.querySelector('.form-control__dual');
  const newElement = elementToClone.cloneNode(true);
  const newElementInputs = newElement.querySelectorAll('input', 'select');
  newElementInputs.forEach((el) => {
    el.value = '';
    el.required = false;
  });
  container.append(newElement);
};

const getDualInputValues = (dualInputs) => {
  return dualInputs.reduce((values, el) => {
    const select = el.querySelector('select');
    const input = el.querySelector('input');
    const name = input.name;
    if (values[name]) {
      values[name] = JSON.parse(values[name]);
      values[name][input.value] = select.value;
    } else {
      values[name] = { [input.value]: select.value };
    }
    values[name] = JSON.stringify(values[name]);
  }, {});
};

const setFormValues = (form, values) => {
  const keys = Object.keys(values);

  form?.elements?.forEach((el) => {
    const key = keys.find((key) => el.name === key);
    if (key) {
      if (el.type === 'checkbox') {
        el.checked = values[key];
        console.log(el);
        console.log(el.checked)
        console.log(values[key], key)
      } else {
        el.value = values[key];
      }
    }
  });
};

export {
  setFormValues,
  getDualInputValues,
  addNewItem,
  resetForm,
  checkFormValidity,
  getFormValues,
};
