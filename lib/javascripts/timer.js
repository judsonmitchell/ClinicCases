//
//Functions for the case note timer.
//

import { live } from '../../html/js/live.js';
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
    //start timer
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

    // TODO come up with vanilla JS idletimeout solution
  } else {
    //stop timer
    clearTimeout(timerLoop);
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
//Destroys timer
function timerDestroy() {
  //   // actually remove timer from html
  //   $('#timer').hide();
  //   //Destroy all cookies
  //   $.cookie('timer_status', null);
  //   $.cookie('timer_start_time', null);
  //   $.cookie('timer_case_name', null);
  //   $.cookie('timer_case_id', null);
  //   //Put content window back to original height
  //   const currentContentHeight = $('#content').height();
  //   $('#content').height(currentContentHeight + 45);
  //   //Reset the timer html
  //   $.get('html/templates/interior/timer.php', function (data) {
  //     $('#timer').replaceWith(data);
  //   });
  //   //restart the idletimer.js timer
  //   $(document).bind('idle.idleTimer', function () {
  //     if ($.data(document, 'idleTimer') === 'idle' && !self.countdownOpen) {
  //       const theTimer = $('#idletimeout').data('idletimeout');
  //       theTimer._stopTimer();
  //       theTimer.countdownOpen = true;
  //       theTimer._idle();
  //     }
  //   });
}

function destroyTimer() {
  const modal = bootstrap.Modal.getInstance(
    document.querySelector('#timerNewCaseNoteModal'),
  );
  modal?.hide();
  document.querySelector('.timer').remove();
}
// $(document).ready(function () {
//   if ($.cookie('timer_status') === 'on') {
//     const caseName = $.cookie('timer_case_name');
//     const startTime = $.cookie('timer_start_time');
//     $('#timer .timer_case_name').html(caseName);
//     ccTimer(true, startTime);
//     $('#timer').show();

//     //make room for the timer widget
//     const currentContentHeight = $('#content').height();
//     $('#content').height(currentContentHeight - 45);
//   }

//   //Stop timer
//   $('#timer_controls button.timer_stop').live('click', function (event) {
//     event.preventDefault();
//     $(this).hide();
//     ccTimer(false);
//   });

//   //Submit time
//   $('#timer_controls button.timer_add_button').live('click', function () {
//     //Get constiables
//     const timerUser = $.cookie('cc_user');
//     const d = new Date();
//     const timerDate =
//       d.getMonth() + 1 + '/' + d.getDate() + '/' + d.getFullYear();
//     const now = d.getTime();
//     const start = $.cookie('timer_start_time');
//     const elapsed = ((now - start) / 1000).toFixed();
//     const description = $('#timer_controls textarea').val();
//     const caseId = $.cookie('timer_case_id');

//     //Put constiables in an object
//     const cseVals = [
//       { name: 'csenote_date', value: timerDate },
//       { name: 'csenote_seconds', value: elapsed },
//       { name: 'csenote_user', value: timerUser },
//       { name: 'csenote_case_id', value: caseId },
//       { name: 'query_type', value: 'add' },
//       { name: 'csenote_description', value: description },
//     ];

//     //Check to see if textarea is valid
//     if (description === '' || description === 'Describe What You Did.') {
//       notify('<p>Please provide a description of what you did.</p>', true);
//       return false;
//     } else {
//       $.post(
//         'lib/php/data/cases_casenotes_process.php',
//         cseVals,
//         function (data) {
//           timerDestroy();
//           //If user is looking at case notes for the timed case, refresh so that it shows new casenote
//           if ($('.case_' + caseId).length) {
//             if ($('#utilities_panel').length) {
//               //user is viewing non-case time
//               $('.case_' + caseId).load(
//                 'lib/php/data/cases_casenotes_load.php',
//                 { case_id: caseId, start: '0', non_case: '1', update: 'yes' },
//               );
//             } else {
//               $('.case_' + caseId).load(
//                 'lib/php/data/cases_casenotes_load.php',
//                 { case_id: caseId, start: '0', update: 'yes' },
//               );
//             }
//           }
//           const serverResponse = $.parseJSON(data);
//           notify(serverResponse.message);
//         },
//       );
//     }
//   });

//   //Cancel adding time
//   $('#timer_controls button.timer_cancel_add_button').live(
//     'click',
//     function () {
//       const dialogWin = $(
//         '<div class=".dialog-casenote-delete" title="Delete this Time?">' +
//           'You will lose the time you have recorded.  Are you sure?</div>',
//       ).dialog({
//         autoOpen: true,
//         resizable: false,
//         modal: true,
//         buttons: {
//           Yes: function () {
//             timerDestroy();
//             $(this).dialog('destroy');
//             notify('Timer removed');
//           },
//           No: function () {
//             $(this).dialog('destroy');
//           },
//         },
//       });
//     },
//   );
// });

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
