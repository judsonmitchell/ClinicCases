import { live } from '../../html/js/live.js';

export const getModal = (id) => {
  return bootstrap.Modal.getInstance(id) || new bootstrap.Modal(id);
};

// close view event modal
live('click', 'close', (e) => {
  const {
    target: {
      dataset: { target: modalId },
    },
  } = e;
  const modal = getModal(modalId);
  modal.hide();
});


