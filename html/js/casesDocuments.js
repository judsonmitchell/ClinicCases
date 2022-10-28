//
//Scripts for documents panel on cases tab
//

/* global escape, escapeHtml, unescape, notify, rte_toolbar, qq , isUrl */
import { live } from './live.js';
import {
  getDocuments,
  processDocuments,
  uploadFile,
} from '../../lib/javascripts/axios.js';
import {
  setFormValues,
  checkFormValidity,
  getFormValues,
  resetForm,
} from '../js/forms.js';
import { setCookie } from '../../lib/javascripts/cookies.js';

function createTrail(path) {
  let pathArray = path.split('/').map((p) => decodeURI(p));
  var pathString = '';
  pathArray.forEach((v, i) => {
    const pathName = decodeURI(v);
    const fullPath = pathArray.slice(0, i + 1).join('/');
    var pathItem = `> <a class="doc_trail_item" href="#" data-path="${fullPath}">${pathName}</a>`;
    pathString += pathItem;
  });

  return pathString;
}

const reloadDocuments = async (caseid) => {
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid="${caseid}"]`,
  );
  const { layout } = caseDetailPanel.dataset;
  const isList = layout === 'List';
  const html = await getDocuments(
    caseid,
    null,
    true,
    isList == true || null,
    null,
  );
  const documentsContainer = document.querySelector(
    `#nav-${caseid}-documents .case_detail_panel`,
  );
  documentsContainer.innerHTML = html;
};

// Switch documents to list view
live('click', 'documents_view_chooser--list', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  caseDetailsRef.dataset.layout = 'List';
  const caseId = caseDetailsRef.dataset.caseid;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const chooser = el.closest('.documents_view_chooser');
  chooser.classList.remove('grid');
  chooser.classList.add('list');
  const gridImage = chooser.querySelector('.documents_view_chooser--grid img');
  const listImage = chooser.querySelector('.documents_view_chooser--list img');
  gridImage.src = 'html/ico/grid-unselected.png';
  listImage.src = 'html/ico/list-selected.png';
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const search =
    document.querySelector(`#nav-${caseId}-documents .documents_search`)
      .value || null;
  const html = await getDocuments(caseId, search, true, 'yes', currentPath);

  documentsContainer.innerHTML = html;
  setCookie('cc_docs_view', 'list', 2);
});
// Switch documents to grid view
live('click', 'documents_view_chooser--grid', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  caseDetailsRef.dataset.layout = 'Grid';
  const caseId = caseDetailsRef.dataset.caseid;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const chooser = el.closest('.documents_view_chooser');
  chooser.classList.remove('list');
  chooser.classList.add('grid');
  const gridImage = chooser.querySelector('.documents_view_chooser--grid img');
  const listImage = chooser.querySelector('.documents_view_chooser--list img');
  gridImage.src = 'html/ico/grid-selected.png';
  listImage.src = 'html/ico/list-unselected.png';

  const search =
    document.querySelector(`#nav-${caseId}-documents .documents_search`)
      .value || null;
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const html = await getDocuments(caseId, search, true, null, currentPath);
  documentsContainer.innerHTML = html;
  setCookie('cc_docs_view', 'grid', 2);
});
// Search documents
live('change', 'documents_search', async (event) => {
  const el = event.target;
  const search = el.value;
  const caseId = el.dataset.caseid;
  const caseDetailsRef = el.closest('.case_details_documents');
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const listView = caseDetailsRef.dataset.currentpath === 'List' ? true : null;

  const html = await getDocuments(caseId, search, true, listView || null);
  documentsContainer.innerHTML = html;
});
//User clicks a folder or document
live('click', 'doc_item_folder', async (event, el) => {
  event.preventDefault();
  const path = el.dataset.path;

  const caseDetailsRef = el.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const pathDisplay = document.querySelector(
    `#nav-${caseId}-documents .path_display`,
  );
  pathDisplay.innerHTML = createTrail(path);
  caseDetailsRef.dataset.currentpath = path;
  // const docType = el.classList.contains('folder') ? 'folder' : 'document';
  // const itemId = el.dataset.id;
  // console.log({ docType,path, caseId, itemId });
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  const isList = caseDetailsRef.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList || null, path);
  documentsContainer.innerHTML = html;
});

