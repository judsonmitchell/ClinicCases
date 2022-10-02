<div id="addNewCaseForm">
    <form>
        <div class="form__control">
            <input placeholder=" " required id="newCaseFirstName" data-label="#newCaseFirstNameLabel" name="first_name" type="text" />
            <label id="newCaseFirstNameLabel" for="newCaseFirstName">First Name</label>
        </div>

        <div class="form__control">
            <input placeholder=" " required id="newCaseLastName" data-label="#newCaseLastNameLabel" name="last_name" type="text" />
            <label id="newCaseLastNameLabel" for="newCaseLastName">Last Name</label>
        </div>

        <div class="form__control">
            <input placeholder=" " required id="newCaseOrganization" data-label="#newCaseOrganizationLabel" name="organization" type="text" />
            <label id="newCaseOrganizationLabel" for="newCaseOrganization">Organization</label>
        </div>

        <div class="modal-footer">
            <button type="button" class="dismiss">Cancel</button>
            <button id="newCaseSubmitButton" type="button" class="primary-button">Submit</button>
        </div>


    </form>
</div>
<script>
    const newCaseSubmitButton = document.querySelector("#newCaseSubmitButton");
    newCaseSubmitButton.addEventListener('click', submit)

    const cancelButtons = document.querySelectorAll('.dismiss');
    cancelButtons.forEach(button => {
        button.addEventListener('click', cancel)
    });




    function cancel() {

        alertify.confirm('Confirm', 'Are you sure you to cancel? You will lose your data.', () => {
            const newCaseModal = bootstrap.Modal.getInstance(document.querySelector('#newCaseModal'));

            const form = document.querySelector('#newCaseModal form');
            // MOVE THIS TO ANOTHER FILE TO IMPORT RESET FORM NINA
            resetForm(form);
            newCaseModal.hide();

        }, null)
    }



    async function submit() {
        const form = document.querySelector('#newCaseModal form');
        form.classList.remove('invalid');
        const state = checkFormValidity(form);
        if (state === true) {

            const formValues = getFormValues(form);
            const response = await axios.post('lib/php/utilities/create_new_case.php', {
                params: formValues
            }).then(res => res.data);
            if (!response.error) {
                resetForm(form);
                const newCaseModal = bootstrap.Modal.getInstance(document.querySelector('#newCaseModal'));
                newCaseModal.hide();
               
                openCase(response.newId, `${formValues.last_name}, ${formValues.last_name}`);
            }
        } else {
            form.classList.add('invalid');
            alertify.error(`Fix ${state} fields.`)
        }
    }
</script>