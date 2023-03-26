import { getModal } from '../../lib/javascripts/modal.js';
import { live } from './live.js';
import { checkFormValidity, getFormValues, resetForm } from './forms.js';
import {
  loadHomeEvents,
  loadHomeActivities,
  processCaseNotes,
  processEvents,
} from '../../lib/javascripts/axios.js';

let caseNoteHTML;
let eventHTML;
let calendar;
const quickAddContent = document.querySelector('#quickAddContent');
const quickAddCaseNoteForm = document.querySelector('#quickAddCaseNote');
const quickAddEventForm = document.querySelector('#quickAddEvent');
let caseNoteSlimSelect;
let caseNoteSlimSelectContainer;
let eventSlimSelect;
let eventSlimSelectContainer;
let responsiblesSlimSelect;
let responsiblesSlimSelectContainer;

const initForm = () => {
  const case_note_input = document.querySelector('#isCaseNote');
  case_note_input.addEventListener('change', (e) => {
    const isCaseNote = e.target.checked;
    if (isCaseNote) {
      quickAddCaseNoteForm.classList.remove('hidden');
      quickAddEventForm.classList.add('hidden');
    } else {
      quickAddCaseNoteForm.classList.add('hidden');
      quickAddEventForm.classList.remove('hidden');
    }
  });
  caseNoteSlimSelect = new SlimSelect({
    select: '.quick_add_case_slim_select',
  });
  caseNoteSlimSelectContainer = document.querySelector(
    '.quick_add_case_slim_select',
  );
  eventSlimSelect = new SlimSelect({
    select: '.quick_add_event_slim_select',
  });
  eventSlimSelectContainer = document.querySelector(
    '.quick_add_event_slim_select',
  );

  responsiblesSlimSelect = new SlimSelect({
    select: '.responsibles_slim_select',
  });
  responsiblesSlimSelectContainer = document.querySelector(
    '.responsibles_slim_select',
  );
};

const formatDate = (date) => {
  const time = date.toLocaleTimeString();
  const months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];

  const month = months[date.getMonth()];
  const day = date.getDate();
  const year = date.getFullYear();

  return `${month} ${day}, ${year} ${time}`;
};

const reloadActivites = async () => {
  const activities = await loadHomeActivities();
  const activitiesContainer = document.getElementById('activities');
  activitiesContainer.innerHTML = activities;
};


const initializeCalendar = async () => {
  const activities = await loadHomeActivities();

  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      start: 'prev,next', // will normally be on the left. if RTL, will be on the right
      center: 'title',
      end: 'today,dayGridMonth,dayGridWeek,timeGridDay',
    },
    eventClick: function (info) {
      const eventId = info.event._def.publicId;
      const event = events.find((e) => e.id == eventId);
      const modal = getModal('#viewEventModal');
      const modalEl = document.querySelector('#viewEventModal');
      const titleEl = modalEl.querySelector('.event_task_title');
      titleEl.innerText = event.title;
      const allDay = event.allDay;
      if (allDay) {
        const span = document.createElement('span');
        span.classList.add('event_all_day');
        span.innerText = 'all day';
        modalEl.querySelector('.case-event__title').appendChild(span);
      }
      const eventStartEl = modalEl.querySelector('.event_start');
      eventStartEl.innerText = formatDate(new Date(event.start));
      const eventEndEl = modalEl.querySelector('.event_end');
      eventEndEl.innerText = formatDate(new Date(event.end));
      const locationEl = modalEl.querySelector('.event-location.location span');
      locationEl.innerText = event.where;

      const guestsEl = modalEl.querySelector('.event-location.guests span');
      const guests = event.users;
      guestsEl.innerText = `${guests?.length || 0} guests`;
      const responsiblesEl = modalEl.querySelector('.event_responsibles');
      responsiblesEl.innerHTML = guests
        .map((guest) => {
          console.log(guest.full_name);
          return `<div class='responsbiles_row'>
          <p>${guest.full_name}</p>
          </div>`;
        })
        .join('');
      const notesEl = modalEl.querySelector('.event_notes');
      notesEl.innerText = event.notes;
      modal.show();
    },
  });
  calendar.addEventSource(loadHomeEvents);
  calendar.render();
  const activitiesContainer = document.getElementById('activities');
  activitiesContainer.innerHTML = activities;
};

if (document.readyState) {
  initializeCalendar();
} else {
  document.addEventListener('DOMContentLoaded', function () {
    console.log('calendar');
    initializeCalendar();
  });
}

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

// cancel case event edit
live('click', 'quick_add_cancel', (e) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose your data.',
    () => {
      const form = document.querySelector('#quickAddModal form');
      resetForm(form);
      const modal = getModal(`#quickAddModal`);
      modal.hide();
    },
    null,
  );
});

live('click', 'quick_add_submit', async (e) => {
  e.preventDefault();

  const isCaseNote = document.querySelector('#isCaseNote')?.checked;
  const form = isCaseNote ? quickAddCaseNoteForm : quickAddEventForm;

  const isValid = checkFormValidity(form);
  const data = getFormValues(form);
  const modal = getModal('#quickAddModal');
  console.log({ isCaseNote });
  if (!isCaseNote && !responsiblesSlimSelect.selected()?.length) {
    responsiblesSlimSelectContainer.classList.add('invaid');
    isValid =
      isValid == true ? ['csenote_case_id'] : [...isValid, 'csenote_case_id'];
  }
  if (isValid != true) {
    form.classList.add('invalid');
    alertify.error(`Please correct the following fields: ${isValid}`);
    return;
  } else {
    form.classList.remove('invalid');
    if (isCaseNote) {
      caseNoteSlimSelectContainer.classList.remove('invaid');
      try {
        const response = await processCaseNotes({
          query_type: 'add',
          ...data,
        }).then((res) => res.data);
        alertify.success(response.message);
        resetForm(form);
        modal.hide();
        reloadActivites();
      } catch (err) {
        alertify.error(err.message);
      }
    } else {
      responsiblesSlimSelectContainer.classList.remove('invaid');
      try {
        const response = await processEvents({
          action: 'add',
          ...data,
          responsibles: responsiblesSlimSelect.selected(),
        });
        alertify.success(response.message);
        resetForm(form);
        modal.hide();
        calendar.refetchEvents();
        reloadActivites();
      } catch (err) {
        alertify.error(err.message);
      }
    }
  }
});
document.addEventListener('DOMContentLoaded', initForm);
