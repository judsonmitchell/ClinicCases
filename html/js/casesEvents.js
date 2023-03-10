//
// Scripts for events panel on cases
//
import { getClosest, live } from './live.js';

import {
  getCaseEventData,
  getUserChooserList,
  processEvents,
} from '../../lib/javascripts/axios.js';
import { getModal } from '../../lib/javascripts/modal.js';
import {
  checkFormValidity,
  getFormValues,
  resetForm,
  setFormValues,
} from './forms.js';

const slimSelect = new SlimSelect({
  select: '.new_event_slim_select',
});
const newEventForm = document.querySelector(`#newEventModal form`);
const newEventModal = getModal('#newEventModal');

const reloadCaseEvents = async (case_id, q) => {
  const html = await getCaseEventData(case_id, q);
  const eventsContainer = document.querySelector(`#nav-${case_id}-events`);
  eventsContainer.innerHTML = html;
};

// Search events
live('change', 'case_events_search', async (event) => {
  const el = event.target;
  const search = el.value;
  const case_id = el.dataset.caseid;
  await reloadCaseEvents(case_id, search);
});

// clear search
live('click', 'case_events_search_clear', (e) => {
  const { caseid: case_id } = e.target.dataset;
  reloadCaseEvents(case_id);
});

live('click', 'events_new', async (e) => {
  const newEventButton =
    e.target.classList.contains('events_new') ||
    e.target.closest('.events_new');

  const { caseid: case_id } = newEventButton.dataset;
  setFormValues(newEventForm, { case_id });
  const responsibles_selector = document.querySelector(
    '.new_event_slim_select',
  );
  const usersList = await getUserChooserList(case_id);
  responsibles_selector.innerHTML = usersList;

  newEventModal.show();
});

live('click', 'new_event_submit', async () => {
  let values = getFormValues(newEventForm);
  const isValid = checkFormValidity(newEventForm);
  const responsibles = slimSelect.selected();
  const slim_select = document.querySelector('.new_event_slim_select');
  if (isValid != true || !responsibles.length) {
    newEventForm.classList.add('invalid');
    alertify.error(
      `Please correct the following fields: ${isValid} ${
        !responsibles.length ? 'responsibles' : ''
      }`,
    );
    if (!responsibles.length) {
      slim_select.classList.add('invalid');
    }
    return;
  }

  newEventForm.classList.remove('invalid');
  slim_select.classList.remove('invalid');

  values = { ...values, responsibles };
  const { case_id } = values;
  try {
    const res = await processEvents({ action: 'add', ...values });
    if (res.error) {
      alertify.error(res.message);
    } else {
      alertify.success(res.message);
    }
    await reloadCaseEvents(case_id);
    newEventModal.hide();
  } catch (err) {
    alertify.error(err.message);
  }
});

const caseEventCancelButton = document.querySelector('#newCaseEventCancel');
caseEventCancelButton.addEventListener('click', (e) => {
  e.preventDefault();
  const values = getFormValues(newEventForm);
  const hasValue = Object.keys(values)
    .filter((k) => k != 'case_id')
    .map((key) => (values[key] ? values[key] : ''))
    .join('');
  if (!hasValue && !slimSelect.selected().length) {
    resetForm(newEventForm);
    newEventModal.hide();
    return;
  }
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose all of your data.',
    () => {
      resetForm(newEventForm);
      newEventModal.hide();
    },
    null,
  );
});

// Edit case event
live('click', 'event_edit', async (e) => {
  e.preventDefault();
  const { target } = e.target.dataset;
  const {
    dataset: { id, caseid },
  } = e.target.closest('.case-event');

  const modal = getModal(target);
  let slimSelect = document.querySelector(
    `${target} .edit_event_slim_select`,
  )?.slim;
  if (!slimSelect) {
    slimSelect = new SlimSelect({
      select: `${target} .edit_event_slim_select`,
    });
  }
  const slimSelectContainer = document.querySelector(
    `${target} .edit_event_slim_select`,
  );
  const {
    dataset: { value },
  } = slimSelectContainer;
  const usersList = await getUserChooserList(caseid, value);
  slimSelectContainer.innerHTML = usersList;
  const valAsArray = value?.split(',') || [];
  // need to give slim select a milisecond to register the data
  setTimeout(() => {
    slimSelect.setSelected(valAsArray);
    modal.show();
  }, [100]);
});

// cancel case event edit

live('click', 'case_event_edit_cancel', (e) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose your data.',
    () => {
      const id = e.target.dataset.target;
      const modal = getModal(`#${id}`);
      modal.hide();
    },
    null,
  );
});

// submit case event edit
live('click', 'edit_event_submit', async (e) => {
  const id = e.target.dataset.target;
  const editEventModal = getModal(`#${id}`);
  const editEventForm = document.querySelector(`#${id} form`);
  let values = getFormValues(editEventForm);
  const isValid = checkFormValidity(editEventForm);
  const slimSelectRef = editEventForm.querySelector('select');
  const editEventSlimSelect = slimSelectRef.slim;
  const responsibles = editEventSlimSelect.selected();
  if (!responsibles.length) {
    slimSelectRef.classList.add('invalid');
  } else {
    slimSelectRef.classList.remove('invalid');
  }
  if (isValid != true || !responsibles.length) {
    editEventForm.classList.add('invalid');
    alertify.error(
      `Please correct the following fields: ${isValid != true && isValid} ${
        !responsibles.length ? 'responsibles' : ''
      }`,
    );

    return;
  }

  editEventForm.classList.remove('invalid');
  slimSelectRef.classList.remove('invalid');

  values = { ...values, all_day: values.all_day?.toString(), responsibles };
  const { case_id } = values;
  try {
    const res = await processEvents({ action: 'edit', ...values });
    if (res.error) {
      alertify.error(res.message);
    } else {
      alertify.success(res.message);
    }
    await reloadCaseEvents(case_id);
    editEventModal.hide();
  } catch (err) {
    alertify.error(err.message);
  }
});

// open the modal
live('click', 'case-event', (event) => {
  const target = event.target;
  // If clicking delete or edit, don't open the modal
  if (
    target.classList.contains('event_delete') ||
    target.classList.contains('event_edit') ||
    target.classList.contains('case_event_edit_cancel') ||
    target.classList.contains('case_edit_event_save')
  ) {
    return;
  }

  const case_event_el = getClosest(target, '.case-event');
  console.log({ target, case_event_el });
  const event_id = case_event_el.dataset.id;
  const modal = getModal(`#viewEventModal-${event_id}`);

  modal.show();
});

// delete case event
live('click', 'event_delete', async function (event) {
  event.preventDefault();
  const event_id = event.target.dataset.id;
  const case_id = event.target.dataset.caseid;
  alertify.confirm(
    'Confirm',
    'Are you sure you want to delete this event?',
    async () => {
      try {
        const res = await processEvents({ action: 'delete', event_id });
        if (res.error) {
          alertify.error(res.message);
        } else {
          alertify.success(res.message);
        }
        await reloadCaseEvents(case_id);
      } catch (err) {
        alertify.error(err.message);
      }
    },
    null,
  );
});
// close view event modal
live('click', 'close_modal', (e) => {
  const {
    target: {
      dataset: { target: modalId },
    },
  } = e;
  const modal = getModal(modalId);
  modal.hide();
});
