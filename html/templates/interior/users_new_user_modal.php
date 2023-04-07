<!-- Modal -->
<div class="modal fade" id="newUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newUserLabel">New User</h5>
        </div>
        <div class="modal-body">
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
            </select>
            <label for="responsibles">Group</label>
          </div>
          <div class="form__control form__control--select">
            <select class="new_user_supervisor_slim_select" tabindex="2">
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
      <button type="button" class="primary-button new_user_submit">Submit</button>
    </div>
  </div>
</div>


