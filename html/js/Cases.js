document.addEventListener('DOMContentLoaded', () => {
  initCasesTable();
  initOpenCaseFunctions();
});

const open_case_ids = [];
let caseData;
let openCasesDataArray;
let closedCasesDataArray;
let open_cases_container;
let open_cases_tab_button;
let table;
let caseEditFormIsSubmitting = false;

// Set these to values to global variables
function initOpenCaseFunctions() {
  open_cases_container = document.querySelector('.open-cases-container');
  open_cases_tab_button = document.querySelector(
    "[data-bs-target='#openCases']"
  );
}

async function initCasesTable() {
  try {
    // Fetch the inital state column visibility information
    const columnResponse = await axios.get(
      `lib/php/data/cases_columns_load.php`
    );
    const columnResponseData = columnResponse.data;

    // Fetch all case data
    const caseDataResponse = await axios.get(`lib/php/data/cases_load.php`);
    caseData = caseDataResponse.data.aaData;

    // Custom table plugin initiation
    table = new Table({
      columns: columnResponseData.aoColumns,
      data: caseData,
      containerId: '#table_cases',
      facets: [
        {
          label: 'Open Cases',
          value: 'open',
          field: 'date_close',
          filter: (item) => {
            return !item.date_close;
          },
          default: true,
        },
        {
          label: 'Closed Cases',
          value: 'closed',
          field: 'date_close',
          filter: (item) => {
            return item.date_close;
          },
        },
        {
          label: 'All Cases',
          value: 'all',
          field: 'date_close',
          filter: () => {
            return true;
          },
        },
      ],
    });
  } catch (error) {
    notify(error, true, 'error');
  } finally {
    registerTableRowClickEvent();
  }
}

function registerTableRowClickEvent() {
  table.onRowClick((event) => {
    const id = event.target.dataset.caseid;
    const name = event.target.dataset.name;
    openCase(id, name);
  });
}

async function openCase(id, name) {
  if (!open_case_ids.includes(id)) {
    open_case_ids.push(id);
    const tabContainer = document.querySelector('#openCasesTabs');
    const tabContentContainer = document.querySelector('#openCasesTabContent');
    const panes = tabContentContainer.querySelectorAll('.tab-pane');
    panes.forEach((pane) => {
      pane.classList.remove('show', 'active');
    });
    const button = document.createElement('button');
    button.classList.add('nav-link');
    const tabLabel = `case${id}Tab`;
    const contentLabel = `case${id}Content`;
    button.setAttribute('id', tabLabel);
    button.setAttribute('data-bs-toggle', 'tab');
    button.setAttribute('data-bs-target', `#${contentLabel}`);
    button.setAttribute('type', 'button');
    button.setAttribute('role', 'tab');
    button.setAttribute('aria-controls', contentLabel);
    button.setAttribute('aria-selected', true);
    button.innerText = name;
    tabContainer.appendChild(button);

    const content = document.createElement('div');
    content.classList.add('tab-pane', 'fade', 'show', 'active');
    content.setAttribute('id', contentLabel);
    content.setAttribute('role', 'tabpanel');
    content.setAttribute('aria-labelledby', contentLabel);
    content.innerText = name;
    content.style.backgroundColor = 'white';
    tabContentContainer.appendChild(content);

    try {
      const caseView = await axios.post(`lib/php/data/open_case_load.php`, {
        id,
      });

      content.innerHTML = caseView.data;

      const caseNotes = await axios.post(
        `lib/php/data/cases_casenotes_load.php`,
        {
          case_id: id,
        },
        {
          headers: {
            'Content-type': 'application/json',
          },
        }
      );

      const notesContainer = document.querySelector(`#nav-${id}-notes`);
      notesContainer.innerHTML = caseNotes.data;

      const caseData = await axios.post(
        `lib/php/data/cases_case_data_load.php`,
        {
          id,
        },
        {
          headers: {
            'Content-type': 'application/json',
          },
        }
      );

      const dataContainer = document.querySelector(`#nav-${id}-data`);
      dataContainer.innerHTML = caseData.data;
      setUpCasePrintFunctionality(id, name);
      setUpOpenEditCaseViewFunctionality(id);
      setUpFloatingLabelStyles(id);
      setUpCancelEditFunctionality(id);
      setUpSaveCaseFunctionality(id);
      setLetMeEditThisFunctionality(id);
      // TODO connect this to tab pane

      // const assignedUsersView = await axios.post(
      //   `lib/php/users/cases_detail_assigned_people_refresh_load.php`,
      //   { id },
      //   {
      //     headers: {
      //       'Content-type': 'application/json',
      //     },
      //   }
      // );
      // const assignedUsersContainer = document.querySelector('#assignedUsersContainer');
      // assignedUsersContainer.innerHTML = assignedUsersView.data;
    } catch (error) {
      console.log(error);
      notify(error, true, 'error');
    } finally {
      button.click();
    }
  }
  open_cases_tab_button.classList.remove('disabled');
  open_cases_tab_button.setAttribute('aria-disabled', 'false');
  open_cases_tab_button.click();
}

