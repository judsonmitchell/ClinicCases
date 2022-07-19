//
//Functions for the case note timer.
//

import { live } from '../../html/js/live.js';
import {
  startIdletimeout,
  endIdleTimeout,
} from '../../html/js/idletimerStart.js';
import { getCaseNotes, getTimer } from './axios.js';
import { setCookie, getCookie, eraseCookie } from './cookies.js';
let timerLoop;
let elapsedTime;

document.addEventListener('DOMContentLoaded', () => {
  const startTime = getCookie('timer_start_time');
  const caseName = getCookie('timer_case_name');
  const caseId = getCookie('timer_case_id');
  if (startTime && caseName && caseId) {
    createTimer(caseId, caseName, new Date(startTime));
  }
});

live('click', 'case_notes_timer', async (event) => {
  const button = event.target.closest('.case_notes_timer');
  const caseId = button.dataset.caseid;
  const caseName = button.dataset.casename;
  createTimer(caseId, caseName, new Date());
});
live('click', 'timer_stop', () => {
  ccTimer(false);
  eraseCookie('timer_start_time');
  eraseCookie('timer_case_name');
  eraseCookie('timer_case_id');
});

live('click', 'cancel_timer_button', (event) => {
  event.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose the time you have recorded. ',
    destroyTimer,
    null,
  );
});
live('click', 'save_timer_button', async (event) => {
  event.preventDefault();
  const form = document.querySelector('#timerForm');
  form.classList.remove('invalid');
  const data = getFormValues(form);
  data.csenote_hours = getHours(elapsedTime);
  data.csenote_minutes = getMinutes(elapsedTime);
  data.csenote_seconds = getSeconds(elapsedTime);
  const isValid = checkFormValidity(form);
  if (isValid != true) {
    form.classList.add('invalid');
    alertify.error(`This field is required.`);
    return;
  }
  await axios
    .post(`lib/php/data/cases_casenotes_process.php`, data)
    .then((res) => res.data);
  destroyTimer();
  const { csenote_case_id: id } = data;
  const response = await getCaseNotes(id, true, '');
  const notesContainer = document.querySelector(
    `#nav-${id}-notes .case_detail_panel_casenotes`,
  );
  notesContainer.innerHTML = response.data;
});
//Starts and stops the timer
async function ccTimer(toggle, startTime) {
  if (toggle === true) {
    endIdleTimeout(); // stop idle timer
    const getElapsed = function (startTime) {
      const timeNow = new Date();
      const timeNowMs = timeNow.getTime();
      const elapsedMs = timeNowMs - startTime;
      elapsedTime = elapsedMs;
      const timeString = msToTime(elapsedMs);
      const elapsedTimeContainer = document.querySelector(
        '.timer_time_elapsed',
      );
      elapsedTimeContainer.innerHTML = timeString;
      timerLoop = setTimeout(function () {
        getElapsed(startTime);
      }, 1000);
      function msToTime(duration) {
        let seconds = Math.floor((duration / 1000) % 60),
          minutes = Math.floor((duration / (1000 * 60)) % 60),
          hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        return hours + ':' + minutes + ':' + seconds;
      }
    };
    getElapsed(startTime);
  } else {
    //stop timer
    clearTimeout(timerLoop);
    startIdletimeout(); // start idle timer
  }
}

function getHours(duration) {
  return Math.floor((duration / (1000 * 60 * 60)) % 24);
}
function getMinutes(duration) {
  return Math.floor((duration / (1000 * 60)) % 60);
}

function getSeconds(duration) {
  return Math.floor((duration / 1000) % 60);
}

function destroyTimer() {
  const modal = bootstrap.Modal.getInstance(
    document.querySelector('#timerNewCaseNoteModal'),
  );
  modal?.hide();
  document.querySelector('.timer').remove();
}

async function createTimer(caseId, caseName, startTime) {
  const timer = await getTimer(caseId);
  const timerContainer = document.createElement('div');
  timerContainer.innerHTML = timer;
  timerContainer.classList.add('timer');
  const caseNameDiv = timerContainer.querySelector('.timer_case_name');
  caseNameDiv.innerText = caseName;
  document.body.append(timerContainer);
  setCookie('timer_start_time', startTime, 3);
  setCookie('timer_case_name', caseName, 3);
  setCookie('timer_case_id', caseId, 3);
  ccTimer(true, startTime);
}
