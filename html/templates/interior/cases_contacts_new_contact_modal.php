<?php
require("lib/php/html/gen_select.php");
?>
<div class="modal fade" role="dialog" id="newContactModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newContactLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newContactLabel">New Contact</h5>
        </div>
        <div class="modal-body">
          <div class="form__control">
            <input id="first_name" required type="text" name="first_name" placeholder=" ">
            <label for="first_name">First Name</label>
          </div>
          <div class="form__control">
            <input id="where" required type="text" name="where" placeholder=" ">
            <label for="where">Last Name</label>
          </div>
          <div class="form__control">
            <input id="organization" required type="text" name="organization" placeholder=" ">
            <label for="where">Organization</label>
          </div>

          <div class="form__control form__control--select">
            <select id="contact_type" tabindex="2">
            </select>
            <label for="contact_type">Contact Type</label>
          </div>
          <div class="form__control">
            <textarea id="address" required name="address" placeholder=" "></textarea>
            <label for="address">Address</label>
          </div>
          <div class="form__control">
            <input id="city" required type="text" name="city" placeholder=" ">
            <label for="where">City</label>
          </div>
          <div class="form-control__two">
            <div class="form__control form__control--select">
              <?php echo genStateSelect('', 'state')  ?>
              <label for="state">State</label>
            </div>
            <div class="form__control">
              <input id="zip" required type="text" name="zip" placeholder=" ">
              <label for="zip">Zip</label>
            </div>
          </div>
          <div id="phone-container" class="form-control__multiple">
            <div class="form__add">
              <i class="add-item-button" data-container="#phone-container" data-field="phone"><img src="html/ico/add-item.svg" alt="Add item"></i>
            </div>

            <div class="form-control__dual">
              <div class="form__control form__control--select">
                <select data-dual="true" name="phone-select1" id="phone-select1">
                  <option value="" disabled selected>Select one...</option>
                  <option value="home">Home</option>
                  <option value="work">Work</option>
                  <option value="mobile">Mobile</option>
                  <option value="fax">Fax</option>
                  <option value="other">Other</option>
                </select>
                <label id="phone-Select1Label" for="phone-select1">Phone</label>
              </div>
              <div class="form__control">
                <input placeholder=" " id="phone1" type="text" name="phone1" data-dual="true">
                <label id="phoneLabel" for="phone1"> </label>
              </div>
            </div>




          </div>
          <div class="form__control">
            <input id="url" required type="text" name="url" placeholder=" ">
            <label for="url">Website</label>
          </div>



          <div class="form__control">
            <textarea id="notes" required name="notes" placeholder=" "></textarea>
            <label for="notes">Notes</label>
          </div>
    </form>
    <div class="modal-footer">
      <button id="newCaseContactCancel" class="case_contact_add_cancel">Cancel</button>
      <button type="button" class="primary-button new_contact_submit">Submit</button>
    </div>
  </div>

</div>
</div>
</div>