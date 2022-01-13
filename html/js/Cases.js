document.addEventListener("DOMContentLoaded", initCasesTable);
document.addEventListener("DOMContentLoaded", initOpenCaseFunctions);

let caseData;
let openCasesDataArray;
let closedCasesDataArray;
const open_case_ids = [];
let open_cases_container;
let open_cases_tab_button;
let table;

function initOpenCaseFunctions() {
  open_cases_container = document.querySelector(".open-cases-container");
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
    // Default is open cases only
    const openCaseData = caseData.filter((item) => !item.date_closed);
    const closedCaseData = caseData.filter((item) => item.date_closed);

    openCaseData.forEach((item) => {
      const colArray = [];
      for (let key of Object.keys(item)) {
        colArray.push(item[key]);
      }
      openCasesDataArray.push(colArray);
    });
    closedCaseData.forEach((item) => {
        const colArray = [];
        for (let key of Object.keys(item)) {
          colArray.push(item[key]);
        }
        closedCasesDataArray.push(colArray);
      });

    // init Table with open Cases
    table = new gridjs.Grid({
      columns: columnResponseData.aoColumns,
      data: openCasesColumnDataArray,
      search: {
        enabled: true,
      },
      sort: {
        enabled: true,
      },
      pagination: {
        enabled: true,
        limit: 10,
        summary: true,
      },
      autoWidth: {
        enabled: true,
      },
    }).render(document.getElementById("table_cases"));
    

  } catch (error) {
    alert(error);
  } 

}

filter();
setAdvancedSearchFields();

function filter(e = null) {}

function search(e) {
  const keyword = e.target.value;
  table.search(keyword);
  table.draw();
}

function searchColumn(e, columnNumber) {
  table.column(columnNumber).search(e.target.value);
  table.draw();
}

function filterDateColumn(id, columnNumber, selectValue) {}
// TODO add chevron down
function toggleCasesAdvancedSearch() {
  this.classList.toggle("advanced_search--open");
  document
    .querySelector(".advanced-search__fields")
    .classList.toggle("advanced-search__fields--open");
}

function applyColumnChanges(e) {
  const select__list = document.querySelector(".select__list");
  const select__inputs = select__list.querySelectorAll("input");
  for (let i = 0; i < select__inputs.length; i++) {
    if (select__inputs[i].checked) {
      table.column(i).visible(true);
    } else {
      table.column(i).visible(false);
    }
  }
  table.draw();
  setAdvancedSearchFields();
  const id = e.target.dataset.select;
  const options = document.querySelector(id);
  options.classList.toggle("closed");
}

function resetColumnSelectInputs() {
  const select__list = document.querySelector(".select__list");
  const select__inputs = select__list.querySelectorAll("input");
  visibleColumns.forEach((column) => {
    select__inputs[column].checked = true;
  });
  hiddenColumns.forEach((column) => {
    select__inputs[column].checked = false;
  });
}

function resetTable() {
  // Default sort by Case ID
  table.order([0, "asc"]);

  visibleColumns.forEach((column) => {
    table.column(column).visible(true);
  });
  hiddenColumns.forEach((column) => {
    table.column(column).visible(false);
  });
  const cases_search = document.querySelector("#cases_search");
  cases_search.value = "";
  search({ target: cases_search });
  table.searchPanes.clearSelections();
  resetColumnSelectInputs();
  table.draw();
}

// The options for Advanced Search are added dynamically based on
// which columns are visible ("checked" on the column dropdown)
// TODO add triangle style to select box
function setAdvancedSearchFields() {
  const container = document.querySelector(".advanced-search__fields");
  container.innerHTML = null;
  const columns = document.querySelectorAll(".select__list input");
  columns.forEach((column) => {
    if (column.checked) {
      let wrapper = document.createElement("div");
      wrapper.classList.add("advanced-search__element");
      let input = document.createElement("input");
      let label = document.createElement("label");
      label.htmlFor = column.dataset.id;
      label.innerText = column.name;
      input.id = column.dataset.id;
      input.type = column.dataset.type;
      input.classList.add("advanced-search__input");

      // For Date Fields, we need a select option box for
      // inequalities
      if (input.type === "date") {
        let div = document.createElement("div");
        div.classList.add("advanced-search__select");
        let select = document.createElement("select");
        let greaterThan = document.createElement("option");
        greaterThan.innerText = ">";
        greaterThan.value = ">";
        let lessThan = document.createElement("option");
        lessThan.innerText = "<";
        lessThan.value = "<";
        let equalTo = document.createElement("option");
        equalTo.innerText = "=";
        equalTo.value = "=";
        select.appendChild(greaterThan);
        select.appendChild(lessThan);
        select.appendChild(equalTo);
        div.appendChild(label);
        div.appendChild(select);
        div.appendChild(input);
        container.append(div);
        input.addEventListener("change", (e) => {
          filterDateColumn(column.dataset.id, column.id, select.value);
          table.draw();
        });
        select.addEventListener("change", (e) => {
          filterDateColumn(column.dataset.id, column.id, e.target.value);
          table.draw();
        });
      } else {
        // For regular text fields, we just need the input
        // and label
        wrapper.appendChild(label);
        wrapper.appendChild(input);
        container.append(wrapper);
        input.addEventListener("keyup", (e) => searchColumn(e, column.id));
        input.addEventListener("keydown", (e) => searchColumn(e, column.id));
      }
    }
  });
}

const cases_search = document.querySelector("#cases_search");
const cases_select = document.querySelector("#cases_select");
const cases_column_select = document.querySelector("#columnsSelectButton");
const cases_reset = document.querySelector(".cases__reset");
const advanced_search = document.querySelector(".advanced_search p");

cases_search.addEventListener("keyup", search);
cases_select.addEventListener("change", filter);
cases_column_select.addEventListener("click", applyColumnChanges);
cases_reset.addEventListener("click", resetTable);
advanced_search.addEventListener("click", toggleCasesAdvancedSearch);

// TODO add to click even on table
function openCase() {
  var data = table.row(this).data();
  openCase(data[0]);
}

function openCase(id) {
  if (!open_case_ids.includes(id)) {
    open_case_ids.push(id);

    const p = document.createElement("p");
    p.innerText = id;
    open_cases_container.appendChild(p);
  }
  open_cases_tab_button.click();
}
