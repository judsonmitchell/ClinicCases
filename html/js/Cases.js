document.addEventListener('DOMContentLoaded', initCasesTable);

function initCasesTable() {
  let visibleColumns;
  let hiddenColumns;
  $.ajax({
    // Load column data from DB
    url: 'lib/php/data/cases_columns_load.php',
    dataType: 'json',
    error: function () {
      alert('Sorry, there is an error in your ClinicCases configuration');
      return true;
    },
    success: function (data) {
      visibleColumns = data.aoColumns
        .map((column, index) => {
          if (column.bVisible === true) {
            return index;
          }
        })
        .filter((val) => val != undefined);
      hiddenColumns = data.aoColumns
        .map((column, index) => {
          if (column.bVisible === false) {
            return index;
          }
        })
        .filter((val) => val != undefined);

      // Load Cases Data
      $.ajax({
        url: 'lib/php/data/cases_load.php',
        error: function () {
          alert('error');
        },
        success: function (data) {
          const jsonData = JSON.parse(data);
          const asObjects = jsonData.aaData;
          const asArray = asObjects.map((value) => {
            const array = [...Object.entries(value)].map((value) => {
              return value[1];
            });
            return array;
          });
          columnNames = Object.keys(asObjects[0]);
          // TODO can I do this with vanilla js?
          const table = $('#table_cases').DataTable({
            data: asArray,
            responsive: true,
            autoWidth: false,
            columnDefs: [
              { targets: visibleColumns, visible: true },
              { targets: hiddenColumns, visible: false },
            ],
          });

          // Set initial state
          filter();
          setAdvancedSearchFields();

          function filter(e = null) {
            // Defaults to open cases
            value = e?.target.value || 'open';
            // TODO can i do this with plain js?
            $.fn.dataTable.ext.search.push(function (
              settings,
              data,
              dataIndex
            ) {
              const close_data = data[7];
              return value === 'open' && !close_data
                ? true
                : value === 'closed' && close_data
                ? true
                : value === 'all'
                ? true
                : false;
            });
          }

          function search(e) {
            const keyword = e.target.value;
            table.search(keyword);
            table.draw();
          }

          function searchColumn(e, columnNumber) {
            table.column(columnNumber).search(e.target.value);
            table.draw();
          }

          function filterDateColumn(inputValue, columnNumber, selectValue) {
            $.fn.dataTable.ext.search.push(function (
              settings,
              data,
              dataIndex
            ) {
              const date = new Date(data[columnNumber]).getTime();
              const value = new Date(inputValue).getTime();

              let isReturned;
              if (!inputValue || !selectValue) {
                isReturned = true;
              } else {
                isReturned =
                  selectValue === '='
                    ? date === value
                    : selectValue === '>'
                    ? date >= value
                    : selectValue === '<'
                    ? date < value
                    : true;
              }

              return isReturned;
            });
          }
          // TODO add chevron down
          function toggleCasesAdvancedSearch() {
            this.classList.toggle('advanced_search--open');
          }

          function applyColumnChanges(e) {
            const select__list = document.querySelector('.select__list');
            const select__inputs = select__list.querySelectorAll('input');
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
            options.classList.toggle('closed');
          }

          function resetColumnSelectInputs() {
            const select__list = document.querySelector('.select__list');
            const select__inputs = select__list.querySelectorAll('input');
            visibleColumns.forEach((column) => {
              select__inputs[column].checked = true;
            });
            hiddenColumns.forEach((column) => {
              select__inputs[column].checked = false;
            });
          }

          function resetTable() {
            // Default sort by Case ID
            table.order([0, 'asc']);

            visibleColumns.forEach((column) => {
              table.column(column).visible(true);
            });
            hiddenColumns.forEach((column) => {
              table.column(column).visible(false);
            });
            const cases_search = document.querySelector('#cases_search');
            cases_search.value = '';
            search({ target: cases_search });
            table.searchPanes.clearSelections();
            resetColumnSelectInputs();
            table.draw();
          }

          // The options for Advanced Search are added dynamically based on
          // which columns are visible ("checked" on the column dropdown)
          // TODO add triangle style to select box
          function setAdvancedSearchFields() {
            const container = document.querySelector(
              '.advanced-search__fields'
            );
            container.innerHTML = null;
            const columns = document.querySelectorAll('.select__list input');
            columns.forEach((column) => {
              if (column.checked) {
                let wrapper = document.createElement('div');
                wrapper.classList.add('advanced-search__element');
                let input = document.createElement('input');
                let label = document.createElement('label');
                label.htmlFor = column.dataset.id;
                label.innerText = column.name;
                input.id = column.dataset.id;
                input.type = column.dataset.type;
                input.classList.add('advanced-search__input');

                // For Date Fields, we need a select option box for
                // inequalities
                if (input.type === 'date') {
                  let div = document.createElement('div');
                  div.classList.add('advanced-search__select');
                  let select = document.createElement('select');
                  let greaterThan = document.createElement('option');
                  greaterThan.innerText = '>';
                  greaterThan.value = '>';
                  let lessThan = document.createElement('option');
                  lessThan.innerText = '<';
                  lessThan.value = '<';
                  let equalTo = document.createElement('option');
                  equalTo.innerText = '=';
                  equalTo.value = '=';
                  select.appendChild(greaterThan);
                  select.appendChild(lessThan);
                  select.appendChild(equalTo);
                  div.appendChild(label);
                  div.appendChild(select);
                  div.appendChild(input);
                  container.append(div);
                  input.addEventListener('change', (e) => {
                    filterDateColumn(e.target.value, column.id, select.value);
                    table.draw();
                  });
                  select.addEventListener('change', (e) => {
                    filterDateColumn(input.value, column.id, e.target.value);
                    table.draw();
                  });
                } else {
                  // For regular text fields, we just need the inpu
                  // and label
                  wrapper.appendChild(label);
                  wrapper.appendChild(input);
                  container.append(wrapper);
                  input.addEventListener('keyup', (e) =>
                    searchColumn(e, column.id)
                  );
                  input.addEventListener('keydown', (e) =>
                    searchColumn(e, column.id)
                  );
                }
              }
            });
          }

          const cases_search = document.querySelector('#cases_search');
          const cases_select = document.querySelector('#cases_select');
          const cases_column_select = document.querySelector(
            '#columnsSelectButton'
          );
          const cases_reset = document.querySelector('.cases__reset');
          const advanced_search = document.querySelector('.advanced_search p');

          cases_search.addEventListener('keyup', search);
          cases_select.addEventListener('change', filter);
          cases_column_select.addEventListener('click', applyColumnChanges);
          cases_reset.addEventListener('click', resetTable);
          advanced_search.addEventListener('click', toggleCasesAdvancedSearch);
        },
      });
    },
  });
}
