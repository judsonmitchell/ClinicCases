import { live } from './live.js';
import {
  deleteCaseNote,
  reloadCaseNoteData,
  processCaseNotes,
} from '../../lib/javascripts/axios.js';
import {
  getFormValues,
  checkFormValidity
} from '../../html/js/forms.js';

// search case notes
live('change', 'case_notes_search', async (event) => {
  const value = event.target.value;
  const id = event.target.dataset.caseid;
  reloadCaseNoteData(id, value);
});

// save new case note
live('click', 'case_note_add_save', async (event) => {
  event.preventDefault();
  const el = event.target;
  const id = el.dataset.caseid;
  const form = el.closest('form');
  const data = getFormValues(form);
  const isValid = checkFormValidity(form);
  const noTime = data.csenote_hours == 0 && data.csenote_minutes == 0;
  if (noTime) {
    form.classList.add('invalid');
    alertify.error('Please provide a time');
    return;
  }
  if (isValid != true) {
    form.classList.add('invalid');
    alertify.error(`Please correct invalid fields.`);
    return;
  }
  const response = await processCaseNotes(data);
  if (response.data.error) {
    alertify.error(response.data.error);
  } else {
    alertify.success(response.data.message);
    closeNewCaseNoteModal();
    reloadCaseNoteData(id);
    resetForm(form);
  }
});

// cancel case notes add form
live('click', 'case_note_add_cancel', (event) => {
  event.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose your data.',
    () => {
      closeNewCaseNoteModal();
    },
    null,
  );
});

// Show more for the case notes descriptions
live('click', 'case_note_description', function () {
  const parent = this.closest('.case_note');
  parent.classList.toggle('case_note--closed');
});

// Toggle the edit form for cases
live('click', 'case_note_edit', function (event) {
  event.preventDefault();
  const parent = this.closest('.case_note');
  const details = parent.querySelector('.case_note_description');
  const edit = parent.querySelector('.case_note_form');
  const actions = parent.querySelector('.case_note_actions');
  details?.classList.add('hidden');
  actions?.classList.add('hidden');
  edit?.classList.remove('hidden');
});

// cancel edit case form
live('click', 'case_note_form_cancel', async function (event) {
  event.preventDefault();
  const parent = this.closest('.case_note');
  const details = parent.querySelector('.case_note_description');
  const edit = parent.querySelector('.case_note_form');
  const actions = parent.querySelector('.case_note_actions');
  details?.classList.remove('hidden');
  actions?.classList.remove('hidden');
  edit?.classList.add('hidden');
});

// modify case (submit case note edit form)
live('click', 'case_note_form_save', async function (event) {
  event.preventDefault();
  const form = this.closest('form');
  const data = getFormValues(form);
  try {
    const response = await processCaseNotes(data);
    alertify.success(response.message);
  } catch (err) {
    alertify.error(err.message);
  } finally {
    const { csenote_case_id: id } = data;
    reloadCaseNoteData(id);
  }
});

// delete case note
live('click', 'case_note_delete', async function (event) {
  event.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to delete this case note?',
    async () => {
      const parent = this.closest('.case_note');
      const form = parent.querySelector('form');
      const data = getFormValues(form);
      const { csenote_casenote_id, csenote_case_id } = data;
      const deleteResponse = await deleteCaseNote(csenote_casenote_id).then(
        (res) => res.data,
      );
      if (deleteResponse.message) {
        alertify.success(deleteResponse.message);
        reloadCaseNoteData(csenote_case_id);
      } else {
        alertify.error(deleteResponse.error || 'Error deleting case note.');
      }
    },
    null,
  );
});

function closeNewCaseNoteModal() {
  const newCaseModal = bootstrap.Modal.getInstance(
    document.querySelector('#newCaseNoteModal'),
  );

  // formContainer.classList.add('hidden');
  newCaseModal.hide();
}
