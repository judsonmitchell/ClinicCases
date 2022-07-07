<div class="case_toolbar">

  <div class="form__control">
    <input id="caseNotesSearch-<?php echo $case_notes_data[0]['case_id'] ?>" data-label="#caseNotesSearchLabel-<?php echo $case_notes_data[0]['case_id'] ?>" name="caseNotesSearch-<?php echo $case_notes_data[0]['case_id'] ?>" type="text" placeholder="search" />
    <label id="caseNotesSearchLabel-<?php echo $case_notes_data[0]['case_id'] ?>" for="caseNotesSearch-<?php echo $case_notes_data[0]['case_id'] ?>">Search Case Notes</label>
  </div>
  <?php

  if ($_SESSION['permissions']['add_case_notes'] == '1') {
  ?>
    <div><button id='caseNotesAddButton-<?php echo $case_notes_data[0]['case_id'] ?>' class="button--primary">+ Add New Note</button>
      <button id='caseNotesTimerButton-<?php echo $this_case_id ?>' class="secondary-button">
        <img src='html/ico/timer.svg' alt='Timer Icon' /> <span>&nbsp;Timer</span>
      </button>
    <?php } ?>

    <button class="button--secondary">
      <img src="html/ico/printer.svg" alt="Print Icon" /> <span>&nbsp;Print</span>
    </div>
</div>
<div id="caseNotesAddForm-<?php echo $case_notes_data[0]['case_id'] ?>" class="hidden">
  <form>
    <div class="">
      <div class=""><img src='<?php echo $this_thumb ?>'> <?php echo $this_fname . ' ' . $this_lname ?></div>
      <div class="">
        <label>Date:</label> <input type="date" name="csenote_date" class="" value='<?php echo  $this_date ?>'> <?php echo  $selector ?>
        <input type="hidden" name="csenote_user" value='<?php echo $this_user ?> '>
        <input type="hidden" name="csenote_case_id" value='<?php echo $this_case_id ?> '>
        <input type="hidden" name="query_type" value="add">
        <button id="caseNotesAddSubmit-<?php echo $this_case_id ?>" class="button--primary">
          Add</button><button id="caseNotesCancel-<?php echo $this_case_id ?>" class="">Cancel</button>
      </div>
    </div>
    <textarea name="csenote_description"></textarea>
  </form>
</div>
<div class="print_content case_detail_panel_casenotes case_<?php echo  $case_notes_data[0]['case_id'] ?>">