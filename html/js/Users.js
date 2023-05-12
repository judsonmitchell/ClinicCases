// //Scripts for users page

import {
  fetchUsers,
  processUsers,
  resetPassword,
} from '../../lib/javascripts/axios.js';
import { getClosest, live } from './live.js';
import { getModal } from '../../lib/javascripts/modal.js';
import { getFormValues, checkFormValidity } from './forms.js';
let table;
let newUserGroupSlimSelect;
const removeUserIdFromParams = () => {
  const searchParams = new URLSearchParams(window.location.search);
  searchParams.delete('user_id');
  const newUrl =
    window.location.origin +
    window.location.pathname +
    '?' +
    searchParams.toString();
  window.history.replaceState(null, null, newUrl);
};
const checkForOpenUser = () => {
  const urlParams = new URLSearchParams(window.location.search);
  const user_id = urlParams.get('user_id');
  const page = urlParams.get('page');
  if (user_id) {
    const viewUserModal = getModal('#viewUserModal');
    viewUserModal.show();
  }
};
const initNewUserForm = () => {
  newUserGroupSlimSelect = new SlimSelect({
    select: '.new_user_group_slim_select',
  });
};

const reloadUsersTable = () => {
  const table_users = document.querySelector('#table_users');
  table_users.innerHTML = '';
  initUsersTable();
};
const createCanAddUserButton = () => {
  const button = document.createElement('button');
  button.setAttribute('data-bs-toggle', 'modal');
  button.setAttribute('data-bs-target', '#newUserModal');
  button.classList.add('primary-button');
  button.setAttribute('type', 'button');
  button.setAttribute('id', 'addButton');
  button.innerText = '+ Add User';

  return button;
};
const initUsersTable = async () => {
  const users = await fetchUsers();
  const canAddButton = createCanAddUserButton();
  const aoColumns = [
    { name: 'Id', hidden: true, type: 'text', fieldName: 'id' },
    {
      name: 'Face',
      hidden: false,
      type: 'img',
      fieldName: 'picture_url',
      noLabel: true,
    },
    {
      name: 'First Name',
      hidden: false,
      type: 'text',
      fieldName: 'first_name',
    },
    { name: 'Last Name', hidden: false, type: 'text', fieldName: 'last_name' },
    { name: 'Email', hidden: true, type: 'text', fieldName: 'email' },
    {
      name: 'Mobile Phone',
      hidden: true,
      type: 'text',
      fieldName: 'mobile_phone',
    },
    {
      name: 'Office Phone',
      hidden: true,
      type: 'text',
      fieldName: 'office_phone',
    },
    { name: 'Home Phone', hidden: true, type: 'text', fieldName: 'home_phone' },
    { name: 'Group', hidden: false, type: 'text', fieldName: 'grp' },
    { name: 'Username', hidden: false, type: 'text', fieldName: 'username' },
    {
      name: 'Supervisor',
      hidden: false,
      type: 'text',
      fieldName: 'supervisors',
    },
    { name: 'Status', hidden: false, type: 'text', fieldName: 'status' },
    { name: 'New', hidden: false, type: 'text', fieldName: 'new' },

    {
      name: 'Date Created',
      hidden: false,
      type: 'date',
      fieldName: 'date_created',
    },
  ];

  // Custom table plugin initiation
  table = new Table({
    columns: aoColumns,
    data: users.aaData,
    containerId: '#table_users',
    facets: [
      {
        label: 'Active Users',
        value: 'active',
        field: 'status',
        filter: (item) => {
          return item.status == 'active';
        },
        default: true,
      },
      {
        label: 'Inactive Users',
        value: 'inactive',
        field: 'status',
        filter: (item) => {
          return item.statu != 'active';
        },
      },
      {
        label: 'All Users',
        value: 'all',
        field: 'status',
        filter: () => {
          return true;
        },
      },
    ],
    tableName: 'Users',
    tableNameSingular: 'User',
    canAddButton,
  });
  setUpRowClick();
};

const setUpRowClick = () => {
  table.onRowClick((e) => {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set(
      'user_id',
      getClosest(e.target, 'table__cell')?.dataset?.userid,
    );
    window.location.search = urlParams.toString();
  });
};
const setupImageDropzone = () => {
  const dropzones = document.querySelectorAll('.picture_dropzone');
  dropzones.forEach((dropzone) => {
    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
      dropzone.classList.remove('dragover');
    });
    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('dragover');
      const files = e.dataTransfer.files;

      handleFiles(files);
    });

    const fileInput =
      dropzone.querySelector('[name="picture_url"]') ||
      document.querySelector('[name="picture_url"]');
    const fileDelete = dropzone.querySelector('.file_delete');
    fileDelete.addEventListener('click', () => {
      fileInput.value = '';
      const event = new Event('change');
      fileInput.dispatchEvent(event);
    });
    fileInput.addEventListener('change', () => {
      const files = fileInput.files;
      handleFilesFromBrowse(files);
    });
    function handleFilesFromBrowse(files) {
      console.log({ fileInput });

      fileInput.files = files;
      const picturePreview = dropzone.querySelector('.file_preview');

      const file = files[0];
      if (file) {
        const file_name = dropzone.querySelector('.file_info .file_name');
        file_name.innerText = file.name;
        const reader = new FileReader();

        reader.addEventListener('load', function () {
          picturePreview.setAttribute('src', this.result);
        });

        reader.readAsDataURL(file);
      } else {
        picturePreview.setAttribute('src', '#');
      }
      if (files?.length) {
        fileInput.classList.add('has_file');
      } else {
        fileInput.classList.remove('has_file');
      }
    }
    function handleFiles(files) {
      fileInput.files = files;
      const event = new Event('change');
      fileInput.dispatchEvent(event);
      const picturePreview = dropzone.querySelector('.file_preview');
      const file = files[0];
      if (file) {
        const file_name = dropzone.querySelector('.file_info .file_name');
        file_name.innerText = file.name;
        const reader = new FileReader();

        reader.addEventListener('load', function () {
          picturePreview.setAttribute('src', this.result);
        });

        reader.readAsDataURL(file);
      } else {
        picturePreview.setAttribute('src', '#');
      }
      if (files?.length) {
        fileInput.classList.add('has_file');
      } else {
        fileInput.classList.remove('has_file');
      }
    }
  });
};

