        <!-- TODO do we need this view? -->
        <!-- Divide here -->
        <div class="two-columns">
          <div class="form__control form__control--select">
            <label class="float--lock" id="caseTypeLabel" for="caseType">Case Type</label>
            <select id="caseType">
              <option value="" disabled selected>Select one...</option>

            </select>
          </div>
          <div class="form__control form__control--select">
            <label class="float--lock" id="clinicTypeLabel" for="clinicType">Clinic Type</label>
            <select id="clinicType">
              <option value="" disabled selected>Select one...</option>
            </select>
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
          <div class="form__control form__control--select">
            <label class="float--lock" id="stateLabel" for="state">State</label>
            <select id="state">
              <option value="" data-code="" disabled selected>Select one...</option>
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
        <div class="form__control">
          <label id="zipcodeLabel" for="zipcode">Zipcode</label>
          <input id="zipcode" data-label="#zipcodeLabel" name="zipcode" type="text" />
        </div>
        <!-- Divide here -->

        <div class="form__array">
          <fieldset id="phoneNumbers">
            <legend>Phone</legend>
            <div class="array__grid">
              <div class="form__control form__control--select">
                <select name="phoneType-1" id="phoneType-1">
                  <option value="home">Home</option>
                  <option value="work">Work</option>
                  <option value="mobile">Mobile</option>
                  <option value="fax">Fax</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form__control">
                <input id="phone-1" data-label="#phoneLabel-1" name="phone-1" type="text" />
              </div>
              <button data-add="2" data-target="#phoneNumbers" class="button__icon">
                <img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
              </button>
            </div>
          </fieldset>

        </div>
        <div class="form__array">
          <fieldset id="emails">
            <legend>Email</legend>
            <div class="array__grid">
              <div class="form__control form__control--select">
                <select name="emailType-1" id="emailType-1">
                  <option value="home">Home</option>
                  <option value="work">Work</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form__control">
                <input id="email-1" data-label="#emailLabel-1" name="email-1" type="text" />
              </div>
              <button data-add="2" data-target="#emails" class="button__icon">
                <img src="html/ico/add-item.svg" alt="Plus sign button to add another email">
              </button>
            </div>
          </fieldset>

        </div>
        <!-- Divide here -->

        <div class="form__control">
          <label id="ssnLabel" for="ssn">SSN</label>
          <input id="ssn" data-label="#ssnLabel" name="ssn" type="text" />
        </div>
        <div class="form__control">
          <label class="float--lock" id="birthdayLabel" for="birthday">Birthday</label>
          <input id="birthday" data-label="#birthdayLabel" name="birthday" type="date" />
        </div>
        <div class="form__control">
          <label id="ageLabel" for="age">Age</label>
          <input id="age" data-label="#ageLabel" name="age" type="text" />
        </div>
        <div class="form__control form__control--select">
          <label class="float--lock" id="genderLabel" for="gender">Gender</label>
          <select id="gender">
            <option value="" disabled selected>Select one...</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
        <div class="form__control form__control--select">
          <label class="float--lock" id="raceLabel" for="race">Race</label>
          <select id="race">
            <option value="african-american">African American</option>
            <option value="white">White</option>
            <option value="hispanic">Hispanic</option>
            <option value="asian">Asian</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="two-columns">
          <div class="form__control">
            <label id="incomeLabel" for="income">Income</label>
            <input id="income" data-label="#incomeLabel" name="age" type="text" />
          </div>
          <div class="form__control form__control--select">
            <label class="float--lock" id="incomePerLabel" for="incomePer">Per</label>
            <select name="incomePer" id="incomePer">
              <option value="" disabled selected>Select one...</option>
              <option value="day">Day</option>
              <option value="week">Week</option>
              <option value="month">Month</option>
              <option value="year">Year</option>
            </select>
          </div>
        </div>
        <!-- Divide here -->

        <div class="two-columns">
          <div class="form__control">
            <label id="judgeLabel" for="judge">Judge</label>
            <input id="judge" data-label="#judgeLabel" name="age" type="text" />
          </div>
          <div class="form__control form__control--select">
            <label class="float--lock" id="plOrDefLabel" for="plOrDef">Plaintiff/Defendent</label>
            <select name="plOrDef" id="plOrDef">
              <option value="" disabled selected>Select one...</option>

              <option value="plaintiff">Plaintiff</option>
              <option value="defendent">Defendent</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        <div class="form__control form__control--select">
          <label class="float--lock" id="courtLabel" for="court">Court</label>
          <select id="court">
          </select>
        </div>
        <div class="form__control">
          <label id="sectionLabel" for="section">Section</label>
          <input id="section" data-label="#sectionLabel" name="section" type="text" />
        </div>
        <div class="form__control">
          <label id="courtCaseNumberLabel" for="courseCaseNumber">Court Case Number</label>
          <input id="courseCaseNumber" data-label="#courtCaseNumberLabel" name="courseCaseNumber" type="text" />
        </div>
        <div class="form__array">
          <fieldset id="adverseParties">
            <legend>Adverse Party</legend>
            <div class="array__grid array__grid--two">
              <div class="form__control">
                <input id="adverseParty-1" data-label="#adverseParty-1" name="adverseParty-1" type="text" />
              </div>
              <button data-add="2" data-target="#adverseParties" class="button__icon">
                <img src="html/ico/add-item.svg" alt="Plus sign button to add another adverse party">
              </button>
            </div>
          </fieldset>

        </div>
        <!-- Divide here -->
        <div class="form__control">
          <label id="notesLabel" for="notes">Notes</label>
          <textarea id="notes" data-label="#notesLabel" name="notes" type="text"></textarea>
        </div>
        <!-- Divide here -->

        <div class="form__control form__control--select">
          <label class="float--lock" id="referralLabel" for="referral">Referral</label>
          <select id="referral"></select>
        </div>
        <div class="form__control">
          <label class="float--lock" id="dateCloseLabel" for="dateClose">Date Close</label>
          <input id="dateClose" data-label="#dateCloseLabel" name="dateClose" type="date" />
        </div>
        <div class="form__control form__control--select">
          <label class="float--lock" id="dispositionLabel" for="disposition">Disposition</label>
          <select id="disposition">
            <option value="" selected disabled>Select one...</option>
          </select>
        </div>
        <!-- Divide here -->

        <div class="form__control">
          <label id="closingNotesLabel" for="closingNotes">Closing Notes</label>
          <textarea id="closingNotes" data-label="#closingNotesLabel" name="closingNotes" type="text"></textarea>
        </div>

        <script type="text/javascript" src="../../js/caseDetailEdit.js"></script>