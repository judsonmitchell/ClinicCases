<div class="case_toolbar">

  <div class="form__control">
    <input class="case_notes_search" data-caseid="<?php echo $this_case_id ?>" id="caseNotesSearch-<?php echo $this_case_id ?>" data-label="#caseNotesSearchLabel-<?php echo $this_case_id ?>" name="caseNotesSearch-<?php echo $this_case_id ?>" type="text" placeholder="search" />
    <label id="caseNotesSearchLabel-<?php echo $this_case_id ?>" for="caseNotesSearch-<?php echo $this_case_id ?>">Search Case Notes</label>
  </div>
  <?php

  if ($_SESSION['permissions']['add_case_notes'] == '1') {
  ?>
    <div><button id='caseNotesAddButton-<?php echo $this_case_id ?>' class="button--primary" data-bs-toggle="modal" data-bs-target="#newCaseNoteModal">+ Add New Note</button>
      <button id='caseNotesTimerButton-<?php echo $this_case_id ?>' class="secondary-button case_notes_timer" data-casename="<?php echo case_id_to_casename($dbh, $this_case_id) ?>" data-caseid="<?php echo $this_case_id ?>">
        <img src='html/ico/timer.svg' alt='Timer Icon' /> <span>&nbsp;Timer</span>
      </button>
    <?php } ?>

    <button class="button--secondary print-button" data-print=".print_content.case_<?php echo $this_case_id ?>" data-filename="<?php echo case_id_to_casename($dbh, $this_case_id) ?> Notes">
      <img src="html/ico/printer.svg" alt="Print Icon" /> <span>&nbsp;Print</span>
    </div>
</div>
<div class="modal fade new_case_modal" id="newCaseNoteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCaseModalLabel">New Case Note</h5>
      </div>
      <div class="modal-body">
        <div id="caseNotesAddForm-<?php echo $this_case_id ?>">
          <form>
            <div class="">
              <div class="case_note_user_info"><img src='<?php echo $this_thumb ?>'> <?php echo $this_fname . ' ' . $this_lname ?></div>
              <div class="case_note_inputs">
                <label>Date:</label> <input required type="datetime-local" name="csenote_date" class="case_note_date" value='<?php echo  $this_date ?>'>
                <div class="case_note_time_selector">
                  <?php echo  $selector ?>
                </div>
                <div class="form__control">
                  <textarea required id="case_note_add_description" name="csenote_description" placeholder=" "></textarea>
                  <label for="case_note_add_description">Description</label>
                </div>
                <input type="hidden" name="csenote_user" value='<?php echo $this_user ?>'>
                <input type="hidden" name="csenote_case_id" value='<?php echo $this_case_id ?>'>
                <input type="hidden" name="query_type" value="add">
              </div>
            </div>
            <div class="case_note_add_toolbar">

              <button id="caseNotesCancel-<?php echo $this_case_id ?>" class="case_note_add_cancel">Cancel</button>
              <button data-caseid="<?php echo $this_case_id ?>" id="caseNotesAddSubmit-<?php echo $this_case_id ?>" class="button--primary case_note_add_save">
                Save</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="print_content case_detail_panel_casenotes case_<?php echo  $this_case_id ?>">