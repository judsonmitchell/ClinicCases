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
      <a href='#' class='case_note_print' data-casenotesid="<?php echo $case_notes['id'] ?>">Print</a>
    </div>
  </div>
</div>