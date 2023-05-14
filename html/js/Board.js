//  //Scripts for Board

import {
  boardFileUpload,
  loadBoard,
  processBoard,
  uploadFile,
} from '../../lib/javascripts/axios.js';
import { getModal } from '../../lib/javascripts/modal.js';
import { checkFormValidity, getFormValues } from './forms.js';
import { live } from './live.js';

const reloadBoardPosts = async (s) => {
  const boardPanel = document.querySelector('#board_panel');
  const boardContent = await loadBoard(s);
  editPostCKEditors = {};
  editPostSlimSelects = {};
  boardPanel.innerHTML = boardContent;
};

let newPostSlimSelect = new SlimSelect({
  select: '.new_post_slim_select',
});

let editPostSlimSelects = {};
let editPostCKEditors = {};
let ckEditor;
ClassicEditor.create(document.querySelector('#editor'))
  .then((editor) => {
    ckEditor = editor;
  })
  .catch((error) => {
    console.error(error);
  });

live('click', 'new_post_cancel', (e) => {
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

live('click', 'new_post_submit', async (_e, el) => {
  const id = el.dataset.target;
  const addPostModal = getModal(`#${id}`);
  const addPostForm = document.querySelector(`#${id} form`);
  let values = getFormValues(addPostForm);
  const isValid = checkFormValidity(addPostForm);
  const slimSelectRef = addPostForm.querySelector('.new_post_slim_select');
  const addPostSlimSelect = slimSelectRef.slim;
  const viewer_select = addPostSlimSelect.selected();

  const textIsBlank = !ckEditor.getData();
  console.log({ textIsBlank });
  const viewerSelectIsBlank = !viewer_select?.length;
  if (textIsBlank) {
    addPostForm.querySelector('.ck-editor').classList.add('invalid');
  } else {
    addPostForm.querySelector('.ck-editor').classList.remove('invalid');
  }
  if (viewerSelectIsBlank) {
    slimSelectRef.classList.add('invalid');
  } else {
    slimSelectRef.classList.remove('invalid');
  }
  if (isValid != true || textIsBlank || viewerSelectIsBlank) {
    addPostForm.classList.add('invalid');
    const errorFields = isValid != true ? isValid?.split(', ') : [];
    if (viewerSelectIsBlank) {
      errorFields.push('viewers');
    }
    if (textIsBlank) {
      errorFields.push('content');
    }
    alertify.error(
      `Please correct the following fields: ${errorFields.join(', ')} `,
    );

    return;
  } else {
    addPostForm.classList.remove('invalid');
  }

  const attachments = addPostForm.querySelector('[name="attachments"]').files;

  values.text = ckEditor.getData();
  delete values[''];
  try {
    const res = await processBoard({ action: 'new', ...values });
    console.log({ res });
    if (res.error) {
      alertify.error(res.message);
    } else {
      const res2 = await processBoard({
        action: 'edit',
        id: res.post_id,
        ...values,
        viewer_select,
      });
      if (res2.error) {
        alertify.error(res2.message);
      } else {
        await boardFileUpload(res.post_id, attachments);
        alertify.success('Post created');
      }
    }
    reloadBoardPosts();
    addPostModal.hide();
  } catch (err) {
    alertify.error(err.message);
  }
});

live('click', 'edit_post_submit', async (_e, el) => {
  const modalId = el.dataset.target;
  const id = el.dataset.id;
  const editPostModal = getModal(`#${modalId}`);
  const viewPostModal = getModal(`#viewPostModal-${id}`);
  const editPostForm = document.querySelector(`#${modalId} form`);
  let values = getFormValues(editPostForm);
  const isValid = checkFormValidity(editPostForm);
  const viewer_select = editPostSlimSelects[id].selected();
  const slimSelectRef = document.querySelector(`.edit_post_slim_select-${id}`);
  const textIsBlank = !editPostCKEditors[id].getData();
  const viewerSelectIsBlank = !viewer_select?.length;
  if (textIsBlank) {
    editPostForm.querySelector('.ck-editor').classList.add('invalid');
  } else {
    editPostForm.querySelector('.ck-editor').classList.remove('invalid');
  }
  if (viewerSelectIsBlank) {
    slimSelectRef.classList.add('invalid');
  } else {
    slimSelectRef.classList.remove('invalid');
  }
  if (isValid != true || textIsBlank || viewerSelectIsBlank) {
    editPostForm.classList.add('invalid');
    const errorFields = isValid != true ? isValid?.split(', ') : [];
    if (viewerSelectIsBlank) {
      errorFields.push('viewers');
    }
    if (textIsBlank) {
      errorFields.push('content');
    }
    alertify.error(
      `Please correct the following fields: ${errorFields.join(', ')} `,
    );

    return;
  } else {
    editPostForm.classList.remove('invalid');
  }

  const attachments = editPostForm.querySelector('[name="attachments"]').files;
  values.text = editPostCKEditors[id].getData();
  delete values[''];
  try {
    const res = await processBoard({
      action: 'edit',
      id,
      ...values,
      viewer_select,
    });
    let res2 = {};
    if (attachments?.length) {
      res2 = await boardFileUpload(id, attachments);
    }

    if (res.error) {
      alertify.error(res.message);
    } else if (res2.error) {
      alertify.error(res2.message);
    } else {
      alertify.success(res.message);
    }
    reloadBoardPosts();
    editPostModal.hide();
    viewPostModal.hide();
  } catch (err) {
    alertify.error(err.message);
  }
});

// search case notes
live('change', 'board_posts_search', async (event) => {
  const value = event.target.value;
  console.log({ value });
  reloadBoardPosts(value);
});
const disallowedClasses = [
  'board_item_edit',
  'board_item_delete',
  'attachment',
];
live('click', 'search_clear', async () => {
  document.querySelector('.board_posts_search').value = '';
  reloadBoardPosts();
});

live('click', 'board_item_card', (e, el) => {
  if (disallowedClasses.includes(e.target.classList)) {
    return;
  }
  const id = el.dataset.id;
  const modal = getModal(`#viewPostModal-${id}`);
  modal.show();
});
live('click', 'board_item_edit', async (e, el) => {
  e.preventDefault();
  e.stopPropagation();
  const id = el.dataset.id;
  const slimSelectClass = `.edit_post_slim_select-${id}`;
  const editorId = `#editor-${id}`;
  const modal = getModal(`#editPostModal-${id}`);

  editPostSlimSelects[id] =
    editPostSlimSelects[id] ||
    new SlimSelect({
      select: slimSelectClass,
    });
  const slimSelectEl = document.querySelector(`.edit_post_slim_select-${id}`);
  const viewers = slimSelectEl.dataset.viewers.split(',');
  editPostSlimSelects[id].set(viewers);
  const editorEl = document.querySelector(editorId);
  if (!editPostCKEditors[id]) {
    ClassicEditor.create(document.querySelector(editorId))
      .then((editor) => {
        editPostCKEditors[id] = editor;
        editPostCKEditors[id].setData(editorEl.dataset.body);
      })
      .catch((error) => {
        console.error(error);
      });
  }
  modal.show();
});

live('click', 'edit_post_cancel', (e) => {
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
live('click', 'board_item_delete', async function (event) {
  event.preventDefault();
  event.stopPropagation();
  const id = event.target.dataset.id;
  alertify.confirm(
    'Confirm',
    'Are you sure you want to delete this post?',
    async () => {
      try {
        const res = await processBoard({ action: 'delete', item_id: id });
        console.log({ res });
        if (res.error) {
          alertify.error(res.message);
        } else {
          alertify.success(res.message);
        }
        await reloadBoardPosts();
      } catch (err) {
        alertify.error(err.message);
      }
    },
    null,
  );
});

live('click', 'attachment', async (e, el) => {
  e.preventDefault();
  e.stopPropagation();
  const board_item = el.closest('.board_item');
  try {
    const id = board_item.dataset.id;
    const res = await processBoard({ action: 'download', item_id: id });
    console.log({ res });
  } catch (err) {
    alertify.error(err.message);
  }
});
document.addEventListener('DOMContentLoaded', async () => {
  reloadBoardPosts();
});
