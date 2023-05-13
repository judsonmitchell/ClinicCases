<!-- Modal -->
<?php
include 'lib/php/utilities/names.php';
include 'lib/php/html/gen_select.php';

?>

<div class="modal fade" id="newPostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <form enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newPostLabel">New Post</h5>
        </div>
        <div class="modal-body">
          <div class="form__control">
            <input id="post_title" required type="text" name="post_title" placeholder=" ">
            <label for="post_title">Title</label>
          </div>
          <div class="form__control form__control--select">
            <select multiple class="new_post_slim_select" name="viewer_select">

              <option value=""></option>

              <?php echo all_active_users_and_groups($dbh, false, true); ?>

            </select>
            <label for="supervisors">Who See's This?</label>
          </div>
          <div id="editor"></div>

          <div class="form__control">
            <input id="attachments" type="file" name="attachments" multiple>
            <label for="attachment">Attach files</label>
          </div>
    </form>
    <div class="modal-footer">
      <button id="newPostCancel" data-target="newPostModal" class="new_post_cancel">Cancel</button>
      <button type="button" data-target="newPostModal" class="primary-button new_post_submit">Save</button>
    </div>
  </div>
</div>
</div>
</div>
