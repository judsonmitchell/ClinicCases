<div class="modal fade" role="dialog" id="renameFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="renameFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameFileLabel">Rename File</h5>

      </div>
      <div class="modal-body">
        <form>

          <div class="form__control">
            <input id="fileName" type="text" name="fileName" required placeholder=" ">
            <label for="fileName">File Name</label>
          </div>
          <input type="text" hidden name="caseId">
          <input type="text" hidden name="isList">
          <input type="text" hidden name="currentPath">
          <input type="text" hidden name="itemId">
          <input type="text" hidden name="docType">
          <input type="text" hidden name="fileType">
        </form>
        <div class="modal-footer">
          <button type="button" data-bs-toggle='modal' data-bs-target="#renameFileModal" class="dismiss">Cancel</button>
          <button type="button" class="primary-button doc_rename_file_submit">Submit</button>
        </div>
      </div>

    </div>
  </div>
</div>