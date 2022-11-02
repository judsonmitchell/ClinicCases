<div class="modal fade" role="dialog" id="newEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newEventLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newEventLabel">New Event</h5>
        </div>
        <div class="modal-body">
          <div class="form__control">
            <input id="task" required type="text" name="task" placeholder=" ">
            <label for="task">What is the name of this event?</label>
          </div>
          <div class="form__control">
            <input id="where" required type="text" name="where" placeholder=" ">
            <label for="where">Where is this event?</label>
          </div>
          <div class="form-control__two">
            <div class="form__control">
              <input required type="datetime-local" name="start" placeholder=" ">
              <label for="start">When does this event start?</label>
            </div>
            <div class="form__control">
              <input required type="datetime-local" name="end" placeholder=" ">
              <label for="end">When does this event end?</label>
            </div>
          </div>

          <div class="form__control--checkbox">
            <input name="all_day" type="checkbox">
            <label for="all_day">All Day?</label>
          </div>

          <div class="form__control form__control--select">
            <select multiple class="new_event_slim_select" tabindex="2">
            </select>
            <label for="responsibles">Who's Responsible?</label>
          </div>

          <div class="form__control">
            <textarea id="notes" required name="notes" placeholder=" "></textarea>
            <label for="notes">Description</label>
          </div>
          <input type="text" hidden name="case_id">
    </form>
    <div class="modal-footer">
    <button id="newCaseEventCancel" class="case_event_add_cancel">Cancel</button>
      <button type="button" class="primary-button new_event_submit">Submit</button>
    </div>
  </div>

</div>
</div>
</div>