// User drags and drops a file or folder
let draggedItem = null;
// need this to allow drop
live('dragover', 'doc_item_folder', (event) => {
  event.preventDefault();
});
live('drag', 'doc_item', (_event, item) => {
  draggedItem = item;
});
live('dragenter', 'doc_item_folder', (_event, folder) => {
  folder.classList.add('doc_item_folder--active');
});
live('dragend', 'doc_item', () => {
  const folders = document.querySelectorAll('.doc_item_folder');
  folders.forEach((folder) =>
    folder.classList.remove('doc_item_folder--active'),
  );
});
live('drop', 'doc_item_folder', async (event, folder) => {
  event.preventDefault();
  const item_id = draggedItem.dataset.id;
  const caseDetailsRef = folder.closest('.case_details_documents');
  const case_id = caseDetailsRef.caseid;
  const path = folder.dataset.path;
  const selection_path = draggedItem.dataset.path;
  const docType = draggedItem.classList.contains('folder') ? 'folder' : 'item';
  if (path == selection_path) return;
  try {
    await processDocuments({
      case_id,
      action: 'cut',
      item_id,
      target_path: path,
      selection_path,
      doc_type: docType,
    });
    draggedItem.classList.add('fadeOut');
    setTimeout(() => {
      draggedItem.classList.add('hidden');
      draggedItem = null;
    }, 500);
  } catch (err) {
    console.log(err);
  } finally {
    setTimeout(() => {
      draggedItem = null;
    }, 500);
  }
});

