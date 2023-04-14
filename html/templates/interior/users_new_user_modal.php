<!-- Modal -->
<?php
require('lib/php/utilities/names.php');
$supervisor_name_data  = supervisor_names_array($dbh);
require('lib/php/html/gen_select.php');

?>

<div class="modal fade" id="newUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newUserLabel">New User</h5>
        </div>
        <div class="modal-body">
          <div>
            <div id="dropzone" class="picture_dropzone">
              <p>Drag and drop your image here</p>
              <p>or</p>
              <label for="picture">click here to browse
              </label>
              <input id="picture" type="file" name="picture">
              <div class="file_info">

                <img src="#" alt="Preview of user picture" class="file_preview">
                <button type="button" class="file_delete"><img src="html/ico/times.png" alt="" /></button>
                <p class="file_name"></p>
              </div>
            </div>
          </div>
          <div class="form__control">
            <input id="first_name" required type="text" name="first_name" placeholder=" ">
            <label for="first_name">First Name</label>
          </div>
          <div class="form__control">
            <input id="last_name" required type="text" name="last_name" placeholder=" ">
            <label for="last_name">Last Name</label>
          </div>
          <div class="form__control">
            <input id="email" required type="text" name="email" placeholder=" ">
            <label for="email">Email</label>
          </div>
          <div class="form__control">
            <input id="mobile_phone" required type="text" name="mobile_phone" placeholder=" ">
            <label for="mobile_phone">Mobile Phone</label>
          </div>
          <div class="form__control">
            <input id="office_phone" required type="text" name="office_phone" placeholder=" ">
            <label for="office_phone">Office Phone</label>
          </div>
          <div class="form__control">
            <input id="home_phone" required type="text" name="home_phone" placeholder=" ">
            <label for="home_phone">Home Phone</label>
          </div>
          <div class="form__control form__control--select">
            <select multiple class="new_user_group_slim_select" tabindex="2">
              <?php
              echo group_select($dbh, $_SESSION['group']);
              ?>
            </select>
            <label for="responsibles">Group</label>
          </div>
          <div class="form__control form__control--select">
            <select class="new_user_supervisor_slim_select" tabindex="2">
              <?php
              echo supervisors_select($_SESSION['group'], $supervisor_name_data);
              ?>
            </select>
            <label for="responsibles">Supervisor</label>
          </div>
          <div class="form__control form__control--select">
            <select class="status" tabindex="2">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

    </form>
    <div class="modal-footer">
      <button id="newUserCancel" class="new_user_cancel">Cancel</button>
      <button type="button" class="primary-button new_user_submit">Add</button>
    </div>
  </div>
</div>