function setUpCasePrintFunctionality(id,name) {
  const button = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataPrintButton');
  const caseData = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseData');
  button.removeEventListener('click', printPDF);
  button.addEventListener('click', printPDF);

  function printPDF() {
    html2pdf().from(caseData).save(`${name} Case Data`);
  }
}

function setUpOpenEditCaseViewFunctionality(id) {
  const button = document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseButton');
  const editCase = document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseData');
  const viewCase = document.querySelector(`#nav-${id}-tabContent`).querySelector('#viewCaseData');
  const printButton = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataPrintButton');
  const saveButton = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataSaveButton');
  const cancelButton = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataCancelButton');

  button.addEventListener('click', () => {
    button.classList.add('hidden');
    editCase.classList.remove('hidden');
    viewCase.classList.add('hidden');
    printButton.classList.add('hidden');
    saveButton.classList.remove('hidden');
    cancelButton.classList.remove('hidden');
    setAlertToNotLoseCaseData();
  });
}

function setUpSaveCaseFunctionality(id) {
  // hook up the button
  const button = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataSaveButton');
  button.addEventListener('click', async () => {
    caseEditFormIsSubmitting = true;
    const form = document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseData');
    const formState = [...form.elements].reduce((prev, current) => {
      const name = current.name;
      if (name) {
        prev[current.name] = current.value;
      }
      return prev;
    }, {});
    const editCaseResponse = await axios.post(
      `lib/php/data/cases_case_data_process.php`,
      {
        action: 'edit',
        id,
        ...formState,
      },
      {
        headers: {
          'Content-type': 'application/json',
        },
      }
    );
    caseEditFormIsSubmitting = false;
    const data = JSON.parse(JSON.stringify(editCaseResponse.data));
    if (data.error) {
      notify(data.message, true, 'error');

    } else {
      notify(data.message, true, 'success');
      const displayFields = document.querySelector(`#nav-${id}-tabContent`).querySelectorAll('#viewCaseData [data-displayfield]');
      displayFields.forEach((el) => {
        const field = el.dataset.displayfield;
        el.innerText = formState[field];
      });

      // update the name in the tab for this case
      const updatedName = `${formState.last_name}, ${formState.first_name}`;
      const dataContainer = document.querySelector(`#case${id}Tab`);
      dataContainer.innerText = updatedName;
      // update ethe print functionality to reflect the changes
      setUpCasePrintFunctionality(updatedName);

      resentCaseDataUI();
    }
  });
}

function setUpCancelEditFunctionality(id) {
  const button = document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataCancelButton');
  const form = document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseData');
  // save the initial state of the form
  const initialState = [...form.elements].map((el) => {
    const obj = {};
    obj['name'] = el.name;
    obj['value'] = el.value;
    return obj;
  });

  button.addEventListener('click', () => {
  if(!window.confirm('Are you sure you want to cancel? You may lose data' )) return;

    // revert the form to the initial state
    initialState.forEach((el) => {
      if (el.name) {
        const input = form[el.name];
        input.value = el.value;
        if (el.value) {
          const label = document.querySelector(`#nav-${id}-tabContent`).querySelector(input.dataset?.label);
          if (label && !label.classList.contains('float')) {
            label.classList.add('float');
          }
        }
      }
    });
    // reset the UI
    resentCaseDataUI(id);
  });
}

function resentCaseDataUI(id) {
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataCancelButton').classList.add('hidden');
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataSaveButton').classList.add('hidden');
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#caseDataPrintButton').classList.remove('hidden');
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseButton').classList.remove('hidden');
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#editCaseData').classList.add('hidden');
  document.querySelector(`#nav-${id}-tabContent`).querySelector('#viewCaseData').classList.remove('hidden');
  removeAlertToNotLoseCaseData();
}
function setAlertToNotLoseCaseData() {
  window.onbeforeunload = (e)=> {
    areYouSure(e);
  }
}

function removeAlertToNotLoseCaseData() {
  window.onbeforeunload = ()=> {
  }
}

function areYouSure(e) {
  e.preventDefault();
  if (caseEditFormIsSubmitting) {
    return undefined;
  }

  let confirmationMessage =
    'It looks like you have been editing something. ' +
    'If you leave before saving, your changes will be lost.';

  (e || window.event).returnValue = confirmationMessage; //Gecko + IE
  return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
}


function setLetMeEditThisFunctionality(id){
const letMeEditThisButton = document.querySelector(`#nav-${id}-tabContent`).querySelector(`.let-me-edit-this[data-target="${id}"]`);
console.log(`.let-me-edit-this[data-target="${id}"]`);
console.log(letMeEditThisButton)
letMeEditThisButton.addEventListener('click', ()=> {
  if(confirm('ClinicCases automatically assigns the next available case number. If your case number contains "CaseType" or "ClinicType", these values will be replaced when you change those fields below. Manually editing a case number may have undesirable results. Are you sure?')){
    const clinicIdInput = document.querySelector(`#nav-${id}-tabContent`).querySelector(`[name="clinic_id"]`);
    clinicIdInput.disabled = false;
    console.log(clinicIdInput);
    console.log(clinicIdInput.disabled);
  }
})
}