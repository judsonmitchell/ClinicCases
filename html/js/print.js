import { live } from './live.js';

// print case
live('click', 'print-button', async function (event, container) {
  event.preventDefault();
  try {
    alertify.message("Starting print...")
    const printTarget = document.querySelector(container.dataset.print);
    const filename = container.dataset.filename;
    const copyOfTarget = printTarget.cloneNode(true);
    copyOfTarget.classList.add('pdf');
    
    printPDF();
    function printPDF() {
      html2pdf()
        .set({ html2canvas: { scale: 1, scrollY: 0 }, filename })
        .from(copyOfTarget)
        .save();
    }
  } catch (err) {
    alertify.error(err.message);
  }
});
