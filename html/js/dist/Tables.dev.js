"use strict";

document.addEventListener('DOMContentLoaded', assignSelectButtons);

function assignSelectButtons() {
  var select_buttons = document.querySelectorAll('.select__button');
  select_buttons.forEach(function (button) {
    button.addEventListener('click', toggleSelectOptions);
  });
}

function toggleSelectOptions(e) {
  var id = this.dataset.select;
  var options = document.querySelector(id);
  options.classList.toggle('closed');
}