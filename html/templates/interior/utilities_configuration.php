<div class = "config_forms">

	<div class="config_item">

		<div id="case">

			<a href="#" class="config_item_link closed">Change Case Types</a>

			<form name = "case_form" class = "config_form" data-type="case">

				<p>
					<label>Case Type Code</label>
					<input name="case_code[]" class="cl_code" type="text" maxlength="4" title="Add a new case type code (4 letters max)">

					<label>Case Type</label>
					<input name="case[]" type="text" class="val_add" title="Add a new case type"><a href="#" class="change_config add" title="Add a new case_type"><img src="html/ico/add.png"></a></p>

				<?php foreach($case_types as $ct){extract($ct)?>

				<p>
					<label>Case Type Code</label>
					<input name="case_code[]" class="cl_code" type="text" value="<?php echo htmlspecialchars($case_type_code,ENT_QUOTES,'UTF-8'); ?>" maxlength="4">

					<label>Case Type</label>
					<input name="case[]" type="text" value="<?php echo htmlspecialchars($type,ENT_QUOTES,'UTF-8');?>" data-id="<?php echo $id; ?>">

					<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($type,ENT_QUOTES,'UTF-8');?>"><img src="html/ico/cancel.png"></a>
				</p>

				<?php } ?>

			</form>

		</div>

	</div>

	<div class="config_item">

		<div id="courts">

			<a href="#" class="config_item_link closed">Change Courts</a>

			<form name = "court_form" class = "config_form" data-type="court">

				<p><input name="court[]" type="text" class="val_add" title="Add a new court"><a href="#" class="change_config add" title="Add a new court"><img src="html/ico/add.png"></a></p>

				<?php foreach($courts as $c){extract($c)?>

				<p>
					<input name="court[]" type="text" value="<?php echo htmlspecialchars($court,ENT_QUOTES,'UTF-8');?>" data-id="<?php echo $id; ?>">

					<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($court,ENT_QUOTES,'UTF-8');?>"><img src="html/ico/cancel.png"></a>
				</p>

				<?php } ?>

			</form>

		</div>

	</div>

	<div class="config_item">

		<div id="dispo">

			<a href="#" class="config_item_link closed">Change Dispositions</a>

			<form name = "dispo_form" class = "config_form" data-type="dispo">

				<p><input name="dispo[]" type="text" class="val_add" title="Add a new disposition"><a href="#" class="change_config add"><img src="html/ico/add.png" title="Add a new disposition"></a></p>

				<?php foreach($dispos as $d){extract($d)?>

				<p>
					<input name="dispo[]" type="text" value="<?php echo htmlspecialchars($dispo,ENT_QUOTES,'UTF-8');?>" data-id="<?php echo $id; ?>" title="Delete <?php echo htmlspecialchars($dispo,ENT_QUOTES,'UTF-8');?>">
					<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($dispo,ENT_QUOTES,'UTF-8');?>"><img src="html/ico/cancel.png"></a>
				</p>

				<?php } ?>

			</form>

		</div>

	</div>

	<div class="config_item">

		<div id ="clinic">

			<a href="#" class="config_item_link closed">Change Clinic Types</a>

			<form name = "clinic_form" class = "config_form config_form_multi" data-type="clinic">

				<p>
					<label>Clinic Code</label>
					<input name="clinic_code[]" class="cl_code" type="text" maxlength="4" title="Add a new clinic code (4 letters max)">

					<label>Clinic Name</label>
					<input name="clinic_name[]" type="text" class="val_add" title="Add a new clinic name"><a href="#" class="change_config add"><img src="html/ico/add.png" title="Add a new clinic name"></a>
				</p>

				<?php foreach($clinic_types as $ct){extract($ct)?>

				<p>
					<label>Clinic Code</label>
					<input name="clinic_code[]" class="cl_code" type="text" value="<?php echo htmlspecialchars($clinic_code,ENT_QUOTES,'UTF-8'); ?>" maxlength="4">

					<label>Clinic Name</label>
					<input name="clinic_name[]" type="text" value="<?php echo htmlspecialchars($clinic_name,ENT_QUOTES,'UTF-8');?>" data-id="<?php echo $id; ?>">
					<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($clinic_name,ENT_QUOTES,'UTF-8');?>"><img src="html/ico/cancel.png"></a>
				</p>

				<?php } ?>

			</form>

		</div>

	</div>

	<div class="config_item">

		<div id="referral">

			<a href="#" class="config_item_link closed">Change Referrals</a>

			<form name="referral_form" class = "config_form" data-type="referral">

				<p><input name="referral[]" type="text" class="val_add" title="Add a new referral source"><a href="#" class="change_config add"><img src="html/ico/add.png" title="Add a new referral source"></a></p>

				<?php foreach($referral as $f){extract($f)?>

				<p>
					<input name="referral[]" type="text" value="<?php echo htmlspecialchars($referral,ENT_QUOTES,'UTF-8');?>" data-id="<?php echo $id; ?>">
					<a href="#" class="change_config" title="Delete <?php echo htmlspecialchars($referral,ENT_QUOTES,'UTF-8');?>"><img src="html/ico/cancel.png"></a>
				</p>

				<?php } ?>
			</form>

		</div>

	</div>

</div>
