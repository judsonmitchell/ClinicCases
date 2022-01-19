class Table {
  columns;
  data;
  table;
  body;
  limit = 25;
  table;
  container;
  page = 1;
  pagination;
  controls;
  facets;
  facetField;
  filteredData;

  constructor({ columns, data, containerId, limit, facets, facetField }) {
    this.columns = columns;
    this.data = data;
    this.container = document.querySelector(containerId);
    if (limit) {
      this.limit = limit;
    }
    this.facets = facets;
    this.filteredData = data;
    this._createControlsContainer();
    this._createFacetsAndSearch();
    this._createAdvancedSearchToggle();
    this._createColumnControls();
    this._createAdvancedSearchContainer();
    this._createPrint();
    this._createTable();
    this._createHeader();
    this._createBody();
    this._attachContainer();
    this._renderPage(this.page);
    this._createPagination(this.page, this.limit, this.filteredData);
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
    var pageData = this._getPageData(this.filteredData, this.limit, this.page);
    this._insertData(pageData);
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
      if (col.hidden) cell.style.display = 'none';
    });
  }
  _insertData(data) {
    data.forEach((data, index) => {
      var row = this.body.insertRow(-1);
      const values = Object.values(data);
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
    console.log({
      totalItems,
      totalPages,
    });
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
      const input = document.createElement('input');
      input.type = col.type;
      input.placeholder = col.name;
      input.setAttribute('data-col', index);
      input.style.display = col.hidden ? 'none' : '';
      this.advancedSearchFields.appendChild(input);
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
      const keyword = input.value;
      const facetValue = facetSelect.value;
      const facet = this.facets.find((facet) => facet.value === facetValue);
      const func = facet.filter;
      console.log(func);
      this._filter(keyword, func);
    });
    wrapper.appendChild(facetSelect);
    wrapper.appendChild(input);
  }

  _filter(keywords, func) {
    // TODO find how to match the first or MORE
    const keywordArray = keywords.trim().split(' ');
    this.body.innerHTML = null;
    this.page = 1;
    this.filteredData = this.data.filter((item) => {
      console.log(item.date_closed);
      const isValidFacet = func(item);
      const exp = new RegExp(keywordArray.join('|'), 'gim');
      const containsKeyword = Object.values(item).join('').search(exp) > -1;
      return isValidFacet && containsKeyword;
    });
    this._renderPage();
    this._updatePagination();
  }
}
