import { getClosest, live } from '../../html/js/live.js';

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
    [...form.elements].forEach((el) => {
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

const getDeleteButton = (index) => {
  const button = document.createElement('button');
  button.dataset.container = '#caseForm';
  button.dataset.add = index;
  button.classList.add('button__icon');
  button.classList.add('delete-item-button');

  button.innerHTML = `
  <img src="html/ico/delete.png" alt="Delete input">
  `;
  return button;
};
const addNewItem = (button) => {
  const containerId = button?.dataset?.container;
  const shouldPrepend = button?.dataset?.shouldprepend;
  const container = document.querySelector(containerId);
  // Clone the dual form control
  const elementToClone = button.closest('.form-control__dual');
  const newElement = elementToClone.cloneNode(true);
  // Reset the inputs' values and attributes
  const newElementInputs = newElement.querySelectorAll('input', 'select');
  newElementInputs.forEach((el) => {
    el.value = '';
    el.required = false;
  });

  // replace all plus signs with delete signs
  const allElements = container.querySelectorAll('.form-control__dual');
  allElements.forEach((el, index) => {
    const addItemButton = el.querySelector('.add-item-button');
    const inputs = el.querySelectorAll('input');
    inputs.forEach((input) => input.setAttribute('required', true));
    addItemButton?.remove();
    if (!el.querySelector('.delete-item-button')) {
      const deleteItemButton = getDeleteButton(index);
      el.append(deleteItemButton);
    }
  });

  if (shouldPrepend) {
    container.insertBefore(newElement, container.firstChild);
  } else {
    // add to the container
    container.append(newElement);
  }
};

const getDualInputValues = (dualInputs) => {
  const val = dualInputs.reduce((values, el) => {
    const select = el.querySelector('select');
    const input = el.querySelector('input');
    const name = input.name;
    if (input.value) {
      if (values[name]) {
        values[name] = JSON.parse(values[name]);
        values[name][input.value] = select.value;
      } else {
        values[name] = { [input.value]: select.value };
      }
      values[name] = JSON.stringify(values[name]);
    }
    return values;
  }, {});
  return val || undefined;
};

const setFormValues = (form, values) => {
  const keys = Object.keys(values);

  form?.elements?.forEach((el) => {
    const key = keys.find((key) => el.name === key);
    if (key) {
      if (el.type === 'checkbox') {
        el.checked = values[key];
        console.log(el);
        console.log(el.checked);
        console.log(values[key], key);
      } else {
        el.value = values[key];
      }
    }
  });
};

const deleteItem = (el) => {
  const button = el.closest('.form-control__dual');
  button?.remove();
};
live('click', 'add-item-button', (e, el) => {
  e.preventDefault();
  addNewItem(el);
});
live('click', 'delete-item-button', (e, el) => {
  e.preventDefault();
  deleteItem(el);
});
// Create a custom method to check form validity
HTMLFormElement.prototype.validate = function (arrs) {
  // Check regular fields
  const isValid = this.checkValidity();
  const invalidFields = [];
  const allElements = [this];
  const invalidElements = [];

  // Check special fields (ckEditors and slimselects)
  arrs?.forEach((arr) => {
    allElements.push(arr.el);
    console.log({ arr });
    if (!arr.condition) {
      invalidFields.push(arr.name);
      invalidElements.push(arr.el);
      if (arr.message) {
        alertify.error(arr.message);
      }
    } else {
    }
  });

  // If everything is valid, remove invalid class from everything and return
  if (isValid && !invalidFields.length) {
    allElements.forEach((el) => {
      el.classList.remove('invalid');
      el.removeAttribute('invalid');
    });
    return true;
  } else {
    this.classList.add('invalid');

    // Add "regular" invalid elements to list
    [...this.elements].forEach((el) => {
      if (!el.checkValidity()) {
        invalidFields.push(el.name);
      }
    });

    invalidElements.forEach((el) => {
      el.classList.add('invalid');
      el?.setAttribute('invalid', true);
    });

    alertify.error(
      `Please correct the following invalid fields: ${invalidFields.join(
        ', ',
      )}`,
    );
    return false;
  }
};

export {
  setFormValues,
  getDualInputValues,
  addNewItem,
  resetForm,
  checkFormValidity,
  getFormValues,
};
