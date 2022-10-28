import {
  getCaseNotes,
  getCaseView,
  getCaseData,
  getAssignedUsersView,
  getAssignedUsersInterface,
  assignUsersToCase,
  getDocuments,
  getCaseEventData,
} from '../../lib/javascripts/axios.js';
import { getCookie } from '../../lib/javascripts/cookies.js';

document.addEventListener('DOMContentLoaded', () => {
  initCasesTable();
  initOpenCaseFunctions();
  setUpOpenCasesMobileSelectListener();
  setUpCloseCaseMobileListener();
});

let open_case_ids = [];
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
    "[data-bs-target='#openCases']",
  );
}

async function initCasesTable() {
  try {
    // Fetch the inital state column visibility information
    const columnResponse = await axios.get(
      `lib/php/data/cases_columns_load.php`,
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
    alertify.error(error);
  } finally {
    registerTableRowClickEvent();
  }
}

function registerTableRowClickEvent() {
  table.onRowClick((event) => {
    const dataset = event.target.dataset;
    // if the td is a header, we don't want to  perform
    // this action
    if (!dataset.header) {
      const id = dataset.caseid;
      const name = dataset.name;
      openCase(id, name);
    }
  });
}

function addCountToOpenCasesLabel() {
  const count = open_case_ids.length;
  const notification = document.querySelector(
    '[data-bs-target="#openCases"] .notification',
  );
  notification.innerText = count || '';
}

function setUpOpenCasesMobileSelectListener() {
  const mobileCasesSelector = document.querySelector(
    '#openCasesTabsMobile select',
  );
  mobileCasesSelector.addEventListener('change', () => {
    const value = mobileCasesSelector.value;
    const tab = document.querySelector(`#case${value}Tab`);
    tab?.click();
  });
}

function setUpCloseCaseMobileListener() {
  const closeCaseButton = document.querySelector(`#closeCaseTabMobile`);
  closeCaseButton.addEventListener('click', () => {
    const selectValue = document.querySelector(
      '#openCasesTabsMobile select',
    )?.value;
    if (selectValue) {
      closeTab(selectValue);
      addCountToOpenCasesLabel();
    }
  });
}

async function openCase(id, name) {
  if (!open_case_ids.includes(id)) {
    open_case_ids.push(id);
    const tabContainer = document.querySelector('#openCasesTabs');
    const tabContentContainer = document.querySelector('#openCasesTabContent');
    const panes = tabContentContainer.querySelectorAll('.tab-pane');
    const contentLabel = `case${id}Content`;
    // set the value in the mobile dropdown for
    // open cases

    panes.forEach((pane) => {
      pane.classList.remove('show', 'active');
    });
    addCountToOpenCasesLabel();
    const button = createOpenCasesButton(id, name, contentLabel);
    const closeButton = createCloseButton(id);
    button.append(closeButton);
    tabContainer.appendChild(button);

    const content = createContentContainer(name, contentLabel);
    tabContentContainer.appendChild(content);
    const mobileCasesSelector = document.querySelector(
      '#openCasesTabsMobile select',
    );
    // TODO this needs to work more like tabs
    const newOption = createNewOption(id, name);
    mobileCasesSelector.appendChild(newOption);
    try {
      const caseView = await getCaseView(id);
      content.innerHTML = caseView.data;
      const caseNotes = await getCaseNotes(id);
      const notesContainer = document.querySelector(`#nav-${id}-notes`);
      notesContainer.innerHTML = caseNotes.data;
      const caseData = await getCaseData(id);
      const dataContainer = document.querySelector(`#nav-${id}-data`);
      dataContainer.innerHTML = caseData.data;
      const cc_docs_view = getCookie('cc_docs_view');
      const documentsData = await getDocuments(
        id,
        '',
        null,
        cc_docs_view === 'list' || null,
      );
      const documentsContainer = document.querySelector(`#nav-${id}-documents`);
      documentsContainer.innerHTML = documentsData;
      const caseEvents = await getCaseEventData(id);
      const eventsContainer = document.querySelector(`#nav-${id}-events`);
      eventsContainer.innerHTML = caseEvents;
      setUpCasePrintFunctionality(id, name);
      setUpOpenEditCaseViewFunctionality(id);
      setUpCancelEditFunctionality(id);
      setUpSaveCaseFunctionality(id);
      setLetMeEditThisFunctionality(id);
      setUpAddItemsButtonFunctionality(id);
      setUpAssignedUsersFunctionality(id);
    } catch (error) {
      console.log(error);
      alertify.error(error);
    } finally {
      button.click();
    }
  }
  open_cases_tab_button.classList.remove('disabled');
  open_cases_tab_button.setAttribute('aria-disabled', 'false');
  open_cases_tab_button.click();
  setValueOfMobileSelect(id);
}

function setValueOfMobileSelect(id) {
  document.querySelector('#openCasesTabsMobile select').value = id;
}

function setUpCasePrintFunctionality(id, name) {
  const button = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataPrintButton');
  const caseData = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseData');
  button.removeEventListener('click', printPDF);
  button.addEventListener('click', printPDF);

  function printPDF() {
    html2pdf().from(caseData).save(`${name} Case Data`);
  }
}

function createOpenCasesButton(id, name, contentLabel) {
  const button = document.createElement('button');
  button.classList.add('nav-link');
  const tabLabel = `case${id}Tab`;
  button.setAttribute('id', tabLabel);
  button.setAttribute('data-bs-toggle', 'tab');
  button.setAttribute('data-bs-target', `#${contentLabel}`);
  button.setAttribute('type', 'button');
  button.setAttribute('role', 'tab');
  button.setAttribute('aria-controls', contentLabel);
  button.setAttribute('aria-selected', true);
  button.innerText = name;
  return button;
}

function createCloseButton(id) {
  const closeButton = document.createElement('span');
  closeButton.classList.add('tab-close');
  closeButton.innerHTML = '&times;';
  closeButton.addEventListener('click', () => closeTab(id));
  return closeButton;
}

function createContentContainer(name, contentLabel) {
  const content = document.createElement('div');
  content.classList.add('tab-pane', 'fade', 'show', 'active');
  content.setAttribute('id', contentLabel);
  content.setAttribute('role', 'tabpanel');
  content.setAttribute('aria-labelledby', contentLabel);
  content.innerText = name;
  content.style.backgroundColor = 'white';
  return content;
}

function createNewOption(id, name) {
  const newOption = document.createElement('option');
  newOption.value = id;
  newOption.innerText = name;
  newOption.setAttribute('id', `case${id}Option`);
  return newOption;
}

function setUpOpenEditCaseViewFunctionality(id) {
  const button = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#editCaseButton');
  const editCase = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#editCaseData');
  const viewCase = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#viewCaseData');
  const printButton = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataPrintButton');
  const saveButton = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataSaveButton');
  const cancelButton = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataCancelButton');

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
  const button = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataSaveButton');
  button.addEventListener('click', async () => {
    try {
      caseEditFormIsSubmitting = true;
      const form = document
        .querySelector(`#nav-${id}-tabContent`)
        .querySelector('#editCaseData');
      form.classList.remove('invalid');
      if (!form.checkValidity()) {
        form.classList.add('invalid');
        const invalidFields = [];
        form.elements.forEach((el) => {
          if (!el.checkValidity()) {
            invalidFields.push(el.name);
          }
        });
        throw new Error(`Fix invalid field(s): ${invalidFields.join(', ')}`);
      }
      const formState = [...form.elements].reduce((prev, current) => {
        const name = current.name;
        const isDual = Boolean(current.dataset.dual);

        if (name && !isDual) {
          prev[current.name] = current.value;
        }
        return prev;
      }, {});

      // Extract values from all dual inputs
      const dualInputs = document.querySelectorAll(
        `#nav-${id}-tabContent .form-control__dual`,
      );
      const dualInputValues = getDualInputValues(dualInputs);
      const editCaseResponse = await axios.post(
        `lib/php/data/cases_case_data_process.php`,
        {
          action: 'edit',
          id,
          ...formState,
          ...dualInputValues,
        },
        {
          headers: {
            'Content-type': 'application/json',
          },
        },
      );
      caseEditFormIsSubmitting = false;
      const data = JSON.parse(JSON.stringify(editCaseResponse.data));
      if (data.error) {
        alertify.error(data.message);
      } else {
        alertify.success(data.message);
        const displayFields = document
          .querySelector(`#nav-${id}-tabContent`)
          .querySelectorAll('#viewCaseData [data-displayfield]');
        displayFields.forEach((el) => {
          const field = el.dataset.displayfield;
          if (formState[field]) {
            el.innerText = formState[field];
          } else if (dualInputValues[field]) {
            el.innerText = Object.keys(JSON.parse(dualInputValues[field])).join(
              ', ',
            );
          }
        });

        // update the name in the tab for this case
        const updatedName = `${formState.last_name}, ${formState.first_name}`;
        const dataContainer = document.querySelector(`#case${id}Tab`);
        dataContainer.innerText = updatedName;
        // update the print functionality to reflect the changes
        setUpCasePrintFunctionality(id, updatedName);
        resentCaseDataUI(id);
      }
    } catch (error) {
      alertify.error(error.message);
    }
  });
}

function setUpCancelEditFunctionality(id) {
  const button = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataCancelButton');
  const form = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#editCaseData');
  // save the initial state of the form
  const initialState = [...form.elements].map((el) => {
    const obj = {};
    obj['name'] = el.name;
    obj['value'] = el.value;
    return obj;
  });

  button.addEventListener('click', () => {
    alertify.confirm(
      'Confirm',
      'Are you sure you want to cancel? You may lose data.',
      () => {
        // revert the form to the initial state
        initialState.forEach((el) => {
          if (el.name) {
            const input = form[el.name];
            input.value = el.value;
            if (el.value) {
              const label = document
                .querySelector(`#nav-${id}-tabContent`)
                .querySelector(input.dataset?.label);
              if (label && !label.classList.contains('float')) {
                label.classList.add('float');
              }
            }
          }
        });
        // reset the UI
        resentCaseDataUI(id);
      },
      null,
    );
  });
}

function resentCaseDataUI(id) {
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataCancelButton')
    .classList.add('hidden');
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataSaveButton')
    .classList.add('hidden');
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#caseDataPrintButton')
    .classList.remove('hidden');
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#editCaseButton')
    .classList.remove('hidden');
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#editCaseData')
    .classList.add('hidden');
  document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector('#viewCaseData')
    .classList.remove('hidden');
  removeAlertToNotLoseCaseData();
}
function setAlertToNotLoseCaseData() {
  window.onbeforeunload = (e) => {
    areYouSure(e);
  };
}

function removeAlertToNotLoseCaseData() {
  window.onbeforeunload = () => {};
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

function setLetMeEditThisFunctionality(id) {
  const letMeEditThisButton = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelector(`.let-me-edit-this[data-target="${id}"]`);
  letMeEditThisButton.addEventListener('click', () => {
    if (
      confirm(
        'ClinicCases automatically assigns the next available case number. If your case number contains "CaseType" or "ClinicType", these values will be replaced when you change those fields below. Manually editing a case number may have undesirable results. Are you sure?',
      )
    ) {
      const clinicIdInput = document
        .querySelector(`#nav-${id}-tabContent`)
        .querySelector(`[name="clinic_id"]`);
      clinicIdInput.disabled = false;
    }
  });
}

function setUpAddItemsButtonFunctionality(id) {
  const addItemButtons = document
    .querySelector(`#nav-${id}-tabContent`)
    .querySelectorAll(`.add-item-button`);

  addItemButtons.forEach((button) => {
    button.addEventListener('click', () => addNewItem(button));
  });
}

function closeTab(id) {
  open_case_ids = open_case_ids.filter((case_id) => case_id != id);
  const tabContent = document.querySelector(`#case${id}Content`);
  const tabButton = document.querySelector(`#case${id}Tab`);
  const option = document.querySelector(`#case${id}Option`);
  tabContent.remove();
  tabButton.remove();
  option.remove();
  if (!open_case_ids.length) {
    removeOpenCaseTab();
    navigateToSearchCases();
  }
}

function removeOpenCaseTab() {
  document
    .querySelector('[data-bs-target="#openCases"]')
    ?.classList.add('disabled');
}

function navigateToSearchCases() {
  document.querySelector(`[data-bs-target="#searchCases"]`)?.click();
}

async function setUpAssignedUsersFunctionality(id) {
  const assignedUsersView = await getAssignedUsersView(id);
  const assignedUsersContainer = document.querySelector(
    `#case${id}Content #assignedUsersContainer > div`,
  );
  assignedUsersContainer.innerHTML = assignedUsersView.data;
  const assignedUsersInterface = await getAssignedUsersInterface(id);
  const addAssignedUser = document.querySelector(
    `#case${id}Content #addAssignedUser`,
  );
  addAssignedUser.innerHTML = assignedUsersInterface;

  // Register the select/search combo box
  const slimSelect = new SlimSelect({
    select: `#case${id}Content .slim-select`,
  });
  initTooltips();
  registerAddCaseClickEvent();
  // register the click even to cancel the form
  document
    .querySelector(`#case${id}Content .cancel-add-user-button`)
    .addEventListener('click', closeAddUsersForm);
  // Register the click even for adding users
  document
    .querySelector(`#case${id}Content .add-user-button`)
    .addEventListener('click', async () => {
      try {
        await assignUsersToCase(id, slimSelect.selected());
        const updatedAssignedUsersView = await getAssignedUsersView(id).then(
          (res) => res.data,
        );
        const userList = document.querySelector(
          `#case${id}Content #assignedUsersContainer > div`,
        );
        userList.innerHTML = updatedAssignedUsersView;
        registerAddCaseClickEvent();
        initTooltips();
        alertify.success('Users successfully assigned.');
      } catch {
        alertify.success('Error assigning users.');
      } finally {
        closeAddUsersForm();
      }
    });

  function closeAddUsersForm() {
    document
      .querySelector(`#case${id}Content #addAssignedUser`)
      .classList.remove('open');
  }
  function registerAddCaseClickEvent() {
    document
      .querySelector(`#case${id}Content .user_add_button`)
      .addEventListener('click', () => {
        const dropdown = document.querySelector(
          `#case${id}Content #addAssignedUser`,
        );
        dropdown.classList.add('open');
      });
  }

  function initTooltips() {
    // initialize the tooltips
    var tooltipTriggerList = [
      ...document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    ];
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
}
