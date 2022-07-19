//
//Functions for the case note timer.
//

import { live } from '../../html/js/live.js';
import { startIdletimeout, endIdleTimeout} from '../../html/js/idletimerStart.js';
let timerLoop;
let elapsedTime;

live('click', 'case_notes_timer', async (event) => {
  const button = event.target.closest('.case_notes_timer');
  const caseId = button.dataset.caseid;
  const timer = await getTimer(caseId);
  const caseName = button.dataset.casename;
  const timerContainer = document.createElement('div');
  timerContainer.innerHTML = timer;
  timerContainer.classList.add('timer');
  const caseNameDiv = timerContainer.querySelector('.timer_case_name');
  caseNameDiv.innerText = caseName;
  document.body.append(timerContainer);
  ccTimer(true, new Date());
});
live('click', 'timer_stop', () => {
  ccTimer(false);
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

function getTimer(caseId) {
  return axios
    .post(`html/templates/interior/timer.php`, { case_id: caseId })
    .then((res) => res.data);
}

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
