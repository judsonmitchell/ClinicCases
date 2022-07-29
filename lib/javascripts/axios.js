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

export const getDocuments = (id, search, update = null, list_view = null) => {
  return axios
    .post(`lib/php/data/cases_documents_load.php`, { id, list_view, search, update })
    .then((res) => res.data);
};