<?php include 'lib/php/utilities/names.php'; ?>
<?php include 'lib/php/html/gen_select.php'; ?>

<div class="modal fade" role="dialog" id="quickAddModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="quickAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="switch">
          <input id="isCaseNote" checked type="checkbox" name="isCaseNote">
          <div class="switch__selector"></div>
          <p class="switch__on">Case Note</p>
          <p class="switch__off">Event</p>
        </div>
      </div>
      <div class="modal-body">
        <form id="quickAddCaseNote">

          <div class="form__control">
            <input required type="datetime-local" name="date" placeholder=" ">
            <label for="date">Date</label>
          </div>
          <div class="form__control form__control--select">
            <select name="csenote_case_id" class="quick_add_case_slim_select" id="cn_case">
              <option value="NC">Non-Case Time</option>
              <?php
              $options = generate_active_cases_select($dbh, $_SESSION['login']);
              echo $options;
              ?>
            </select>
            <label for="date">Case</label>

          </div>
          <div class="case_note_time_selector">
            <?php
            $selector = generate_time_selector(null, null);
            echo  $selector ?>
          </div>
          <div class="form__control">
            <textarea required id="case_note_add_description" name="csenote_description" placeholder=" "></textarea>
            <label for="case_note_add_description">Description</label>
          </div>

        </form>
        <form id="quickAddEvent" class="hidden">

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
            <select name="csenote_case_id" class="quick_add_event_slim_select" id="cn_case">
              <option value="NC">Non-Case Time</option>
              <?php
              $options = generate_active_cases_select($dbh, $_SESSION['login']);
              echo $options;
              ?>
            </select>
            <label for="date">Case</label>

          </div>
          <div class="form__control form__control--select">
            <select multiple class="responsibles_slim_select" tabindex="2">
              <?php echo all_active_users_and_groups($dbh, false, $_SESSION['login']); ?>

            </select>
            <label for="responsibles">Who's Responsible?</label>
          </div>

          <div class="form__control">
            <textarea id="notes" required name="notes" placeholder=" "></textarea>
            <label for="notes">Description</label>
          </div>
        </form>
        <div id="quickAddContent"></div>
        <div class="modal-footer">
          <button id="quickAddCancel" class="quick_add_cancel">Cancel</button>
          <button type="button" class="primary-button quick_add_submit">Save</button>
        </div>
      </div>

    </div>
  </div>
</div>



<script>
  let caseNoteHTML;
  let eventHTML;
  const quickAddContent = document.querySelector('#quickAddContent');
  const quickAddCaseNote = document.querySelector('#quickAddCaseNote')
  const quickAddEvent = document.querySelector('#quickAddEvent')
  const initForm = () => {
    const case_note_input = document.querySelector('#isCaseNote');
    case_note_input.addEventListener('change', (e) => {
      const isCaseNote = e.target.checked;
      if (isCaseNote) {
        quickAddCaseNote.classList.remove("hidden");
        quickAddEvent.classList.add("hidden");
      } else {
        quickAddCaseNote.classList.add("hidden");
        quickAddEvent.classList.remove("hidden");
      }
    });
    const caseNoteSlimSelect = new SlimSelect({
      select: '.quick_add_case_slim_select',
    });
    const eventSlimSelect = new SlimSelect({
      select: '.quick_add_event_slim_select',
    });
    const responsiblesSlimSelect = new SlimSelect({
      select: '.responsibles_slim_select',
    });
  }


  document.addEventListener('DOMContentLoaded', initForm)
</script>