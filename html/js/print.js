import { live } from './live.js';

// print case
live('click', 'print-button', async function (event) {
  event.preventDefault();
  const container = this;
  console.log(this);
  const printTarget = document.querySelector(container.dataset.print);
  const filename = container.dataset.filename;
  const copyOfTarget = printTarget.cloneNode(true);
  copyOfTarget.classList.add('pdf');
  printPDF();
  function printPDF() {
    html2pdf().from(copyOfTarget).save(filename);
  }
});
