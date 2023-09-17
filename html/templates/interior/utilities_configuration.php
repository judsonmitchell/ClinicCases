<div class="config_forms">

	<div id="case">

		<button data-bs-toggle="modal" data-bs-target="#caseTypesConfig" class="config_item">Case Types</button>

		<div class="modal fade" id="caseTypesConfig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="caseTypesConfigLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="caseTypesConfigLabel">Case Types</h5>
					</div>
					<div class="modal-body">

						<form name="case_form" class="config_form" data-type="case">
							<div class="form__array">
								<fieldset id="caseForm">
									<div class="array__grid form-control__dual">


										<div class="form__control">
											<input id="case_code" name="case_code[]" class="cl_code new" type="text" maxlength="4" title="Add a new case type code (4 letters max)" placeholder=" ">
											<label for="case_code">Case Type Code</label>

										</div>

										<div class="form__control">
											<input id="case_type" name="case[]" type="text" class="val_add new" title="Add a new case type" placeholder=" ">
											<label for="case_type">Case Type</label>
										</div>
										<button data-shouldprepend="true" data-container="#caseForm" class="button__icon add-item-button">
											<img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
										</button>
									</div>
								</fieldset>



								<?php

								foreach ($case_types as $ct) {
									extract($ct);


								?>

									<div class="array__grid form-control__dual">


										<div class="form__control">
											<input required id="case_code" name="case_code[]" class="cl_code" type="text" maxlength="4" value="<?php echo htmlspecialchars($case_type_code, ENT_QUOTES, 'UTF-8'); ?>" maxlength="4" title="Add a new case type code (4 letters max)" placeholder=" ">
											<label for="case_code">Case Type Code</label>

										</div>

										<div class="form__control">
											<input required id="case_type" name="case[]" value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" data-id="<?php echo $id; ?>" type="text" class="val_add" title="Add a new case type" placeholder=" ">
											<label for="case_type">Case Type</label>
										</div>
										<button class="button__icon delete-item-button" title="Delete <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/delete.png"></button>

									</div>

								<?php
								} ?>
							</div>
							<div class="modal-footer">
								<button type="button" data-target="#caseTypesConfig" class="config_cancel">Cancel</button>
								<button id="caseTypesSubmit" data-target="#caseTypesConfig" type="button" class="primary-button config_submit">Submit</button>
							</div>

						</form>
					</div>

				</div>
			</div>
		</div>


	</div>


	<div id="courts">

		<button data-bs-toggle="modal" data-bs-target="#courtsConfig" class="config_item">Courts</button>
		<div class="modal fade" id="courtsConfig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="caseTypesConfigLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="caseTypesConfigLabel">Courts</h5>
					</div>
					<div class="modal-body">

						<form name="case_form" class="config_form" data-type="court">
							<div class="form__array">
								<fieldset id="courseForm">
									<div class="array__grid form-control__dual">

										<div></div>
										<div class="form__control">
											<input id="case_code" name="court[]" class="cl_code val_add" type="text" maxlength="4" value="" maxlength="4" title="Add a new case type code (4 letters max)" placeholder=" ">
											<label for="case_code">Court</label>

										</div>

										<button data-shouldprepend="true" data-container="#courseForm" class="button__icon add-item-button">
											<img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
										</button>
									</div>
								</fieldset>
								<?php

								foreach ($courts as $ct) {
									extract($ct);
								?>
									<div class="array__grid form-control__dual">
										<div></div>

										<div class="form__control">
											<input required id="case_code" name="court[]" class="cl_code" type="text" maxlength="4" value="<?php echo htmlspecialchars($court, ENT_QUOTES, 'UTF-8'); ?>" maxlength="4" title="Add a new case type code (4 letters max)" placeholder=" ">
											<label for="case_code">Court</label>

										</div>
										<button class="button__icon delete-item-button" title="Delete <?php echo htmlspecialchars($court, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/delete.png"></button>
									</div>
								<?php
								} ?>
							</div>
							<div class="modal-footer">
								<button type="button" data-target="#courtsConfig" class="config_cancel">Cancel</button>
								<button id="courtsSubmit" data-target="#courtsConfig" type="button" class="primary-button config_submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="dispo">
		<button data-bs-toggle="modal" data-bs-target="#disposConfig" class="config_item">Dispositions</button>

		<div class="modal fade" id="disposConfig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="disposConfigLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="caseTypesConfigLabel">Dispositions</h5>
					</div>
					<div class="modal-body">

						<form name="case_form" class="config_form" data-type="dispo">
							<div class="form__array">
								<fieldset id="disposForm">
									<div class="array__grid form-control__dual">

										<div></div>
										<div class="form__control">
											<input id="case_code" name="dispo[]" class="cl_code val_add" type="text" value="" title="Add a new dispostion (4 letters max)" placeholder=" ">
											<label for="case_code">Dispositions</label>

										</div>

										<button data-shouldprepend="true" data-container="#disposForm" class="button__icon add-item-button">
											<img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
										</button>
									</div>
								</fieldset>
								<?php

								foreach ($dispos as $ct) {
									extract($ct);
								?>
									<div class="array__grid form-control__dual">
										<div></div>

										<div class="form__control">
											<input required id="case_code" name="dispo[]" class="cl_code" type="text" value="<?php echo htmlspecialchars($dispo, ENT_QUOTES, 'UTF-8'); ?>" title="Add a new disposition code (4 letters max)" placeholder=" ">
											<label for="case_code">Disposition</label>

										</div>
										<button class="button__icon delete-item-button" title="Delete <?php echo htmlspecialchars($dispo, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/delete.png"></button>
									</div>
								<?php
								} ?>
							</div>
							<div class="modal-footer">
								<button type="button" data-target="#disposConfig" class="config_cancel">Cancel</button>
								<button id="disposSubmit" data-target="#disposConfig" type="button" class="primary-button config_submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


	</div>



	<div id="clinic">
		<button data-bs-toggle="modal" data-bs-target="#clinicTypesConfig" class="config_item">Clinic Types</button>
		<div class="modal fade" id="clinicTypesConfig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="clinicTypesConfigLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="clinicTypesConfigLabel">Clinic Types</h5>
					</div>
					<div class="modal-body">

						<form name="case_form" class="config_form" data-type="clinic">
							<div class="form__array">
								<fieldset id="clinicForm">
									<div class="array__grid form-control__dual">


										<div class="form__control">
											<input id="clinic_code" name="clinic_code[]" class="cl_code new" type="text" maxlength="4" title="Add a new clinic type code (4 letters max)" placeholder=" ">
											<label for="clinic_code">Clinic Type Code</label>

										</div>

										<div class="form__control">
											<input id="clinic_type" name="clinic_name[]" type="text" class="val_add new" title="Add a new clinic type" placeholder=" ">
											<label for="clinic_type">Clinic Name</label>
										</div>
										<button data-shouldprepend="true" data-container="#clinicForm" class="button__icon add-item-button">
											<img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
										</button>
									</div>
								</fieldset>



								<?php

								foreach ($clinic_types as $ct) {
									extract($ct);


								?>

									<div class="array__grid form-control__dual">


										<div class="form__control">
											<input required id="clinic_code" name="clinic_code[]" class="cl_code" type="text" maxlength="4" value="<?php echo htmlspecialchars($clinic_code, ENT_QUOTES, 'UTF-8'); ?>" maxlength="4" title="Add a new clinic type code (4 letters max)" placeholder=" ">
											<label for="case_code">Clinic Type Code</label>

										</div>

										<div class="form__control">
											<input required id="clinic_type" name="clinic_name[]" value="<?php echo htmlspecialchars($clinic_name, ENT_QUOTES, 'UTF-8'); ?>" data-id="<?php echo $id; ?>" type="text" class="val_add" title="Add a new case type" placeholder=" ">
											<label for="clinic_type">Case Type</label>
										</div>
										<button class="button__icon delete-item-button" title="Delete <?php echo htmlspecialchars($clinic_name, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/delete.png"></button>

									</div>

								<?php
								} ?>
							</div>
							<div class="modal-footer">
								<button type="button" data-target="#clinicTypesConfig" class="config_cancel">Cancel</button>
								<button id="clinicTypesSubmit" data-target="#clinicTypesConfig" type="button" class="primary-button config_submit">Submit</button>
							</div>

						</form>
					</div>

				</div>
			</div>
		</div>

		<!-- <form name="clinic_form" class="config_form config_form_multi" data-type="clinic">

				<p>
					<label>Clinic Code</label>
					<input name="clinic_code[]" class="cl_code" type="text" maxlength="4" title="Add a new clinic code (4 letters max)">

					<label>Clinic Name</label>
					<input name="clinic_name[]" type="text" class="val_add" title="Add a new clinic name"><a href="#" class="change_config add"><img src="html/ico/add.png" title="Add a new clinic name"></a>
				</p>

				<?php foreach ($clinic_types as $ct) {
					extract($ct) ?>

					<p>
						<label>Clinic Code</label>
						<input name="clinic_code[]" class="cl_code" type="text" value="<?php echo htmlspecialchars($clinic_code, ENT_QUOTES, 'UTF-8'); ?>" maxlength="4">

						<label>Clinic Name</label>
						<input name="clinic_name[]" type="text" value="<?php echo htmlspecialchars($clinic_name, ENT_QUOTES, 'UTF-8'); ?>" data-id="<?php echo $id; ?>">
						<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($clinic_name, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/cancel.png"></a>
					</p>

				<?php } ?>

			</form> -->

	</div>



	<div id="referral">

		<button data-bs-toggle="modal" data-bs-target="#referralsConfig" class="config_item">Referrals</button>
		<div class="modal fade" id="referralsConfig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="referralsConfigLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="caseTypesConfigLabel">Referrals</h5>
					</div>
					<div class="modal-body">

						<form name="case_form" class="config_form" data-type="referral">
							<div class="form__array">
								<fieldset id="referralForm">
									<div class="array__grid form-control__dual">

										<div></div>
										<div class="form__control">
											<input id="referral" name="referral[]" class="cl_code val_add" type="text" value="" title="Add a referral" placeholder=" ">
											<label for="referral">Referral</label>

										</div>

										<button data-shouldprepend="true" data-container="#referralForm" class="button__icon add-item-button">
											<img src="html/ico/add-item.svg" alt="Plus sign button to add another phone number">
										</button>
									</div>
								</fieldset>
								<?php

								foreach ($referral as $ct) {
									extract($ct);
								?>
									<div class="array__grid form-control__dual">
										<div></div>

										<div class="form__control">
											<input required id="referral" name="referral[]" class="cl_code" type="text" value="<?php echo htmlspecialchars($referral, ENT_QUOTES, 'UTF-8'); ?>" title="Add a new referral (4 letters max)" placeholder=" ">
											<label for="referral">Referral</label>

										</div>
										<button class="button__icon delete-item-button" title="Delete <?php echo htmlspecialchars($referral, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/delete.png"></button>
									</div>
								<?php
								} ?>
							</div>
							<div class="modal-footer">
								<button type="button" data-target="#referralsConfig" class="config_cancel">Cancel</button>
								<button id="courtsSubmit" data-target="#referralsConfig" type="button" class="primary-button config_submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- <form name="referral_form" class="config_form" data-type="referral">

				<p><input name="referral[]" type="text" class="val_add" title="Add a new referral source"><a href="#" class="change_config add"><img src="html/ico/add.png" title="Add a new referral source"></a></p>

				<?php foreach ($referral as $f) {
					extract($f) ?>

					<p>
						<input name="referral[]" type="text" value="<?php echo htmlspecialchars($referral, ENT_QUOTES, 'UTF-8'); ?>" data-id="<?php echo $id; ?>">
						<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($referral, ENT_QUOTES, 'UTF-8'); ?>"><img src="html/ico/cancel.png"></a>
					</p>

				<?php } ?>
			</form> -->


	</div>

</div>