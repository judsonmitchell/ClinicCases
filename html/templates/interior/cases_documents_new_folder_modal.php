<div class="modal fade" role="dialog" id="newFolderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newFolderLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newFolderLabel">New Folder</h5>

      </div>
      <div class="modal-body">
        <form>

          <div class="form__control">
            <input id="folderName" type="text" name="folderName" required placeholder=" ">
            <label for="folderName">Folder Name</label>
          </div>
          <input type="text" hidden name="caseId">
          <input type="text" hidden name="isList">
          <input type="text" hidden name="currentPath">
        </form>
        <div class="modal-footer">
          <button type="button" data-bs-toggle='modal' data-bs-target="#newFolderModal" class="dismiss">Cancel</button>
          <button type="button" class="primary-button doc_new_folder_submit">Submit</button>
        </div>
      </div>

    </div>
  </div>
</div>