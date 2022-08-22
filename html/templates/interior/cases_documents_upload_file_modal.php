<div class="modal fade open" role="dialog" id="uploadFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="uploadFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadFileLabel">Upload to <span class="current-path">Home</span> Folder</h5>
        <div class="switch">
            <input type="checkbox" checked class="file_or_url_switch" name="file_or_url">
            <div class="switch__selector"></div>
            <p class="switch__off">URL</p>
            <p class="switch__on">FILE</p>
          </div>
      </div>
      <form>
        <div class="modal-body">
          <div id="uploadFileForm">

            <div id="dropArea" class="file-upload">
              <img src="html/ico/folder.png" alt="">
              <p>Drag and drop your files here</p>
              <p>or</p>
              <label class="button" for="fileElem">click here to browse</label>
              <input type="file" id="fileElem" class="file-upload-input" multiple accept="image/*">
            </div>
          </div>
          <div id="uploadURLForm">
            <div class="form__control">
              <input id="uploadURL" name="url" type="text" placeholder=" " required>
              <label for="uploadURL">Use a URL from the web</label>
            </div>
            <div class="form__control">
              <input id="uploadName" name="url_name" type="text" placeholder=" " required>
              <label for="uploadName">File name</label>
            </div>
          </div>

          <input type="text" hidden name="caseId">
          <input type="text" hidden name="isList">
          <input type="text" hidden name="currentPath">
          <input type="text" hidden name="ccd_id">
      </form>
      <div id="uploadFileFooter" class="modal-footer">
        <button type="button" data-bs-toggle='modal' data-bs-target="#uploadFileModal" class="dismiss">Cancel</button>
      </div>
      <div id="uploadURLFooter" class="modal-footer">
        <button type="button" data-bs-toggle='modal' data-bs-target="#uploadFileModal" class="dismiss">Cancel</button>
        <button type="button" class="primary-button doc_upload_file_submit">Submit</button>
      </div>
    </div>

  </div>
</div>
</div>