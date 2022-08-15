<div class="modal fade" role="dialog" id="editDocumentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDocumentLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDocumentLabel">Edit Document</h5>
          <div class="switch">
            <input type="checkbox" name="locked">
            <div class="switch__selector"></div>
            <p class="switch__on">Locked</p>
            <p class="switch__off">Unlocked</p>
          </div>
        </div>
        <div class="modal-body">
          <div class="form__control">
            <input required id="docTitle" type="text" name="doc_name" placeholder=" " value="New Document">
            <label for="docTitle">Document Name</label>
          </div>
          <textarea name="text" id="editDocEditor" required></textarea>
          <input type="text" hidden name="caseId">
          <input type="text" hidden name="isList">
          <input type="text" hidden name="currentPath">
          <input type="text" hidden name="ccd_id">
    </form>
    <div class="modal-footer">
      <button type="button" data-bs-toggle='modal' data-bs-target="#editDocumentModal" class="dismiss">Cancel</button>
      <button type="button" class="primary-button doc_edit_document_submit">Submit</button>
    </div>
  </div>

</div>
</div>
</div>