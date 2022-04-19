<!-- Modal -->
<div class="modal fade" id="newCaseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCaseLabel">New Case</h5>
      </div>
      <div class="modal-body">
        <form id="addCaseForm">
          <?php include('case_add.php') ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal">Cancel</button>
        <button id="updateCaseButton" type="button" class="primary-button">Create</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="html/js/forms.js"></script>

<script>
  getCaseData();

  let case_id;

  const updateCaseButton = document.querySelector('#updateCaseButton');
  updateCaseButton.addEventListener('click', createNewCase)


  function getCaseData(id) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      const clinic_types = JSON.parse(this.responseText.replace(',', ', '));
      setClinicTypes(clinic_types);
    };

    xhttp.open("GET", `lib/php/data/clinic_types_load.php?id=${id}`, false);
    xhttp.send();
  }

  function setClinicTypes(clinic_types) {
    const clinicType = document.querySelector('#newCaseClinicType');
    clinic_types.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.clinic_name;
        clinicType.appendChild(option);
      }
    )
  }

  function createNewCase() {

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
      const response = JSON.parse(this.responseText.replace(',', ', '));
      if (response?.error) {
        alert(error);
      } else {
        case_id = response.newId;
        alertify.success(`Case created!`);
        openCase(case_id)
      }
    };

    try {
      const form = document.querySelector('#addCaseForm');
      if (!form.checkValidity()) {
        form.classList.remove('invalid');
        const invalidFields = [];
        form.classList.add('invalid');
        form.elements.forEach((el) => {
          if (!el.checkValidity()) {
            invalidFields.push(el.name);
          }
        });

        throw new Error(`Fix invalid fields: ${invalidFields.join(', ')}`)
      }
      const values = [...form.elements].reduce((total, el) => {

        total[el.name] = el.value;
        return total;
      }, {});
      console.log({
        values
      });


      const queryString = Object.keys(values).map(key => {
        return encodeURIComponent(key) + '=' + encodeURIComponent(values[key])
      }).join('&');
      console.log({queryString})
      xhttp.open("POST", `lib/php/utilities/create_new_case.php`, values, false);
      xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhttp.send(queryString);
    } catch (error) {
      alertify.error(error.message);
    }

  }

  // function getCaseData(id) {
  //   var xhttp = new XMLHttpRequest();
  //   xhttp.onreadystatechange = function() {
  //     console.log(this.responseText);
  //     const formatted = this.responseText.replace(',', ', ');
  //     const json = JSON.parse(formatted);
  //     const case_data = JSON.parse(json.case_data.replace(',', ', '));

  //     setFormValues(case_data);

  //   };

  //   xhttp.open("GET", `lib/php/data/cases_detail_load.php?id=${id}`, false);
  //   xhttp.send();
  // }

  // function setFormValues(case_data) {
  //   const clinicId = document.querySelector('#clinicId');
  //   console.log(clinicId);
  //   const clinicIdLabel = document.querySelector(clinicId.dataset.label);
  //   clinicId.value = case_data.clinic_id;
  //   clinicId.disabled = true;
  // }

  function updateCase(id) {
    console.log('update case');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      // const response = JSON.parse(this.responseText);
      console.log(this.responseText);
      if (response.error) {
        alertify.error(response.error);
      } else {
        console.log('open case')
        alertify.success(response.message);
        openCase(id);

      }

    };
    const form = document.querySelector('form');
    const values = [...form.elements].reduce((total, el) => {
      total[el.name] = el.value;
      return total;
    }, []);
    const body = {
      id: id,
      action: 'update_new_case',
      values
    }
    var prms = new URLSearchParams(values);

    xhttp.open("POST", `lib/php/data/cases_case_data_process.php`, false);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhttp.send(`id=${case_id}&action=update_new_case&${prms.toString()}`);
  }
</script>