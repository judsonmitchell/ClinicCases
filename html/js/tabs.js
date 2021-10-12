window.addEventListener('DOMContentLoaded', (event) => {
  const toggleButton = document.querySelector('.header__toggle');
  if (!toggleButton) return;
  toggleButton.addEventListener('click', toggleMenu);

  function toggleMenu() {
    const menu = document.querySelector('.header ul');
    if (!menu) return;
    menu.classList.toggle('collapsed');
  }
});
