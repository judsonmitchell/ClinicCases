<!-- Modal -->
<div class="modal fade" id="newCaseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCaseLabel">New Case</h5>
      </div>
      <div class="modal-body">
        <div class="form__control">
          <label class="float" id="clinicIdLabel" for="clinicId">Case Number</label>
          <input id="clinicId" data-label="#clinicIdLabel" name="clinicId" type="text" />
        </div>
        <div class="form__control">
          <label id="firstNameLabel" for="firstName">First Name</label>
          <input id="firstName" data-label="#firstNameLabel" name="firstName" type="text" />
        </div>
        <div class="form__control">
          <label id="lastNameLabel" for="lastName">Last Name</label>
          <input id="lastName" data-label="#lastNameLabel" name="lastName" type="text" />
        </div>
        <div class="form__control">
          <label id="organizationLabel" for="organization">Organization</label>
          <input id="organization" data-label="#organizationLabel" name="organization" type="text" />
        </div>
        <div class="form__control">
          <label class="float" id="dateOpenLabel" for="dateOpen">Date Open</label>
          <input id="dateOpen" data-label="#dateOpenLabel" name="dateOpen" type="date" />
        </div>
        <!-- Divide here -->
        <div class="two-columns">
          <div class="form__control">
            <label class="float" id="caseTypeLabel" for="caseType">Case Type</label>
            <select id="caseType"></select>
          </div>
          <div class="form__control">
            <label class="float" id="clinicTypeLabel" for="clinicType">Clinic Type</label>
            <select id="clinicType"></select>
          </div>
        </div>
        <div class="form__control">
          <label id="address1Label" for="address1">Address 1</label>
          <input id="address1" data-label="#address1Label" name="address1" type="text" />
        </div>
        <div class="form__control">
          <label id="address2Label" for="address2">Address 2</label>
          <input id="address2" data-label="#address2Label" name="address2" type="text" />
        </div>
        <div class="two-columns">
          <div class="form__control">
            <label id="cityLabel" for="city">City</label>
            <input id="city" data-label="#cityLabel" name="city" type="text" />
          </div>
          <div class="form__control">
            <!-- <label class="float" id="stateLabel" for="state">State</label> -->
            <select id="state">
              <option value="" data-code="" disabled selected>State</option>
              <option value="Alabama" data-code="AL">Alabama</option>
              <option value="Alaska" data-code="AK">Alaska</option>
              <option value="Arizona" data-code="AZ">Arizona</option>
              <option value="Arkansas" data-code="AR">Arkansas</option>
              <option value="California" data-code="CA">California</option>
              <option value="Colorado" data-code="CO">Colorado</option>
              <option value="Connecticut" data-code="CT">Connecticut</option>
              <option value="Delaware" data-code="DE">Delaware</option>
              <option value="District Of Columbia" data-code="DC">District Of Columbia</option>
              <option value="Florida" data-code="FL">Florida</option>
              <option value="Georgia" data-code="GA">Georgia</option>
              <option value="Hawaii" data-code="HI">Hawaii</option>
              <option value="Idaho" data-code="ID">Idaho</option>
              <option value="Illinois" data-code="IL">Illinois</option>
              <option value="Indiana" data-code="IN">Indiana</option>
              <option value="Iowa" data-code="IA">Iowa</option>
              <option value="Kansas" data-code="KS">Kansas</option>
              <option value="Kentucky" data-code="KY">Kentucky</option>
              <option value="Louisiana" data-code="LA">Louisiana</option>
              <option value="Maine" data-code="ME">Maine</option>
              <option value="Maryland" data-code="MD">Maryland</option>
              <option value="Massachusetts" data-code="MA">Massachusetts</option>
              <option value="Michigan" data-code="MI">Michigan</option>
              <option value="Minnesota" data-code="MN">Minnesota</option>
              <option value="Mississippi" data-code="MS">Mississippi</option>
              <option value="Missouri" data-code="MO">Missouri</option>
              <option value="Montana" data-code="MT">Montana</option>
              <option value="Nebraska" data-code="NE">Nebraska</option>
              <option value="Nevada" data-code="NV">Nevada</option>
              <option value="New Hampshire" data-code="NH">New Hampshire</option>
              <option value="New Jersey" data-code="NJ">New Jersey</option>
              <option value="New Mexico" data-code="NM">New Mexico</option>
              <option value="New York" data-code="NY">New York</option>
              <option value="North Carolina" data-code="NC">North Carolina</option>
              <option value="North Dakota" data-code="ND">North Dakota</option>
              <option value="Ohio" data-code="OH">Ohio</option>
              <option value="Oklahoma" data-code="OK">Oklahoma</option>
              <option value="Oregon" data-code="OR">Oregon</option>
              <option value="Pennsylvania" data-code="PA">Pennsylvania</option>
              <option value="Rhode Island" data-code="RI">Rhode Island</option>
              <option value="South Carolina" data-code="SC">South Carolina</option>
              <option value="South Dakota" data-code="SD">South Dakota</option>
              <option value="Tennessee" data-code="TN">Tennessee</option>
              <option value="Texas" data-code="TX">Texas</option>
              <option value="Utah" data-code="UT">Utah</option>
              <option value="Vermont" data-code="VT">Vermont</option>
              <option value="Virginia" data-code="VA">Virginia</option>
              <option value="Washington" data-code="WA">Washington</option>
              <option value="West Virginia" data-code="WV">West Virginia</option>
              <option value="Wisconsin" data-code="WI">Wisconsin</option>
              <option value="Wyoming" data-code="WY">Wyoming</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', setUpFloatingLabelStyles)
  const newCaseModal = document.querySelector('#newCaseModal');
  newCaseModal.addEventListener('show.bs.modal', () => {
    getNewCaseId();
  })

  function setUpFloatingLabelStyles() {
    const inputs = [...document.querySelectorAll('input')];
    inputs.forEach(input => {
      input.addEventListener('focus', () => {
        const lableId = input.dataset.label;
        const labelEl = document.querySelector(lableId);
        labelEl.classList.add('float');
      });
      input.addEventListener('blur', () => {
        const lableId = input.dataset.label;
        const labelEl = document.querySelector(lableId);
        if (!input.value) {
          labelEl.classList.remove('float');
        }
      });
    })
  }




  function getNewCaseId() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      const response = JSON.parse(this.responseText.replace(',', ', '));
      if (response?.error) {
        alert(error);
      } else {
        const id = response.newId;
        getCaseData(id);
      }

    };

    xhttp.open("GET", "lib/php/utilities/create_new_case.php", false);
    xhttp.send();
  }

  function getCaseData(id) {
    console.log({
      id
    });
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      const formatted = this.responseText.replace(',', ', ');
      const json = JSON.parse(formatted);
      const case_data = JSON.parse(json.case_data.replace(',', ', '));
      const case_types = JSON.parse(json.case_types.replace(',', ', '));
      const clinic_types = JSON.parse(json.clinic_types.replace(',', ', '));;
      console.log(json);
      setFormValues(case_data);
      setCaseTypes(case_types);
      setClinicTypes(clinic_types);

    };

    xhttp.open("GET", `lib/php/data/cases_detail_load.php?id=${id}`, false);
    xhttp.send();
  }

  function setFormValues(case_data) {
    const clinicId = document.querySelector('#clinicId');
    const clinicIdLabel = document.querySelector(clinicId.dataset.label);
    clinicId.value = case_data.clinic_id;
    clinicId.disabled = true;
  }

  function setCaseTypes(case_types) {
    const caseType = document.querySelector('#caseType');
    case_types.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.type;
        caseType.appendChild(option);
      }
    )
  }

  function setClinicTypes(clinic_types) {
    console.log(clinic_types);
    const clinicType = document.querySelector('#clinicType');
    clinic_types.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.clinic_name;
        clinicType.appendChild(option);
      }
    )
  }
</script>