<div class="modal fade" role="dialog" id="newMessageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newMessageLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newMessageLabel">New Message</h5>
        </div>
        <div class="modal-body">
          <div class="form__control form__control--select">
            <select multiple class="new_message_tos_slim_select" tabindex="2">
            </select>
            <label for="tos">To: Choose Recipients</label>
          </div>
          <div class="form__control form__control--select">
            <select multiple class="new_message_ccs_slim_select" tabindex="2">
            </select>
            <label for="ccs">CC: Choose Recipients</label>
          </div>
          <div class="form__control">
            <input id="subject" required type="text" name="subject" placeholder=" ">
            <label for="subject">Subject</label>
          </div>
          <div class="form__control form__control--select">
            <select multiple class="new_message_file_in_slim_select" tabindex="2">
            </select>
            <label for="file_in">File in</label>
          </div>
          <div class="form__control">
            <textarea id="message" required name="message" placeholder=" "></textarea>
            <label for="message">Message</label>
          </div>
          <input type="text" hidden name="case_id">
    </form>
    <div class="modal-footer">
      <button id="newCaseMessageCancel" class="case_message_add_cancel">Cancel</button>
      <button type="button" class="primary-button new_message_submit">Submit</button>
    </div>
  </div>

</div>
</div>
</div>