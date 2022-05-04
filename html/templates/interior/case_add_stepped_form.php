<div id="addNewCaseForm">
    <form id="stepOne" class="fadeI">
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
            <button data-current="#stepOne" data-next="#stepTwo" type="button" class="primary-button stepped-form-button next">Next</button>
        </div>


    </form>
    <form class="hidden fadeIn" id="stepTwo">
        <div class="form__control">
            <label class="float--lock" id="newCaseCaseTypeLabel" for="newCaseCaseType">Case Type</label>
            <select required id="newCaseCaseType" data-label="#newCaseCaseypeLabel" name="case_type" type="text"></select>
        </div>
        <div class="form__control">
            <label id="newCaseAddress1Label" for="newCaseAddress1">Address 1</label>
            <input required id="newCaseAddress1" data-label="#newCaseAddress1Label" name="address1" type="text" />
        </div>
        <div class="form__control">
            <label id="newCaseAddress2Label" for="newCaseAddress2">Address 2</label>
            <input required id="newCaseAddress2" data-label="#newCaseAddress2Label" name="address2" type="text" />
        </div>
        <div class="form__control">
            <label id="newCaseCityLabel" for="newCaseCity">City</label>
            <input required id="newCaseCity" data-label="#newCaseCityLabel" name="city" type="text" />
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form__control">
                    <label id="newCaseStateLabel" for="newCaseState">State</label>
                    <input required id="newCaseState" data-label="#newCaseStateLabel" name="state" type="text" />
                </div>

            </div>
            <div class="col-6">
                <div class="form__control">
                    <label id="newCaseZipLabel" for="newCaseZip">Zip</label>
                    <input required id="newCaseZip" pattern='^[0-9]{5}(?:-[0-9]{4})?$' data-label="#newCaseZipLabel" name="zip" type="text" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-current="#stepTwo" data-prev="#stepOne" class="stepped-form-button prev">Back</button>
            <button data-current="#stepTwo" data-next="#stepThree" type="button" class="primary-button stepped-form-button next">Next</button>
        </div>
    </form>
    <form action="" class="hidden fadeIn" id="stepThree">
        <div id="newCasePhoneContainer" class="form-control__multiple">
            <div class="form__add">
                <i class="add-item-button" data-container="#newCasePhoneContainer" data-field="phone"><img src="html/ico/add-item.svg" alt="Add item"></i>
            </div>
            <div class="form-control__dual">
                <div class="form__control form__control--select">
                    <label class="float--lock" id="phone0Label" for="phone0">Phone Select</label>
                    <select required="" data-dual="true" name="phone_select" id="phone0" value="home">
                        <option value="" disabled="">Select one...</option>
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                        <option value="mobile">Mobile</option>
                        <option value="fax">Fax</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form__control">
                    <label id="phone0Label" for="phone0" class=" float ">Phone </label>
                    <input required="" id="phone0" data-label="#phone0Label" type="phone" name="phone" data-dual="true" value="">
                </div>
            </div>
        </div>
        <div id="newCaseEmailContainer" class="form-control__multiple">
            <div class="form__add">
                <i class="add-item-button" data-container="#newCaseEmailContainer" data-field="email"><img src="html/ico/add-item.svg" alt="Add item"></i>
            </div>
            <div class="form-control__dual">
                <div class="form__control form__control--select">
                    <label class="float--lock" id="email0Label" for="email0">Email Select</label>
                    <select required="" data-dual="true" name="email_select" id="email0" value="Home">
                        <option value="" disabled="">Select one...</option>
                        <option value="Home">Home</option>
                        <option value="Work">Work</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form__control">
                    <label id="email0Label" for="email0" class=" float ">Email </label>
                    <input required="" id="email0" data-label="#email0Label" type="email" name="email" data-dual="true" value="">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-current="#stepThree" data-prev="#stepTwo" class="stepped-form-button prev">Back</button>
            <button data-current="#stepThree" data-next="#stepFour" type="button" class="primary-button stepped-form-button next">Next</button>
        </div>
    </form>
</div>
<script>
    getOptions();
    addItemButtons();

    async function getOptions() {
        const columns = await axios.get('lib/php/data/cases_columns_load.php').then((res) => res.data.aoColumns);
        const selects = [...document.querySelectorAll('select')];
        selects.forEach(select => {
            const column = columns.find(col => col.fieldName === select.name);
            if (column) {
                let options = '<option value="" selected disabled>Select one... </option>';
                options = options.concat(Object.keys(column.selectOptions).map(option => {
                    return `<option value="${option}">${column.selectOptions[option]} </option>`
                }).join(''));
                select.innerHTML = options;
            }
        })
    }
    const formButtons = [...document.querySelectorAll('.stepped-form-button.next')];
    formButtons.forEach(button => {
        button.addEventListener('click', validateStep)
    })
    const prevButtons = [...document.querySelectorAll('.stepped-form-button.prev')];
    prevButtons.forEach(button => {
        button.addEventListener('click', goToPrev)
    })
    const cancelButtons = document.querySelectorAll('.dismiss');
    cancelButtons.forEach(button => {
        button.addEventListener('click', cancel)
    });




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
            setTimeout(() => {
                current.classList.add('hidden');
                next.classList.remove('hidden')
            }, 300)
            current.classList.remove('invalid');
            current.classList.add('opacity-0');
            next.classList.remove('opacity-0')
        } else {
            current.classList.add('invalid');
            alertify.error(`Fix ${state} fields.`)
        }
    }

    function goToPrev() {
        const current = document.querySelector(this.dataset.current);
        const prev = document.querySelector(this.dataset.prev);
        setTimeout(() => {
            current.classList.add('hidden');
            prev.classList.remove('hidden')
        }, 300)
        current.classList.add('opacity-0');

        prev.classList.remove('opacity-0')

    }

    function addItemButtons() {
        const addItemButtons = document.querySelectorAll('#newCaseModal .add-item-button');
        addItemButtons.forEach(button => {
            button.addEventListener('click', () => addNewItem(button))
        })
    }

    function submit() {
        const dualInputs = document.querySelectorAll(
            `#newCaseForm .form-control__dual`
        );
        const dualInputValues = getDualInputValues(dualInputs)
    }
</script>