<div class="case__controls">
	<div class="controls__end">
		<button id="caseDataPrintButton" class="secondary-button">
			<img src="html/ico/printer.svg" alt="Print icon" /> <span>&nbsp;Print</span>
		</button>
		<?php if ($_SESSION['permissions']['edit_cases'] === 1) {
		?>
			<button id="editCaseButton" class="secondary-button">
				<img src="html/ico/edit.svg" alt="Edit icon" /> <span>&nbsp;Edit</span>
			</button>
		<?php
		} ?>

		<button id="caseDataCancelButton" class="cancel-button hidden">Cancel</button>
		<button id="caseDataSaveButton" class="primary-button hidden">Save</button>
	</div>
</div>
<div id="caseData">
	<div id="viewCaseData">
		<?php foreach ($dta as $col) {
		?>
			<div class="case-data__group">

				<p class="data__heading"><?php echo $col['display_name'] ?></p>
				<p class="data__value" data-displayfield="<?php echo $col['db_name'] ?>"> <?php
																																									//first check if this is a serialized value
																																									$items = unserialize($col['value']);
																																									if ($items !== false) {
																																										$val = null;
																																										foreach ($items as $key => $item) {
																																											$val .= $key . ", ";
																																										}
																																										echo htmlspecialchars(substr($val, 0, -2), ENT_QUOTES, 'UTF-8');
																																									} elseif ($col['input_type'] === 'date')
																																									//then check if it's a date
																																									{
																																										echo $col['value'];
																																										// echo sql_date_to_us_date($col['value']);
																																									} else {
																																										echo htmlspecialchars($col['value'], ENT_QUOTES, 'UTF-8');
																																									} ?></p>

			</div>
		<?php
		}
		?>

	</div>
	<form id="editCaseData" class="hidden">
		<?php foreach ($dta as $col) {

		?>
			<?php if ($col['input_type'] == 'select') { ?>
				<div class="form__control form__control--select">
					<select <?php if ($col['required']) { ?> required <?php  } ?> name="<?php echo $col['db_name'] ?>" id="<?php echo $col['db_name'] ?>" value="<?php echo $col['value'] ?>">
						<option value="" disabled <?php if (!isset($col['value'])) {
																				echo 'selected';
																			} ?>>Select one...</option>
						<?php
						$options = unserialize($col['select_options']);
						foreach ($options as $key => $option) {
						?>
							<option value="<?php echo $key ?>"><?php echo $option ?></option>
						<?php
						} 	?>
					</select>
					<label id="<?php echo $col['db_name'] ?>Label" for="<?php echo $col['db_name'] ?>"><?php echo $col['display_name']; ?></label>
				</div>
			<?php
			} else if ($col['input_type'] == 'textarea') {
			?>
				<div class="form__control">
					<textarea placeholder=" " <?php if ($col['required']) { ?> required <?php  } ?> id="<?php echo $col['db_name'] ?>" data-label="#<?php echo $col['db_name'] ?>Label" type="<?php echo $col['input_type'] ?>" name="<?php echo $col['db_name'] ?>" value="<?php echo $col['value'] ?>"></textarea>
					<label id="<?php echo $col['db_name'] ?>Label" for="<?php echo $col['db_name'] ?>" class="<?php if ($col['input_type'] == 'date') { ?>  <?php } else if (!empty($col['value'])) { ?> float <?php } ?>"><?php echo $col['display_name']; ?> </label>
				</div>
			<?php
			} else if ($col['input_type'] == 'dual') {
			?>
				<div id="<?php echo $col['db_name'] ?>Container" class="form-control__multiple">
					<div class="form__add">
						<i class="add-item-button" data-container="#<?php echo $col['db_name'] ?>Container" data-field="<?php echo $col['db_name'] ?>"><img src="html/ico/add-item.svg" alt="Add item"></i>
					</div>
					<?php
					if (!$col['value']) {
					?>

						<div class="form-control__dual">
							<div class="form__control form__control--select">
								<select <?php if ($col['required']) { ?> required <?php  } ?> data-dual="true" name="<?php echo $col['db_name'] ?>_select1" id="<?php echo $col['db_name'] ?>">
									<option value="" disabled <?php if (!isset($col['value'])) {
																							echo 'selected';
																						} ?>>Select one...</option>
									<?php
									$options = unserialize($col['select_options']);
									foreach ($options as $key => $option) {
									?>
										<option value="<?php echo $key ?>"><?php echo $option ?></option>
									<?php
									} 	?>
								</select>
								<label  id="<?php echo $col['db_name'] ?>Select1Label" for="<?php echo $col['db_name'] ?>_select1"><?php echo $col['display_name']; ?> Select</label>
							</div>
							<div class="form__control">
								<input placeholder=" " <?php if ($col['required']) { ?> required <?php  } ?> data-dual="true" <?php if ($col['db_name'] == 'clinic_id') { ?> disabled <?php } ?> <?php if ($col['required']) { ?> required <?php } ?> id="<?php echo $col['db_name'] ?>1" data-label="#<?php echo $col['db_name'] ?>1Label" type="<?php echo $col['input_type'] ?>" name="<?php echo $col['db_name'] ?>">
								<label id="<?php echo $col['db_name'] ?>1Label" for="<?php echo $col['db_name'] ?>1" class="<?php if (!empty($col['value'])) { ?> float <?php } ?>"><?php echo $col['display_name']; ?> <?php if ($col['db_name'] == 'clinic_id') { ?> <?php } ?> </label>
							</div>
						</div>
						<?php
					} else {
						$values = unserialize($col['value']);
						$count =  count(array($values));

						foreach ($values as $formValue => $selectValue) {
							$index = 0;
						?>
							<div class="form-control__dual">
								<div class="form__control form__control--select">
									<select <?php if ($col['required']) { ?> required <?php  } ?> data-dual="true" name="<?php echo $col['db_name'] ?>_select" id="<?php echo $col['db_name'] . $index ?>" value="<?php echo $selectValue ?>">
										<option value="" disabled <?php if (!isset($col['value'])) {
																								echo 'selected';
																							} ?>>Select one...</option>
										<?php
										$options = unserialize($col['select_options']);
										foreach ($options as $key => $option) {
										?>
											<option value="<?php echo $key ?>"><?php echo $option ?></option>
										<?php
										} 	?>
									</select>
									<label id="<?php echo $col['db_name'] . $index ?>Label" for="<?php echo $col['db_name'] . $index ?>"><?php echo $col['display_name']; ?> Select</label>
								</div>
								<div class="form__control">
									<input placeholder=" "  <?php if ($col['required']) { ?> required <?php  } ?> <?php if ($col['db_name'] == 'clinic_id') { ?> disabled <?php } ?> <?php if ($col['required']) { ?> required <?php } ?> id="<?php echo $col['db_name'] . $index ?>" data-label="#<?php echo $col['db_name'] . $index ?>Label" type="<?php echo $col['db_name'] ?>" name="<?php echo $col['db_name'] ?>" data-dual="true" value="<?php echo $formValue ?>">
									<label id="<?php echo $col['db_name'] . $index ?>Label" for="<?php echo $col['db_name'] . $index ?>" class="<?php if (!empty($col['value'])) { ?> float <?php } ?>"><?php echo $col['display_name']; ?> <?php if ($col['db_name'] == 'clinic_id') { ?> <?php } ?> </label>
								</div>
							</div>



					<?php
							$index++;
						}
					}
					?>

				</div>

			<?php

			} else { ?>
				<div class="form__control">
					<input placeholder=" " <?php if ($col['db_name'] == 'clinic_id') { ?> disabled <?php } ?> <?php if ($col['required'] && $col['db_name'] != 'date_close') { ?> required <?php } ?> id="<?php echo $col['db_name'] ?>" data-label="#<?php echo $col['db_name'] ?>Label" type="<?php echo $col['input_type'] ?>" name="<?php echo $col['db_name'] ?>" value="<?php echo $col['value'] ?>">
					<label id="<?php echo $col['db_name'] ?>Label" for="<?php echo $col['db_name'] ?>" class="<?php if ($col['input_type'] == 'date') { ?>  <?php } else if (!empty($col['value'])) { ?> float <?php } ?>"><?php echo $col['display_name']; ?> <?php if ($col['db_name'] == 'clinic_id') { ?> <span class="let-me-edit-this" data-target="<?php echo $case_id ?>">Let me edit this</span> <?php } ?> </label>
				</div>
			<?php
			} ?>

		<?php } ?>




	</form>


</div>