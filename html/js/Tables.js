document.addEventListener('DOMContentLoaded', assignSelectButtons);

function assignSelectButtons() {
  const select_buttons = document.querySelectorAll('.select__button');
  select_buttons.forEach((button) => {
    console.log('event added');
    button.addEventListener('click', toggleSelectOptions);
  });
}

function toggleSelectOptions(e) {
  const id = this.dataset.select;
  const options = document.querySelector(id);
  options.classList.toggle('closed');
  
}
