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
    // TODO use notify.js
    console.log(error);
    alert(error);
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
      console.log(error.stack)
    } finally{
      button.click();
    }
  }
  open_cases_tab_button.click();
  open_cases_tab_button.classList.remove('hidden');
}
