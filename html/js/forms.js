/**
 * This file contains utility functions for forms.
 * Import this on any page that uses forms.
 */

// Returns a json object of form values
export const getFormValues = (form) => {
  const elements = [...form.elements];
  const values = elements.reduce((obj, current) => {
    obj[current.name] = current.value;
    return obj;
  }, {});

  return values;
}

export const checkFormValidity = (form) => {
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


export const setFormValues = (form, values) => {
  const keys = Object.keys(values);
  // keys.forEach(key => {
  //   const el = els.find(el => el.name = key);
  //   if(el){
  //     el.value = values[key]
  //   }
  // })
  console.log(values);
  form.elements.forEach(el => {
    const key = keys.find(key => el.name === key);
    if(key){
      el.value = values[key];
    }
  })

  console.log(form);

}