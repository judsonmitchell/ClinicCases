<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left">

		<b>Please enter new case data</b>

	</div>

	<div class="case_detail_panel_tools_right">

	</div>

</div>

<div class="case_detail_panel_casenotes">


<?php if ($type = 'new'){ ?>

	<div class="new_case_data">

		<form>

			<?php foreach ($data as $d) {extract($d) ?>

			<p>
				<label><?php echo $display_name; ?></label>

				<?php if ($input_type === 'text'){ ?>

					<input type="text" name = "<?php echo $db_name; ?>" value = "<?php echo $value; ?>">

				<?php } elseif ($input_type === 'date'){ ?>

					<input type="hidden" class="date_field" name = "<?php echo $db_name; ?>" value = "<?php echo sql_date_to_us_date($value); ?>">

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

			<p><button class="new_case_submit">Submit</button></p>

		</form>

	</div>

<?php } else {} ?>

</div>
