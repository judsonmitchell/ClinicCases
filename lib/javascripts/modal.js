import { live } from '../../html/js/live.js';

export const getModal = (id) => {
  return bootstrap.Modal.getInstance(id) || new bootstrap.Modal(id);
};

console.log('modal');
// close view event modal
live('click', 'close', (e) => {
  console.log('click');
  const {
    target: {
      dataset: { target: modalId },
    },
  } = e;
  console.log({ modalId });
  const modal = getModal(modalId);
  modal.hide();
});
