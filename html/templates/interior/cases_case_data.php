<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left">

		<?php if ($type == 'new'){ echo "<b>Please enter new case data</b>";} ?>

		<?php if ($type == 'edit'){ echo "<b>Edit Case Data below:</b>";} ?>


	</div>

	<div class="case_detail_panel_tools_right">

		<?php if ($type !== 'new' AND $type !== 'edit'){?>

			<button class="case_data_edit">Edit</button>

			<button class="case_data_print">Print</button>

		<?php } ?>

	</div>

</div>

<div class="case_detail_panel_casenotes">


<?php if ($type == 'new' || $type == 'edit'){ ?>

	<div class="new_case_data">

		<form>

			<?php foreach ($data as $d) {extract($d) ?>

			<p>
				<label><?php echo $display_name; ?></label>

				<?php if ($input_type === 'text'){ ?>

					<input type="text" name = "<?php echo $db_name; ?>" value = "<?php echo $value; ?>">

				<?php } elseif ($input_type === 'date'){ ?>

					<input type="hidden" class="date_field" name = "<?php echo $db_name; ?>" value = "<?php echo $value; ?>">

				<?php } elseif ($input_type === 'select') { ?>

					<select name = "<?php echo $db_name; ?>">

						<option value=""> --- </option>

						<?php
							$s = unserialize($select_options);

							foreach ($s as $key => $val) {

							if ($key == $value)
								{echo "<option value = '$key' selected=selected>$val</option>";}
							else
								{echo "<option value = '$key'>$val</option>";}

						} ?>

					</select>

				<?php } elseif ($input_type === 'select_multiple'){ ?>

					<select multiple name = "<?php echo $db_name; ?>">

						<?php foreach ($variable as $key => $value) { ?>
							# code...
						<?php } ?>

					</select>

				<?php } elseif ($input_type === 'textarea'){ ?>

					<textarea name = "<?php echo $db_name; ?>"></textarea>

				<?php } ?>

			</p>

			<?php } ?>

			<p>
				<button class="case_modify_submit <?php if ($type == 'new') {echo 'update_new_case';}?>">
				Submit
				</button>
			</p>

		</form>

	</div>

<?php } else { ?>

	<div class = "case_data">

		<?php foreach ($data as $d) {extract($d) ?>

		<div class = "<?php echo $db_name;?>_display case_data_display">
				<div class = "case_data_name"><?php echo $display_name; ?></div>

				<div class="case_data_value"><?php echo $value; ?></div>
		</div>

		<?php } ?>

	</div>



<?php } ?>
</div>
