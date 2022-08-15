<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

<div class="modal fade" role="dialog" id="newDocumentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newDocumentLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newDocumentLabel">New Folder</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form__control">
            <input required id="docTitle" type="text" name="doc_name" placeholder=" " value="New Document">
            <label for="docTitle">Document Name</label>
          </div>
          <textarea name="text" id="editor" required></textarea>
          <input type="text" hidden name="caseId">
          <input type="text" hidden name="isList">
          <input type="text" hidden name="currentPath">
        </form>
        <div class="modal-footer">
          <button type="button" data-bs-toggle='modal' data-bs-target="#newDocumentModal" class="dismiss">Cancel</button>
          <button type="button" class="primary-button doc_new_document_submit">Submit</button>
        </div>
      </div>

    </div>
  </div>
</div>
