class Table {
  columns;
  data;
  table;
  body;
  limit = 10;
  table;
  container;
  page = 1;
  pagination;
  controls;
  facets;
  facetField;
  filteredData;
  currentSort;
  sortedData;

  constructor({ columns, data, containerId, limit, facets, facetField }) {
    this.columns = columns;
    this.data = [...data];
    this.container = document.querySelector(containerId);
    if (limit) {
      this.limit = limit;
    }
    this.facets = facets;
    this.filteredData = [...data];
    this.sortedData = [...data];
    this._initDataToDefaultFacet();
    this._createControlsContainer();
    this._createFacetsAndSearch();
    this._createAdvancedSearchToggle();
    this._createColumnControls();
    this._createAdvancedSearchContainer();
    this._createPrint();
    this._createReset();
    this._createTable();
    this._createHeader();
    this._createBody();
    this._attachContainer();
    this._renderPage(this.page);
    this._createPagination(this.page, this.limit, this.filteredData);
  }

  _initDataToDefaultFacet() {
    const defaultFacet = this.facets.find((facet) => facet.default);
    const func = defaultFacet.filter;
    this.filteredData = this.sortedData.filter((item) => {
      return func(item);
    });
  }

  _createTable() {
    this.table = document.createElement('table');
  }

  _createBody() {
    this.body = document.createElement('tbody');
    this.table.appendChild(this.body);
  }
  _attachContainer() {
    this.container.append(this.table);
  }
  _renderPage() {
    var currentPageData = this._getPageData(
      this.filteredData,
      this.limit,
      this.page
    );
    this._insertData(currentPageData);
  }

  _renderNextPage() {
    this.page++;
    this.body.innerHTML = null;
    this._renderPage();
    this._updatePagination();
  }
  _renderPrevPage() {
    this.page--;
    this.body.innerHTML = null;
    this._renderPage();
    this._updatePagination();
  }

  _getPageData(data, limit, page) {
    const start = (page - 1) * limit;
    const pageData = data.slice(start, start + limit);
    return pageData;
  }

  _createHeader() {
    var header = this.table.createTHead();
    var row = header.insertRow(0);
    this.columns.forEach((col, index) => {
      var cell = row.insertCell(index);
      cell.innerHTML = col.name;
      cell.setAttribute('data-col', index);
      cell.setAttribute('data-fieldName', col.fieldName);
      if (col.hidden) cell.style.display = 'none';
      cell.addEventListener('click', (event) => this._sortByColumn(event));
    });
  }

  _sortByColumn(event) {
    const column = event.target;
    const fieldName = column.dataset.fieldname;
    const headings = Array.from(this.table.querySelectorAll('td')).filter(
      (col) => col != column
    );

    headings.forEach((head) => {
      head.classList.remove('asc');
      head.classList.remove('desc');
    });

    if (!column.classList.length || column.classList.contains('desc')) {
      column.classList.add('asc');
      column.classList.remove('desc');
      this.filteredData = this.filteredData.sort((a, b) => {
        return a[fieldName] > b[fieldName] ? 1 : -1;
      });
      this.sortedData = this.sortedData.sort((a, b) => {
        return a[fieldName] > b[fieldName] ? 1 : -1;
      });
    } else {
      column.classList.add('desc');
      column.classList.remove('asc');
      this.filteredData = this.filteredData.sort((a, b) => {
        return a[fieldName] > b[fieldName] ? -1 : 1;
      });
      this.sortedData = this.sortedData.sort((a, b) => {
        return a[fieldName] > b[fieldName] ? -1 : 1;
      });
    }

    this.page == 1;
    this.body.innerHTML = null;
    this._renderPage();
    this._updatePagination();
  }
  _insertData(data) {
    data.forEach((item) => {
      var row = this.body.insertRow(-1);
      row.setAttribute('data-item', item.id);
      row.style.display = 'table-row';
      const values = Object.values(item);
      values.forEach((val, valIndex) => {
        var cell = row.insertCell(valIndex);
        cell.innerHTML = val;
        cell.setAttribute('data-col', valIndex);

        if (this.columns[valIndex].hidden) cell.style.display = 'none';
      });
    });
  }

  _createPagination() {
    this.pagination = document.createElement('div');
    this.pagination.classList.add('table__pagination');
    this.pagination.navigation = document.createElement('div');
    this.pagination.navigation.classList.add('pagination__navigation');
    this.pagination.next = document.createElement('button');
    this.pagination.summary = document.createElement('p');
    this.pagination.prev = document.createElement('button');
    this.pagination.total = document.createElement('p');
    this.pagination.next.addEventListener('click', () => {
      this._renderNextPage.call(this);
    });
    this.pagination.prev.addEventListener('click', () => {
      this._renderPrevPage.call(this);
    });

    this.pagination.next.innerText = 'Next';
    this.pagination.prev.innerText = 'Prev';
    this.pagination.navigation.appendChild(this.pagination.prev);
    this.pagination.navigation.appendChild(this.pagination.summary);
    this.pagination.navigation.appendChild(this.pagination.next);

    this.pagination.appendChild(this.pagination.navigation);
    this.pagination.appendChild(this.pagination.total);
    this.container.appendChild(this.pagination);

    this._updatePagination();
  }