// NAVIGATING BETWEEN DIRECTORIES
// user clicks on home directory doc_trail_home
live('click', 'doc_trail_home', async (event, homePanel) => {
  const caseDetailsRef = homePanel.closest('.case_details_documents');
  const caseId = caseDetailsRef?.dataset.caseid;
  caseDetailsRef.dataset.currentpath = 'Home';
  const pathDisplay = homePanel
    .closest('.case_documents_submenu')
    .querySelector('.path_display');
  pathDisplay.innerHTML = '';
  const isList = caseDetailsRef?.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList, null);
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  documentsContainer.innerHTML = html;
});
// user clicks on another item in path doc_trail_path
live('click', 'doc_trail_item', async (_event, trail) => {
  const caseDetailsRef = trail.closest('.case_details_documents');
  const caseId = caseDetailsRef?.dataset.caseid;
  const path = trail.dataset.path;
  caseDetailsRef.dataset.currentpath = path;
  const pathDisplay = trail
    .closest('.case_documents_submenu')
    .querySelector('.path_display');
  pathDisplay.innerHTML = createTrail(path);
  console.log({ path });
  const isList = caseDetailsRef?.dataset.layout === 'List' ? true : null;
  const html = await getDocuments(caseId, null, true, isList, path);
  const documentsContainer = document.querySelector(
    `#nav-${caseId}-documents .case_detail_panel`,
  );
  documentsContainer.innerHTML = html;
});
// OPENING DOCUMENTS
live('click', 'docs_new_folder', (_event, button) => {
  const caseDetailsRef = button.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  const newFolderForm = document.querySelector('#newFolderModal form');
  setFormValues(newFolderForm, { caseId, isList, currentPath });
  const newFolderModal =
    bootstrap.Modal.getInstance('#newFolderModal') ||
    new bootstrap.Modal('#newFolderModal');
  newFolderModal.show();
});
// Adding folder
live('click', 'doc_new_folder_submit', async (event, button) => {
  const modal = document.querySelector('#newFolderModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const isValid = checkFormValidity(form);
  if (isValid == false) {
    form.classList.add('invalid');
    alertify.error('Please provide a folder name.');
    return;
  }
  const values = getFormValues(form);
  const { folderName, caseId, isList, currentPath } = values;
  try {
    await processDocuments({
      case_id: caseId,
      action: 'newfolder',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'folder',
      new_folder: folderName,
      container: currentPath || null,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
  }
});

// adding documents
let newDocEditor;
live('click', 'docs_new_document', (_event, button) => {
  const caseDetailsRef = button.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  if (!newDocEditor) {
    ClassicEditor.create(document.querySelector('#newDocEditor'))
      .then((editor) => (newDocEditor = editor))
      .catch((error) => {
        console.error(error);
      });
  }
  const newDocumentForm = document.querySelector('#newDocumentModal form');
  setFormValues(newDocumentForm, { caseId, isList, currentPath });
  const newDocumentModal =
    bootstrap.Modal.getInstance('#newDocumentModal') ||
    new bootstrap.Modal('#newDocumentModal');
  newDocumentModal.show();
});
live('click', 'doc_new_document_submit', async (event, button) => {
  const modal = document.querySelector('#newDocumentModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const text = newDocEditor.getData();
  const textarea = modal.querySelector('#newDocEditor');
  textarea.value = text;
  const values = getFormValues(form);
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { folderName, caseId, isList, currentPath, doc_name, locked } = values;
  try {
    await processDocuments({
      case_id: caseId,
      action: 'new_ccd',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'item',
      new_folder: folderName,
      container: currentPath || null,
      ccd_name: doc_name,
      ccd_text: text,
      path: currentPath,
      local_file_name: `${doc_name}.ccd`,
      ccd_lock: locked ? 'yes' : null,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
    newDocEditor.setData('');
  }
});
// editing documents
let editDocEditor;
live('click', 'ccd', async (_event, ccd) => {
  const caseDetailsRef = ccd.closest('.case_details_documents');
  const caseId = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout == 'List' ? true : null;
  const currentPath =
    caseDetailsRef.dataset.currentpath != 'Home'
      ? caseDetailsRef.dataset.currentpath
      : null;
  if (!editDocEditor) {
    ClassicEditor.create(document.querySelector('#editDocEditor'))
      .then((editor) => (editDocEditor = editor))
      .catch((error) => {
        console.error(error);
      });
  }
  const editDocumentForm = document.querySelector('#editDocumentModal form');
  const doc_id = ccd.dataset.id;
  const ccdoc = await processDocuments({
    item_id: doc_id,
    action: 'open',
  });
  // if readonly, show doc
  // if not readonly, show edit form
  if (ccdoc.ccd_permissions === 'yes') {
    setFormValues(editDocumentForm, {
      caseId,
      isList,
      currentPath,
      doc_name: ccdoc.ccd_title,
      locked: ccdoc.ccd_locked == 'yes',
      ccd_id: ccdoc.ccd_id,
    });
    editDocEditor.setData(ccdoc.ccd_content);
    const editDocumentModal =
      bootstrap.Modal.getInstance('#editDocumentModal') ||
      new bootstrap.Modal('#editDocumentModal');
    editDocumentModal.show();
  } else {
    const viewCCDModal =
      bootstrap.Modal.getInstance('#viewCCDModal') ||
      new bootstrap.Modal('#viewCCDModal');
    const viewCCDLabel = document.getElementById('viewCCDLabel');
    viewCCDLabel.innerText = ccdoc.ccd_title;
    const viewCCDContent = document.getElementById('viewCCDContent');
    viewCCDContent.innerHTML = ccdoc.ccd_content;

    viewCCDModal.show();
  }
});
live('click', 'doc_edit_document_submit', async (event, button) => {
  const modal = document.querySelector('#editDocumentModal');
  const newFolderModal = bootstrap.Modal.getInstance(modal);
  const form = modal.querySelector('form');
  const text = editDocEditor.getData();
  const textarea = modal.querySelector('#editDocEditor');
  textarea.value = text;
  const values = getFormValues(form);
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { folderName, caseId, isList, currentPath, doc_name, locked, ccd_id } =
    values;
  try {
    const response = await processDocuments({
      case_id: caseId,
      action: 'update_ccd',
      target_path: currentPath,
      selection_path: folderName,
      doc_type: 'item',
      new_folder: folderName,
      container: currentPath || null,
      ccd_name: doc_name,
      ccd_text: text,
      path: currentPath,
      local_file_name: `${doc_name}.ccd`,
      ccd_lock: locked ? 'yes' : null,
      ccd_id,
    });
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    newFolderModal.hide();
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList || null,
      currentPath || null,
    );
    documentsContainer.innerHTML = html;
  } catch (error) {
    alertify.error(error.message);
  } finally {
    resetForm(form);
    editDocEditor.setData('');
  }
});
// uploading files
live('click', 'docs_upload_file', async (_event, el) => {
  const caseDetailsRef = el.closest('.case_details_documents');
  const currentPath = caseDetailsRef.dataset.currentpath;
  const case_id = caseDetailsRef.dataset.caseid;
  const isList = caseDetailsRef.dataset.layout === 'List';
  const label = document.querySelector('#uploadFileLabel span');
  const pathArray = currentPath.split('/');
  label.innerText = pathArray[pathArray.length - 1];
  const newDocumentModal =
    bootstrap.Modal.getInstance('#uploadFileModal') ||
    new bootstrap.Modal('#uploadFileModal');
  newDocumentModal.show();
  const form = document.querySelector(`#uploadFileModal form`);
  setFormValues(form, { caseId: case_id, currentPath, isList });
});
let dropArea = document.querySelector('#dropArea');
live('dragenter', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.add('highlight');
});
live('dragleave', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.remove('highlight');
});
live('dragover', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.add('highlight');
});
live('drop', 'file-upload', async (event, el) => {
  event.preventDefault();
  event.stopPropagation();
  dropArea.classList.remove('highlight');
  let dt = event.dataTransfer;
  let files = dt.files;
  const form = el.closest('form');
  const { currentPath, caseId, isList } = getFormValues(form);
  handleDrop(files, caseId, currentPath, isList);
});
live('change', 'file-upload-input', async (event, input) => {
  let files = input.files;
  const form = input.closest('form');
  const { currentPath, caseId, isList } = getFormValues(form);
  handleDrop(files, caseId, currentPath, isList);
});

const handleDrop = async (files, case_id, path, isList) => {
  if (path === 'Home') {
    path = '';
  }
  for (const file of [...files]) {
    const res = await uploadFile(file, path, case_id);
    if (res.error) {
      alertify.error(res.error);
      return;
    }
    if (res.success) {
      alertify.success('File uploaded successfully!');
      const html = await getDocuments(
        case_id,
        null,
        true,
        isList == 'true' || null,
        path || null,
      );
      const documentsContainer = document.querySelector(
        `#nav-${case_id}-documents .case_detail_panel`,
      );
      documentsContainer.innerHTML = html;
      const newDocumentModal =
        bootstrap.Modal.getInstance('#uploadFileModal') ||
        new bootstrap.Modal('#uploadFileModal');
      newDocumentModal.hide();
    }
  }
};

// upload by url

// toggle form
live('click', 'file_or_url_switch', (_event, el) => {
  const isChecked = el.checked;
  const modal = document.querySelector('#uploadFileModal');
  if (isChecked) {
    modal.classList.add('open');
  } else {
    modal.classList.remove('open');
  }
});
live('click', 'doc_upload_file_submit', async (_event, el) => {
  const form = el.closest('form');
  const errors = checkFormValidity(form);
  const isValid = errors == true;
  if (!isValid) {
    form.classList.add('invalid');
    alertify.error(`Please provide values for ${errors}`);
    return;
  }
  const { caseId, isList, currentPath, url_name, url } = getFormValues(form);
  try {
    const response = await processDocuments({
      action: 'add_url',
      case_id: caseId,
      isList: isList === 'true' || null,
      path: currentPath == 'Home' ? '' : currentPath,
      url_name,
      url,
    });
    alertify.success('File uploaded successfully!');
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList == 'true' || null,
      path || null,
    );
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
    alertify.success('Web address added!');
  } catch (err) {
    alertify.error(err.message);
  } finally {
    const newDocumentModal =
      bootstrap.Modal.getInstance('#uploadFileModal') ||
      new bootstrap.Modal('#uploadFileModal');
    newDocumentModal.hide();
  }
});

const getFileType = (classList) => {
  if (classList.contains('folder')) {
    return 'folder';
  }
  if (classList.contains('ccd')) {
    return 'ccd';
  }
  return 'download';
};

const openContextMenu = (e) => {
  const target = e.target;
  const doc_item = target.classList.contains('doc_item')
    ? target
    : target.closest('.doc_item');
  const case_detail_panel = target.classList.contains('case_detail_panel')
    ? target
    : target.closest('.case_detail_panel');
  if (doc_item) {
    e.preventDefault();
    const { pageX, pageY } = e;
    const contextMenu = document.getElementById('contextMenu');
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${pageX}px`;
    contextMenu.style.top = `${pageY}px`;
    doc_item.classList.add('selected');
    // Add case details so they're available inside the context menu
    console.log(doc_item);
    const caseDetails = contextMenu.querySelector('.context-menu-details');
    caseDetails.dataset.caseid = doc_item.dataset.caseid;
    caseDetails.dataset.id = doc_item.dataset.id;
    caseDetails.dataset.type = getFileType(doc_item.classList);
    return;
  } else {
    if (case_detail_panel) {
      e.preventDefault();
      const { pageX, pageY } = e;
      const contextMenu = document.getElementById('contextMenu');
      contextMenu.style.display = 'block';
      contextMenu.style.left = `${pageX}px`;
      contextMenu.style.top = `${pageY}px`;
      contextMenu.classList.add('non-doc');
      // Add case details so they're available inside the context menu
      const caseDetails = contextMenu.querySelector('.context-menu-details');
      caseDetails.dataset.caseid = e.target.closest(
        '.case_details_documents',
      ).dataset.caseid;
      console.log(caseDetails.dataset);
      caseDetails.dataset.id = '';
      caseDetails.dataset.type = '';
    }
  }
  // Nina also open when no doc_item if we're in a case_detail_panel
  // for pasting
};
const hideContextMenu = () => {
  const contextMenu = document.getElementById('contextMenu');
  contextMenu.style.display = 'none';
  contextMenu.classList.remove('non-doc');
  const doc_items = document.querySelectorAll('.doc_item');
  doc_items.forEach((item) => item.classList.remove('selected'));
};
document.oncontextmenu = openContextMenu;
document.addEventListener('click', (e) => {
  const target = e.target;
  const contextMenu = target.classList.contains('context-menu')
    ? target
    : target.closest('.context-menu');
  if (!contextMenu) {
    hideContextMenu();
  }
});

// Open file from context menu
live('click', 'context-menu-open', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  let clickItem;
  if (type === 'download') {
    clickItem = document.querySelector(
      `[data-id="${id}"][data-caseid="${caseid}"] a`,
    );
  } else {
    clickItem = document.querySelector(
      `.doc_item[data-id="${id}"][data-caseid="${caseid}"]`,
    );
  }
  if (clickItem) {
    clickItem.click();
  }
});
// Cut file from context menu
live('click', 'context-menu-cut', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const doc_item = document.querySelector(
    `[data-id="${id}"][data-caseid="${caseid}"]`,
  );
  const { path } = doc_item.dataset;
  const case_details_documents = doc_item.closest('.case_detail_panel');
  // Store cut data
  const cut_data = new Array(id, type, path, caseid);
  case_details_documents.dataset.cutdata = cut_data;
  case_details_documents.dataset.copydata = '';
  hideContextMenu();
});
// Copy file from context menu
live('click', 'context-menu-copy', (e) => {
  // Nina - don't allow this if it's a folder
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const doc_item = document.querySelector(
    `[data-id="${id}"][data-caseid="${caseid}"]`,
  );
  const { path } = doc_item.dataset;
  const case_details_documents = doc_item.closest('.case_detail_panel');
  // Store cut data
  const cut_data = new Array(id, type, path, caseid);
  case_details_documents.dataset.copydata = cut_data;
  case_details_documents.dataset.cutdata = '';
});
// Paste file from context menu
live('click', 'context-menu-paste', async (e) => {
  const details = e.target.closest('.context-menu-details');
  const { caseid } = details.dataset;
  const caseDetails = document.querySelector(
    `.case_details_documents[data-caseid='${caseid}']`,
  );
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid='${caseid}'] .case_detail_panel`,
  );
  const { currentPath, layout } = caseDetails.dataset;
  const { cutdata } = caseDetailPanel.dataset;
  const [item_id, doc_type, selection_path, case_id] = cutdata.split(',');
  try {
    const res = await processDocuments({
      case_id,
      action: 'cut',
      item_id,
      target_path: currentPath,
      selection_path,
      doc_type,
    });
    const isList = layout === 'List' ? true : null;
    const html = await getDocuments(case_id, null, true, isList, null);
    const documentsContainer = document.querySelector(
      `#nav-${case_id}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
  } catch (err) {
    alertify.error(err.message);
  }
});
// Rename file from context menu
live('click', 'context-menu-rename', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type: docType, id: itemId, caseid } = details.dataset;
  const renameFileModal =
    bootstrap.Modal.getInstance('#renameFileModal') ||
    new bootstrap.Modal('#renameFileModal');
  const docItem = document.querySelector(`.doc_item[data-id="${itemId}"]`);
  let { filename, type } = docItem.dataset;
  const fileName = filename.replace(`.${type}`, '');
  const renameFileForm = document.querySelector('#renameFileModal form');
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid="${caseid}"]`,
  );
  const { currentpath: currentPath, layout } = caseDetailPanel.dataset;
  const isList = layout === 'List';
  setFormValues(renameFileForm, {
    caseId: caseid,
    isList,
    currentPath,
    itemId,
    docType,
    fileName,
    fileType: type,
  });
  renameFileModal.show();
});
// Rename file -- listen to form submit
live('click', 'doc_rename_file_submit', async (e) => {
  const form = document.querySelector('#renameFileModal form');
  const isValid = checkFormValidity(form);
  if (isValid != true) {
    form.classList.add('invalid');
    alertify.error('Please provide a file name.');
    return;
  }
  const { caseId, isList, currentPath, itemId, docType, fileType, fileName } =
    getFormValues(form);
  const new_name =
    docType == 'ccd' || docType == 'url' || docType == 'folder'
      ? fileName
      : `${fileName}.${fileType}`;

  try {
    const res = await processDocuments({
      action: 'rename',
      new_name,
      item_id: itemId,
      doc_type: docType,
      path: currentPath,
      case_id: caseId,
    });
    if (res.error) {
      throw new Error(res.message);
    }
    const html = await getDocuments(
      caseId,
      null,
      true,
      isList == 'true' || null,
      null,
    );
    const documentsContainer = document.querySelector(
      `#nav-${caseId}-documents .case_detail_panel`,
    );
    documentsContainer.innerHTML = html;
    const renameFileModal =
      bootstrap.Modal.getInstance('#renameFileModal') ||
      new bootstrap.Modal('#renameFileModal');
    renameFileModal.hide();
  } catch (err) {
    console.log(err.message);
    alertify.error(err.message);
  }
});

// Delete file from context menu
live('click', 'context-menu-delete', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const caseDetailPanel = document.querySelector(
    `.case_details_documents[data-caseid="${caseid}"]`,
  );
  const folder = document.querySelector(`.doc_item.folder[data-id="${id}"]`);
  const { path: folderPath } = folder?.dataset || {};
  const { currentpath: currentPath, layout } = caseDetailPanel.dataset;
  const isList = layout === 'List';
  alertify.confirm(
    'Confirm',
    type == 'folder'
      ? 'This folder and all of its contents will be permanently deleted from the server. Are you sure you want to delete it?'
      : `This item will be permanently deleted from the server.  Are you sure?`,
    async function () {
      try {
        await processDocuments({
          action: 'delete',
          item_id: id,
          doc_type: type,
          path: folder ? folderPath : currentPath,
          case_id: caseid,
        });
        const html = await getDocuments(
          caseid,
          null,
          true,
          isList == true || null,
          null,
        );
        const documentsContainer = document.querySelector(
          `#nav-${caseid}-documents .case_detail_panel`,
        );
        documentsContainer.innerHTML = html;
        alertify.success('File deleted');
      } catch (err) {
        alertify.error('Error deleting file.');
      }
    },
    function () {},
  );
});
// Properties file from context menu
live('click', 'context-menu-properties', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const docPropertiesModal =
    bootstrap.Modal.getInstance(`#documentPropertiesModal_${id}`) ||
    new bootstrap.Modal(`#documentPropertiesModal_${id}`, { keyboard: true });

  hideContextMenu();
  docPropertiesModal.show();
});
live('click', 'context-menu-delete', (e) => {
  const details = e.target.closest('.context-menu-details');
  const { type, id, caseid } = details.dataset;
  const doc_item = document.querySelector(`.doc_item[data-id="${id}"]`);
  const { path } = doc_item.dataset;
  console.log({ path });
  alertify.confirm(
    'Confirm',
    'This item will be permanently deleted from the server. Are you sure?',
    async () => {
      try {
        const res = await processDocuments({
          action: 'delete',
          doc_type: type,
          item_id: id,
          path,
          case_id: caseid
        });
        if (res.error) {
          throw new Error(res.message);
        }
        await reloadDocuments(caseid);
        alertify.success(res.message);
      } catch (err) {
        alertify.error(err.message);
      }
    },
    null,
  );
});

// delete file
// drag and drop on list
// save preferred docs view to cookies
// load docs based on cookies
// mobile design
