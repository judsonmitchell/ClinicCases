<?php

include 'lib/php/utilities/names.php';
$reader = $_SESSION['supervisors'];
include 'lib/php/html/gen_select.php';

?>

<div class="modal fade" id="newJournalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">
      <form enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="newJournalLabel">New Journal</h5>
        </div>
        <div class="modal-body">
          <div class="form__control form__control--select">
            <select class="reader_select" multiple name="reader_select" data-placeholder="Submit this journal to">

              <option value=""></option>
              <?php echo get_journal_readers($dbh, $reader); ?>

            </select>
            <label for="supervisors">Submit journal to</label>
          </div>

          <div class="p-4">

            <input type="checkbox" name="remember_choice">
            <label>Remember</label>
          </div>

          <div id="editor"></div>
        </div>
      </form>
      <div class="modal-footer">
        <button id="newJournalCancel" data-target="newJournalModal" class="new_journal_cancel">Cancel</button>
        <button type="button" data-target="newJournalModal" class="primary-button new_journal_submit">Submit</button>
      </div>
    </div>

  </div>
</div>