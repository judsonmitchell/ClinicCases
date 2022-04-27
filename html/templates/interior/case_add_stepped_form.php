<div id="addNewCaseForm">
    <form id="stepOne">
        <?php if ($case_id) :
        ?>
            <div class="form__control">
                <label id="newCaseClinicIdLabel" for="newCaseClinicId" class="float--lock">Case Number <span class="let-me-edit-this" data-target="<?php echo $case_id ?>">Let me edit this</span> </label>
                <input <?php if ($col['db_name'] == 'clinic_id') { ?> disabled <?php } ?> <?php if ($col['required'] && $col['db_name'] != 'date_close') { ?> required <?php } ?> id="<?php echo $col['db_name'] ?>" data-label="#<?php echo $col['db_name'] ?>Label" type="<?php echo $col['input_type'] ?>" name="<?php echo $col['db_name'] ?>" value="<?php echo $col['value'] ?>">
            </div>
        <?php endif; ?>


        <div class="form__control">
            <label id="newCaseFirstNameLabel" for="newCaseFirstName">First Name</label>
            <input required id="newCaseFirstName" data-label="#newCaseFirstNameLabel" name="first_name" type="text" />
        </div>

        <div class="form__control">
            <label id="newCaseLastNameLabel" for="newCaseLastName">Last Name</label>
            <input required id="newCaseLastName" data-label="#newCaseLastNameLabel" name="last_name" type="text" />
        </div>
        <div class="form__control">
            <label class="float--lock" id="newCaseClinicTypeLabel" for="newCaseClinicType">Clinic Type</label>
            <select required id="newCaseClinicType" data-label="#newCaseClinicTypeLabel" name="clinic_type" type="text"></select>
        </div>

        <div class="form__control">
            <label id="newCaseOrganizationLabel" for="newCaseOrganization">Organization</label>
            <input required id="newCaseOrganization" data-label="#newCaseOrganizationLabel" name="organization" type="text" />
        </div>
        <div class="form__control">
            <label class="float--lock" id="newCaseDateOpenLabel" for="newCaseDateOpen">Date Open</label>
            <input required id="newCaseDateOpen" data-label="#newCaseDateOpenLabel" name="date_open" type="date" />
        </div>
        <div class="modal-footer">
            <button type="button" class="dismiss">Cancel</button>
            <button data-current="#stepOne" data-next="#stepTwo" type="button" class="primary-button stepped-form-button">Next</button>
        </div>


    </form>

</div>
<script>
    const getOptions = async () => {
        const columns = await axios.get('lib/php/data/cases_columns_load.php').then((res) => res.data.aoColumns);
        const selects = [...document.querySelectorAll('select')];
        selects.forEach(select => {
            const column = columns.find(col => col.fieldName === select.name);
            const options = Object.keys(column.selectOptions).map(option => {
                return `<option value="${option}">${column.selectOptions[option]} </option>`
            }).join('');
            select.innerHTML = options;
        })
    }
    const formButtons = [...document.querySelectorAll('.stepped-form-button')];
    formButtons.forEach(button => {
        button.addEventListener('click', validateStep)
    })
    const cancelButtons = document.querySelectorAll('.dismiss');
    cancelButtons.forEach(button => {
        button.addEventListener('click', cancel)
    });

    getOptions();


    function cancel() {

        alertify.confirm('Confirm', 'Are you sure you to cancel? You will lose your data.', () => {
            const newCaseModal = bootstrap.Modal.getInstance(document.querySelector('#newCaseModal'));
            const forms = document.querySelectorAll('#newCaseModal form');
            forms.forEach(form => {
                resetForm(form);
            })
            newCaseModal.hide();

        }, null)
    }

    function validateStep() {

        const current = document.querySelector(this.dataset.current);
        const next = document.querySelector(this.dataset.next);
        const state = checkFormValidity(current);
        if (state === true) {
            current.classList.remove('invalid');
            current.classList.add('.hidden');
            next.classList.remove('hidden')
        } else {
            current.classList.add('invalid');
            alertify.error(`Fix ${state} fields.`)
        }
    }
</script>