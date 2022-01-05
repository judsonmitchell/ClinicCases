<!-- Modal -->
<div class="modal fade" id="newCaseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCaseLabel">New Case</h5>
      </div>
      <div class="modal-body">
        <form>
          <?php include('case_add.php') ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal">Cancel</button>
        <button id="updateCaseButton" data-bs-dismiss="modal" type="button">Create</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="html/js/forms.js"></script>

<script>
  let case_id;

  const updateCaseButton = document.querySelector('#updateCaseButton');
  updateCaseButton.addEventListener('click', updateCase)
  
  const newCaseModal = document.querySelector('#newCaseModal');
  newCaseModal.addEventListener('show.bs.modal', () => {
    getNewCaseId();
  })

  function getNewCaseId() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      const response = JSON.parse(this.responseText.replace(',', ', '));
      if (response?.error) {
        alert(error);
      } else {
        case_id = response.newId;
        getCaseData(case_id);
      }

    };


    xhttp.open("GET", "lib/php/utilities/create_new_case.php", false);
    xhttp.send();
  }

  function getCaseData(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      console.log(this.responseText);
      const formatted = this.responseText.replace(',', ', ');
      const json = JSON.parse(formatted);
      const case_data = JSON.parse(json.case_data.replace(',', ', '));
      // const case_types = JSON.parse(json.case_types.replace(',', ', '));
      // const clinic_types = JSON.parse(json.clinic_types.replace(',', ', '));
      // const courts = JSON.parse(json.courts.replace(',', ', '));
      // const referrals = JSON.parse(json.referrals.replace(',', ', '));
      // const dispositions = JSON.parse(json.dispositions.replace(',', ', '));

      setFormValues(case_data);
      // setCaseTypes(case_types);
      // setClinicTypes(clinic_types);
      // setCourts(courts);
      // setReferrals(referrals);
      // setDispositions(dispositions);
    };

    xhttp.open("GET", `lib/php/data/cases_detail_load.php?id=${id}`, false);
    xhttp.send();
  }

  function setFormValues(case_data) {
    const clinicId = document.querySelector('#clinicId');
    console.log(clinicId);
    const clinicIdLabel = document.querySelector(clinicId.dataset.label);
    clinicId.value = case_data.clinic_id;
    clinicId.disabled = true;
  }

  function updateCase() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      const response = JSON.parse(this.responseText);
      if (response.error) {

      } else {
        // todo show success message using response.message
        openCase(case_id);

      }

    };
    const form = document.querySelector('form');
    const values = getFormValues(form);
    const body = {
      id: case_id,
      action: 'update_new_case',
      values
    }
    var prms = new URLSearchParams(values);

    xhttp.open("POST", `lib/php/data/cases_case_data_process.php`, false);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhttp.send(`id=${case_id}&action=update_new_case&${prms.toString()}`);
  }

  // delete case
</script>