  _updatePagination() {
    const totalItems = this.filteredData.length;
    const totalPages = Math.ceil(totalItems / this.limit);
    this.pagination.summary.innerHTML = `Page ${this.page} of ${
      totalPages || 1
    }`;
    this.pagination.total.innerText = `${totalItems} total cases`;
    this.pagination.prev.disabled = this.page == 1;
    this.pagination.next.disabled = this.page == totalPages;
  }

  _createControlsContainer() {
    this.controls = document.createElement('div');
    this.controls.classList.add('table__controls');
    this.container.appendChild(this.controls);
  }
  _createColumnControls() {
    const columns = document.createElement('div');
    columns.className = 'controls__columns';
    const label = document.createElement('p');
    label.innerText = 'Columns';
    label.classList.add('controls__label');

    columns.appendChild(label);
    const wrapper = document.createElement('div');
    wrapper.classList.add('controls__dropdown');
    wrapper.classList.add('hidden');
    columns.appendChild(wrapper);
    label.addEventListener('click', () => {
      wrapper.classList.toggle('hidden');
    });
    this.columns.forEach((column, index) => {
      const select = document.createElement('div');
      select.classList.add('dropdown__option');
      const label = document.createElement('label');
      label.innerHTML = `<input name='dropdown-option' ${
        column.hidden ? '' : 'checked'
      } type='checkbox' value='${index}' /> ${column.name}`;
      select.appendChild(label);
      wrapper.appendChild(select);
    });
    const bottomBar = document.createElement('div');
    bottomBar.classList.add('dropdown__bottom-bar');
    const button = document.createElement('button');
    button.innerText = 'Apply Changes';
    button.addEventListener('click', () => {
      const checkboxes = document.querySelectorAll('[name="dropdown-option"]');
      checkboxes.forEach((box) => {
        const columns = document.querySelectorAll(`[data-col="${box.value}"]`);
        columns.forEach((column) => {
          column.style.display = box.checked ? '' : 'none';
        });
        this.columns[box.value].hidden = !box.checked;
        wrapper.classList.add('hidden');
      });
      this._createAdvancedSearchContainer();
    });
    bottomBar.appendChild(button);
    wrapper.appendChild(bottomBar);
    this.controls.append(columns);
  }

  _createPrint() {
    const button = document.createElement('button');
    button.innerText = 'Print';
    button.classList.add('neutral-button');
    this.controls.appendChild(button);
    button.addEventListener('click', () => {
      this._printTable();
    });
  }

  _createReset() {
    const button = document.createElement('button');
    button.innerText = 'Reset';
    button.classList.add('secondary-button');
    this.controls.appendChild(button);
    button.addEventListener('click', () => {
      this._resetTable();
    });
  }

  _printTable() {
    // TODO add options for exporting
    // Ask judson
    const wrapper = document.createElement('div');
    wrapper.setAttribute('id', 'table_cases');
    const table = document.createElement('table');
    wrapper.appendChild(table);
    var header = table.createTHead();
    var row = header.insertRow(0);
    this.columns.forEach((col, index) => {
      var cell = row.insertCell(index);
      cell.innerHTML = col.name;
      cell.setAttribute('data-col', index);
      if (col.hidden) cell.style.display = 'none';
    });
    const body = document.createElement('tbody');
    table.appendChild(body);
    this.filteredData.forEach((data) => {
      var row = body.insertRow(-1);
      const values = Object.values(data);
      values.forEach((val, valIndex) => {
        var cell = row.insertCell(valIndex);
        cell.innerHTML = val;
        cell.setAttribute('data-col', valIndex);
        if (this.columns[valIndex].hidden) cell.style.display = 'none';
      });
    });

    var worker = html2pdf().from(wrapper).save('Cases');
  }

  _resetTable() {
    const search = document.querySelector('[name="search"]');
    const headings = this.table.querySelectorAll('td');
    headings.forEach((head) => {
      head.classList.remove('asc');
      head.classList.remove('desc');
    });
    search.value = '';
    const facet = document.querySelector('[name="facets"]');
    facet.value = this.facets.find((facet) => facet.default).value;
    const inputs = [...document.querySelectorAll('.advanced-search__container input'), document.querySelectorAll('.advanced-search__container select')];
    inputs.forEach(input => input.value = '');
    this.page = 1;
    this.filteredData = [...this.data];
    this.sortedData = [...this.data];
    this.body.innerHTML = null;
    this._renderPage();
    this._updatePagination();
  }