live('click', 'new_user_submit', async (e) => {
  const id = e.target.dataset.target;
  const addUserModal = getModal(`#${id}`);
  const addUserForm = document.querySelector(`#${id} form`);
  let values = getFormValues(addUserForm);
  const isValid = checkFormValidity(addUserForm);
  const slimSelectRef = addUserForm.querySelector(
    '.new_user_group_slim_select',
  );
  const addUserSlimSelect = slimSelectRef.slim;
  const group = addUserSlimSelect.selected();

  if (!group.length) {
    slimSelectRef.classList.add('invalid');
  } else {
    slimSelectRef.classList.remove('invalid');
  }
  if (isValid != true || !group.length) {
    addUserForm.classList.add('invalid');
    alertify.error(
      `Please correct the following fields: ${isValid != true && isValid} ${
        !group.length ? 'group' : ''
      }`,
    );

    return;
  }

  addUserForm.classList.remove('invalid');
  slimSelectRef.classList.remove('invalid');

  const picture_file = addUserForm.querySelector('[name="picture_url"]')
    .files[0];

  delete values[''];
  delete values['picture_url'];
  try {
    const res = await processUsers(
      { action: 'create', ...values },
      picture_file,
    );
    if (res.error) {
      alertify.error(res.message);
    } else {
      alertify.success(res.message);
    }
    reloadUsersTable();
    addUserModal.hide();
  } catch (err) {
    alertify.error(err.message);
  }
});

live('click', 'new_user_cancel', (e) => {
  e.preventDefault();
  alertify.confirm(
    'Confirm',
    'Are you sure you want to cancel? You will lose your data.',
    () => {
      const id = e.target.dataset.target;
      const modal = getModal(`#${id}`);
      modal.hide();
    },
    null,
  );
});

live('click', 'modal_close', () => {
  removeUserIdFromParams();
});

// submit form handler
// function handleFiles(files) {
//   const file = files[0];
//   const formData = new FormData();
//   formData.append("file", file);
//   axios.post("/upload", formData).then((response) => {
//     console.log(response.data);
//   });
// }

const loadFile = (url) => {
  if (!url) return;
  // Create a File object with some sample data
  var file = new File(['User picture'], url, { type: 'img/png' });
  // Create a new DataTransfer object
  var dataTransfer = new DataTransfer();

  // Add the File object to the DataTransfer object
  dataTransfer.items.add(file);
  // Get a reference to the file input element
  var fileInput = document.querySelector('#editUser #picture_url');

  // Set its value to the File object
  fileInput.files = dataTransfer.files;
  fileInput.classList.add('has_file');
};
live('click', 'reset_password', async (e, el) => {
  alertify.confirm(
    'Confirm',
    "This will reset the user's password. Are you sure you want to do that?",
    async () => {
      try {
        const id = el.dataset.id;
        const res = await resetPassword(id);
        if (res.error) {
          throw new Error(res.message);
        } else {
          alertify
            .confirm('Success!', res.html, null, null)
            .set('labels', { ok: 'OK', cancel: '' });
          const copyButton = document.getElementById('copyButton');
          copyButton.addEventListener('click', () => {
            navigator.clipboard
              .writeText(document.getElementById('copyText').innerText)
              .then(() => {
                alertify.success('Password copied to clipboard!');
              })
              .catch((err) => {
                alertify.error(`Error: ${err.message}`);
              });
          });
        }
      } catch (e) {
        alertify.error(e.message);
      }
    },
    null,
  );
});
live('click', 'user_detail_delete', async (e, el) => {
  alertify.confirm(
    'Confirm',
    `<p>It is usually best to deactivate, rather than delete, a user account.</p>
     <p>To deactivate, click the edit button below and then change the user status.</p>
    <p>You should only delete if this user account was created by error or as a result of spam.</p> 
    <p>Are you sure you want to delete?</p>`,
    async () => {
      try {
        const id = el.dataset.id;
        const res = await processUsers({ users: id, action: 'delete' });
        if (res.error) {
          throw new Error(res.message);
        } else {
          alertify.success(res.message);
          const modal = getModal(el.dataset.target);
          modal.hide();
          removeUserIdFromParams();
          reloadUsersTable();
        }
      } catch (e) {
        alertify.error(e.message);
      }
    },
    null,
  );
});
live('click', 'user_detail_edit', async (e, el) => {
  const viewUser = document.querySelector('#viewUser');
  const editUser = document.querySelector('#editUser');
  viewUser.classList.add('hidden');
  editUser.classList.remove('hidden');
  const modal = document.querySelector('#viewUserModal');
  modal.scrollTop = 0;
  const url = document.querySelector('#editUser .file_preview').src;
  loadFile(url);
});

document.addEventListener('DOMContentLoaded', () => {
  initUsersTable();
  initNewUserForm();
  setupImageDropzone();
  checkForOpenUser();
});
