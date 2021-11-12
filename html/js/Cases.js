function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
  };

  return text.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
}

$(document).ready(function () {
  let visibleColumns;
  let hiddenColumns;
  let columnsNames;
  $.ajax({
    url: 'lib/php/data/cases_columns_load.php',
    dataType: 'json',
    error: function () {
      alert('Sorry, there is an error in your ClinicCases configuration');
      return true;
    },
    success: function (data) {
      console.log(data);
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
      $.ajax({
        url: 'lib/php/data/cases_load.php',
        error: function () {
          console.log('error');
        },
        success: function (data) {
          const mobileBreakPoint = 567;
          const tabletBreakPoint = 768;
          const medBreakPoint = 992;
          const lgBreakPoint = 1024;
          const xlBreakPoint = 1440;

          const jsonData = JSON.parse(data);
          const asObjects = jsonData.aaData;
          const asArray = asObjects.map((value) => {
            const array = [...Object.entries(value)].map((value) => {
              return value[1];
            });
            return array;
          });
          columnNames = Object.keys(asObjects[0]);
          const table = $('#table_cases').DataTable({
            data: asArray,
            responsive: true,
            autoWidth: false,
            searchPanes: {
              cascadePanes: false,
              controls: true,
              orderable: true,
              initCollapsed: true,
              clear: true,
              className: 'cases__search-pane',
              collapse: true,
              dtOps: {
                info: true,
              },
            },

            columnDefs: [
              {
                visible: false,
                searchPanes: { show: false },
                targets: hiddenColumns,
              },
              {
                visible: true,
                searchPanes: { show: true, initCollapsed: true },
                targets: visibleColumns,
              },
              {
                visible: window.innerWidth > xlBreakPoint,
                searchPanes: {
                  show: window.innerWidth > xlBreakPoint,
                  initCollapsed: true,
                },
                targets: [5],
              },
              {
                visible: window.innerwidth > lgBreakPoint,
                searchPanes: {
                  show: window.innerWidth > lgBreakPoint,
                  initCollapsed: true,
                },
                targets: [19],
              },
            ],
          });

          initializeSearchPanes();

          filter();
          setColumnVisibilty();

          function filter(e = null) {
            // defaults to open cases
            value = e?.target.value || 'open';

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
            table.draw();
          }

          function search(e) {
            const keyword = e.target.value;
            table.search(keyword);
            table.draw();
          }

          function setColumnVisibilty() {
            var w = window.innerWidth;
            const organizationColumn = table.column(5);
            const dispositionColumn = table.column(19);
            // Only shows Organization column on XL screens
            organizationColumn.visible(w > xlBreakPoint);
            // Only show Disposition column on LG screens
            dispositionColumn.visible(w > lgBreakPoint);
          }
          function toggleCasesAdvancedSearch() {
            this.classList.toggle('advanced_search--open');

            table.searchPanes.container()[0].hidden =
              !table.searchPanes.container()[0].hidden;
          }
          function initializeSearchPanes() {
            table.searchPanes.container().prependTo(table.table().container());
            table.searchPanes.resizePanes();
            table.searchPanes.container()[0].hidden = true;
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

            const id = e.target.dataset.select;
            const options = document.querySelector(id);
            options.classList.toggle('closed');
          }

          function resetTable() {
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
            table.draw();
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
          window.addEventListener('resize', setColumnVisibilty);
          advanced_search.addEventListener('click', toggleCasesAdvancedSearch);
        },
      });
    },
  });
});