  _createAdvancedSearchToggle() {
    const wrapper = document.createElement('div');
    wrapper.classList.add('controls__advanced-search');
    const p = document.createElement('p');
    p.innerText = 'Advanced Search';
    wrapper.appendChild(p);
    p.addEventListener('click', () => {
      wrapper.classList.toggle('open');
      this.advancedSearchFields.classList.toggle('hidden');
    });
    this.controls.appendChild(wrapper);
  }

  _createAdvancedSearchContainer() {
    this.advancedSearchFields = document.createElement('div');
    this.advancedSearchFields.classList.add(
      'advanced-search__container',
      'hidden'
    );

    this.columns.forEach((col, index) => {
      if (col.type === 'date') {
        const wrapper = document.createElement('div');
        wrapper.setAttribute('data-col', index);
        wrapper.setAttribute('data-fieldname', col.fieldName);
        wrapper.style.display = col.hidden ? 'none' : '';

        const dateSelect = document.createElement('select');
        dateSelect.setAttribute('data-fieldname', col.fieldName);
        const greaterThan = document.createElement('option');
        const lessThan = document.createElement('option');
        const equalTo = document.createElement('option');

        greaterThan.value = 'greater_than';
        greaterThan.innerText = '>';
        lessThan.value = 'less_than';
        lessThan.innerText = '<';
        equalTo.value = 'equal_to';
        equalTo.innerText = '=';

        dateSelect.appendChild(greaterThan);
        dateSelect.appendChild(lessThan);
        dateSelect.appendChild(equalTo);

        wrapper.appendChild(dateSelect);

        const input = document.createElement('input');
        input.type = col.type;
        input.placeholder = col.name;
        input.setAttribute('data-col', index);
        input.setAttribute('data-fieldname', col.fieldName);
        input.style.display = col.hidden ? 'none' : '';
        wrapper.appendChild(input);

        this.advancedSearchFields.appendChild(wrapper);

        dateSelect.addEventListener('change', (event) => {
          this._filterByDate.call(this, event);
        });
        input.addEventListener('change', (event) => {
          this._filterByDate.call(this, event);
        });
      } else {
        const input = document.createElement('input');
        input.type = col.type;
        input.placeholder = col.name;
        input.setAttribute('data-col', index);
        input.setAttribute('data-fieldname', col.fieldName);
        input.style.display = col.hidden ? 'none' : '';
        this.advancedSearchFields.appendChild(input);
        input.addEventListener('keyup', (event) => {
          const input = event.target;
          const keywords = input.value;
          const fieldName = input.dataset.fieldname;
          this._filter(keywords, fieldName);
        });
      }
    });

    this.container.appendChild(this.advancedSearchFields);
  }

  _createFacetsAndSearch() {
    const wrapper = document.createElement('div');
    wrapper.classList.add('controls__search');
    this.controls.appendChild(wrapper);
    const facetSelect = document.createElement('select');
    facetSelect.setAttribute('name', 'facets');
    this.facets.forEach((facet) => {
      const option = document.createElement('option');
      option.value = facet.value;
      option.innerText = facet.label;
      facetSelect.appendChild(option);
    });
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'search';
    input.addEventListener('keyup', () => {
      const keywords = input.value;
      this._filter(keywords);
    });
    wrapper.appendChild(facetSelect);
    wrapper.appendChild(input);
  }

  _filter(keywords, fieldName = '') {
    let keywordArray = keywords.trim().split(' ');
    const facetSelect = document.querySelector('[name="facets"]');
    const facetValue = facetSelect.value;
    const facet = this.facets.find((facet) => facet.value === facetValue);
    const func = facet.filter;

    this.body.innerHTML = null;
    this.page = 1;
    this.filteredData = this.sortedData.filter((item) => {
      const keywordRegExpArray = keywordArray.map((word) => {
        return `(?=.*${word})`;
      });
      const isValidFacet = func(item);
      const exp = new RegExp(`${keywordRegExpArray.join('')}`, 'gim');
      const columnsToSearch = fieldName
        ? item[fieldName]
        : Object.values(item).join('');
      const containsKeyword = columnsToSearch.search(exp) > -1;
      return isValidFacet && containsKeyword;
    });

    this._renderPage();
    this._updatePagination();
  }

  // TODO apply one omre more advanced searchs
  _filterByDate(event) {
    const fieldName = event.target.dataset.fieldname;
  
    const select = document.querySelector(
      `select[data-fieldname="${fieldName}"`
    );
    const input = document.querySelector(
      `input[type="date"][data-fieldname=${fieldName}]`
    );
    const selectValue = select.value;
    const inputValue = input.value;
    if (!selectValue || !inputValue) return;
    this.body.innerHTML = null;
    this.page = 1;
    this.filteredData = this.sortedData.filter((item) => {
      if (selectValue == 'less_than') {
        return item[fieldName] < inputValue;
      }
      if (selectValue == 'greater_than') {
        return item[fieldName] > inputValue;
      }
      if (selectValue == 'equal_to') {
        return item[fieldName] == inputValue;
      }
    });
    this._renderPage();
    this._updatePagination();
  }
}
