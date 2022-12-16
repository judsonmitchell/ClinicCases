export const getCaseView = (id) => {
  return axios.post(`lib/php/data/open_case_load.php`, {
    id,
  });
};
export const getCaseData = (id) => {
  return axios.post(`lib/php/data/cases_case_data_load.php`, {
    id,
  });
};

export const getAssignedUsersView = (id) => {
  return axios.post(
    `lib/php/users/cases_detail_assigned_people_refresh_load.php`,
    { id },
  );
};

export const getAssignedUsersInterface = (id) => {
  return axios
    .post('lib/php/users/cases_detail_user_chooser_load.php', {
      case_id: id,
    })
    .then((res) => res.data);
};

export const assignUsersToCase = (id, usersArray) => {
  return axios.get('lib/php/users/add_user_to_case.php', {
    params: {
      users_add: usersArray,
      case_id: id,
    },
  });
};

export const processCaseNotes = (data) => {
  return axios.post(`lib/php/data/cases_casenotes_process.php`, data);
};

export const reloadCaseNoteData = async (id, value = '') => {
  const response = await getCaseNotes(id, true, value);
  const notesContainer = document.querySelector(
    `#nav-${id}-notes .case_detail_panel_casenotes`,
  );
  notesContainer.innerHTML = response.data;
};

export const getCaseNotes = (id, update, search) => {
  let body = { case_id: id };
  if (update) {
    body = { ...body, update: true, search };
  }
  return axios.post(`lib/php/data/cases_casenotes_load.php`, body);
};

export const deleteCaseNote = (caseNoteId) => {
  const body = {
    query_type: 'delete',
    csenote_casenote_id: caseNoteId,
  };
  return axios.post(`lib/php/data/cases_casenotes_process.php`, body);
};
export const getTimer = (caseId) => {
  return axios
    .post(`html/templates/interior/timer.php`, { case_id: caseId })
    .then((res) => res.data);
};

export const getDocuments = (
  id,
  search,
  update = null,
  list_view = null,
  container = null,
) => {
  return axios
    .post(`lib/php/data/cases_documents_load.php`, {
      id,
      list_view,
      search,
      update,
      container,
      path: container,
    })
    .then((res) => res.data);
};

export const processDocuments = (args) => {
  return axios
    .post(`lib/php/data/cases_documents_process.php`, args)
    .then((res) => res.data);
};

export const uploadFile = async (qqfile, path, case_id) => {
  const formData = new FormData();
  formData.append('file', qqfile);
  formData.append('path', path);
  formData.append('case_id', case_id);

  return axios
    .post(`lib/php/utilities/file_upload.php`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    .then((res) => res.data);
};

export const getCaseEventData = async (case_id, q) => {
  return axios
    .post(`lib/php/data/cases_events_load.php`, { case_id, q })
    .then((res) => res.data);
};

export const processEvents = async (args) => {
  return axios
    .post(`lib/php/data/cases_events_process.php`, args)
    .then((res) => res.data);
};
export const getUserChooserList = (case_id, value) => {
  return axios
    .post('lib/php/data/cases_load_users_for_chooser.php', { case_id, value })
    .then((res) => res.data);
};
export const deleteCaseEvent = (caseNoteId) => {
  const body = {
    query_type: 'delete',
    csenote_casenote_id: caseNoteId,
  };
  return axios.post(`lib/php/data/cases_casenotes_process.php`, body);
};