import { live } from './live.js';

// search case notes
live('change', 'case_notes_search', async (event) => {
  const value = event.target.value;
  const id = event.target.dataset.caseid;
  reloadCaseData(id, value);
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
    reloadCaseData(id);
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
    const response = await axios
      .post(`lib/php/data/cases_casenotes_process.php`, data)
      .then((res) => res.data);
    alertify.success(response.message);
  } catch (err) {
    alertify.error(err.message);
  } finally {
    const { csenote_case_id: id } = data;
    reloadCaseData(id);
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
        reloadCaseData(csenote_case_id);
      } else {
        alertify.error(deleteResponse.error || 'Error deleting case note.');
      }
    },
    null,
  );
});

function getCaseNotes(id, update, search) {
  let body = { case_id: id };
  if (update) {
    body = { ...body, update: true, search };
  }
  return axios.post(`lib/php/data/cases_casenotes_load.php`, body, {
    headers: {
      'Content-type': 'application/json',
    },
  });
}

function deleteCaseNote(caseNoteId) {
  const body = {
    query_type: 'delete',
    csenote_casenote_id: caseNoteId,
  };
  return axios.post(`lib/php/data/cases_casenotes_process.php`, body, {
    headers: {
      'Content-type': 'application/json',
    },
  });
}

async function reloadCaseData(id, value = '') {
  const response = await getCaseNotes(id, true, value);
  const notesContainer = document.querySelector(
    `#nav-${id}-notes .case_detail_panel_casenotes`,
  );
  notesContainer.innerHTML = response.data;
}

function processCaseNotes(data) {
  return axios.post(`lib/php/data/cases_casenotes_process.php`, data);
}
function closeNewCaseNoteModal() {
  const newCaseModal = bootstrap.Modal.getInstance(
    document.querySelector('#newCaseNoteModal'),
  );

  // formContainer.classList.add('hidden');
  newCaseModal.hide();

}
