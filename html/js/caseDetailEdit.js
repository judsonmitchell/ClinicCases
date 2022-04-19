

 
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

  function setCourts(courts) {
    const court = document.querySelector('#court');
    courts.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.court;
        court.appendChild(option);
      }
    )
  }


  function setReferrals(referrals) {
    const referral = document.querySelector('#referral');
    referrals.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.referral;
        referral.appendChild(option);
      }
    )
  }

  function setDispositions(dispositions) {
    const disposition = document.querySelector('#disposition');
    dispositions.forEach(
      type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.innerText = type.dispo;
        disposition.appendChild(option);
      }
    )
  }



  const addPhoneButton = document.querySelector('[data-target="#phoneNumbers"]');
  addPhoneButton.addEventListener('click', addPhoneFieldset);

  function addPhoneFieldset(e) {
    const id = this.dataset.target;
    const i = this.dataset.add;
    const container = document.querySelector(id);
    const arrayGrid = document.createElement('div');
    arrayGrid.classList.add('array__grid');
    arrayGrid.innerHTML = ` 
              <div class="form__control form__control--select">
                <select name="phoneType-${i}" id="phoneType-${i}">
                  <option value="home">Home</option>
                  <option value="work">Work</option>
                  <option value="mobile">Mobile</option>
                  <option value="fax">Fax</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form__control">
                <input id="phone-${i}" data-label="#phoneLabel-${i}" name="phone-${i}" type="text" />
              </div>
           `;
    this.remove();
    this.dataset.add = i + 1;
    arrayGrid.appendChild(this);
    container.appendChild(arrayGrid);
  }

  const addEmailButton = document.querySelector('[data-target="#emails"]');
  addEmailButton.addEventListener('click', addEmailFieldset);

  function addEmailFieldset(e) {
    const id = this.dataset.target;
    const i = this.dataset.add;
    const container = document.querySelector(id);
    const arrayGrid = document.createElement('div');
    arrayGrid.classList.add('array__grid');
    arrayGrid.innerHTML = ` 
              <div class="form__control form__control--select">
                <select name="emailType-${i}" id="emailType-${i}">
                  <option value="home">Home</option>
                  <option value="work">Work</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form__control">
                <input id="email-${i}" data-label="#emailLabel-${i}" name="email-${i}" type="text" />
              </div>
           `;
    this.remove();
    this.dataset.add = i + 1;
    arrayGrid.appendChild(this);
    container.appendChild(arrayGrid);
  }

  const addAdversePartyButton = document.querySelector('[data-target="#adverseParties"]');
  addAdversePartyButton.addEventListener('click', addAdversePartyFieldset);

  function addAdversePartyFieldset() {
    const id = this.dataset.target;
    const i = this.dataset.add;
    const container = document.querySelector(id);
    const arrayGrid = document.createElement('div');
    arrayGrid.classList.add('array__grid', 'array__grid--two');
    arrayGrid.innerHTML = ` 
              <div class="form__control">
                <input id="adverseParty-${i}" data-label="#adverseParty-${i}" name="adverseParty-${i}" type="text" />
              </div>
           `;
    this.remove();
    this.dataset.add = i + 1;
    arrayGrid.appendChild(this);
    container.appendChild(arrayGrid);
  }