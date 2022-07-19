<?php
$convertedTime = explode(':', str_replace(';', '', gmdate("H:i", $case_notes['time'])));
$hours = ltrim($convertedTime[0], 0);
$min = ltrim($convertedTime[1], 0);

$editSelector = generate_time_selector($hours, $min);
?>

<div class="case_note case_note--closed" id="case_note_<?php echo $case_notes['id'] ?>">
  <div>
    <img class='thumbnail-mask' src='<?php echo thumbify($case_notes['picture_url']) ?>'>
  </div>
  <div class="case_note_details">

    <div class="case_note_top">
      <div class="case_note_name">
        <h4><?php echo username_to_fullname($dbh, $case_notes['username']) ?></h4>
        <p><?php echo $time[0] . $time[1]  ?></p>
      </div>
      <p class="case_note_date"><?php echo extract_date($case_notes['date']) ?></p>
    </div>
    <p class="case_note_description"> <?php echo nl2br(htmlentities($case_notes['description'])) ?></p>
    <div class="case_note_actions">
      <?php
      if ($case_notes['username'] == $_SESSION['login']) {
      ?>
        <a href='#' class='case_note_edit' data-casenotesid="<?php echo $case_notes['id'] ?>">Edit</a>&nbsp;&nbsp;<a href='#' class='case_note_delete' data-casenotesid="<?php echo $case_notes['id'] ?>">Delete</a>
      <?php
      } ?>
      &nbsp;&nbsp;
      <a href='#' data-print="#case_note_<?php echo $case_notes['id'] ?>" data-filename="<?php echo case_id_to_casename($dbh, $this_case_id) ?> - <?php echo username_to_fullname($dbh, $case_notes['username']) ?> - Note <?php echo date('Y-m-d', strtotime($case_notes['date'])) ?>" class='case_note_print print-button' data-casenotesid="<?php echo $case_notes['id'] ?>">Print</a>
    </div>
    <form class="case_note_form hidden" id="case_note_edit<?php echo $case_notes['id'] ?>">
      <div class="case_note_form_dates">
        <label>Date:</label> <input type="datetime-local" name="csenote_date" class="" value='<?php echo date('Y-m-d H:i:s', strtotime($case_notes['date'])) ?>'> <?php echo  $editSelector ?>
        <input type="hidden" name="csenote_user" value='<?php echo $this_user ?>'>
        <input type="hidden" name="csenote_case_id" value='<?php echo $this_case_id ?>'>
        <input type="hidden" name="csenote_casenote_id" value='<?php echo $case_notes['id'] ?>'>

        <input type="hidden" name="query_type" value="modify">
      </div>
      <textarea name="csenote_description"><?php echo nl2br(htmlentities($case_notes['description'])) ?></textarea>
      <div class="case_note_form_toolbar">
        <button class="case_note_form_cancel" data-caseid="<?php echo $this_case_id ?>">Cancel</button>
        <button class="button--primary case_note_form_save">Save</button>
      </div>
    </form>
  </div>


</div>