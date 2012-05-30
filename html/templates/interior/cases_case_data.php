<?php if ($type = 'new'){ ?>

	<form>

		<?php foreach ($data as $d) {extract($d) ?>

		<p>
			<label><?php echo $display_name; ?></label>

			<?php if ($input_type === 'text'){ ?>

				<input type="text" name = "<?php echo $db_name; ?>" value = "<?php echo $value; ?>">

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

	</form>

<?php } else {} ?>
